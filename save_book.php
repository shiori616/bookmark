<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// POSTリクエストのみ受け付け
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// JSONデータを受け取る
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON data']);
    exit;
}

// 必要なデータが含まれているかチェック
$required_fields = ['title', 'authors', 'imageUrl', 'status'];
foreach ($required_fields as $field) {
    if (!isset($input[$field]) || empty($input[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing required field: $field"]);
        exit;
    }
}

// industryIdentifiersを処理
$industryIdentifiers = isset($input['industryIdentifiers']) ? $input['industryIdentifiers'] : [];

// ISBNを抽出する関数
function extractISBN($identifiers) {
    $isbn13 = '';
    $isbn10 = '';
    
    foreach ($identifiers as $identifier) {
        if (isset($identifier['type']) && isset($identifier['identifier'])) {
            if ($identifier['type'] === 'ISBN_13') {
                $isbn13 = $identifier['identifier'];
            } elseif ($identifier['type'] === 'ISBN_10') {
                $isbn10 = $identifier['identifier'];
            }
        }
    }
    
    return [
        'isbn13' => $isbn13,
        'isbn10' => $isbn10
    ];
}

$isbn_data = extractISBN($industryIdentifiers);

// 保存するデータを準備
$book_data = [
    'id' => uniqid(), // ユニークID生成
    'title' => htmlspecialchars($input['title'], ENT_QUOTES, 'UTF-8'),
    'authors' => htmlspecialchars($input['authors'], ENT_QUOTES, 'UTF-8'),
    'imageUrl' => filter_var($input['imageUrl'], FILTER_SANITIZE_URL),
    'status' => in_array($input['status'], ['read', 'tsundoku']) ? $input['status'] : 'unknown',
    'description' => isset($input['description']) ? htmlspecialchars($input['description'], ENT_QUOTES, 'UTF-8') : '',
    'industryIdentifiers' => $industryIdentifiers,
    'isbn13' => $isbn_data['isbn13'],
    'isbn10' => $isbn_data['isbn10'],
    'saved_at' => date('Y-m-d H:i:s')
];

// デバッグ用ログ
error_log("Received book data: " . json_encode($book_data));

// JSONファイルのパス
$json_file = 'books.json';

// 既存のデータを読み込み
$books = [];
if (file_exists($json_file)) {
    $json_content = file_get_contents($json_file);
    $books = json_decode($json_content, true) ?: [];
}

// 同じ本が既に存在するかチェック（タイトルと著者、またはISBNで判定）
$book_exists = false;
foreach ($books as &$existing_book) {
    $title_match = $existing_book['title'] === $book_data['title'] && 
                   $existing_book['authors'] === $book_data['authors'];
    
    $isbn_match = false;
    if (!empty($book_data['isbn13']) && !empty($existing_book['isbn13'])) {
        $isbn_match = $existing_book['isbn13'] === $book_data['isbn13'];
    } elseif (!empty($book_data['isbn10']) && !empty($existing_book['isbn10'])) {
        $isbn_match = $existing_book['isbn10'] === $book_data['isbn10'];
    }
    
    if ($title_match || $isbn_match) {
        // 既存の本の状態を更新
        $existing_book['status'] = $book_data['status'];
        $existing_book['saved_at'] = $book_data['saved_at'];
        // industryIdentifiersも更新
        if (!empty($book_data['industryIdentifiers'])) {
            $existing_book['industryIdentifiers'] = $book_data['industryIdentifiers'];
            $existing_book['isbn13'] = $book_data['isbn13'];
            $existing_book['isbn10'] = $book_data['isbn10'];
        }
        $book_exists = true;
        break;
    }
}

// 新しい本の場合は追加
if (!$book_exists) {
    $books[] = $book_data;
}

// JSONファイルに保存
if (file_put_contents($json_file, json_encode($books, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode([
        'success' => true,
        'message' => 'Book saved successfully',
        'book' => $book_data
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save book data']);
}
?>
