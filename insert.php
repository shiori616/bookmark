<?php
//エラー表示
ini_set("display_errors", 1);

// 共通設定ファイルを読み込み
require_once 'config/database.php';

// JSONレスポンス用のヘッダー設定
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// POSTリクエストのみ受け付け
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// JSONデータを受け取る
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
    exit;
}

// データベース接続
$database = new Database();
$pdo = $database->getConnection();

// 必要なデータを取得
$industryIdentifiers = isset($input['industryIdentifiers']) ? $input['industryIdentifiers'] : [];
$clickDateTime = isset($input['clickDateTime']) ? $input['clickDateTime'] : date('Y-m-d H:i:s');
$buttonType = isset($input['buttonType']) ? $input['buttonType'] : '';

// その他のデータ
$title = isset($input['title']) ? $input['title'] : '';
$authors = isset($input['authors']) ? $input['authors'] : '';
$imageUrl = isset($input['imageUrl']) ? $input['imageUrl'] : '';
$description = isset($input['description']) ? $input['description'] : '';

// デバッグ用ログ
error_log("Received data:");
error_log("Industry Identifiers: " . json_encode($industryIdentifiers));
error_log("Click DateTime: " . $clickDateTime);
error_log("Button Type: " . $buttonType);

// ISBNを抽出
$isbn13 = '';
$isbn10 = '';
foreach ($industryIdentifiers as $identifier) {
    if (isset($identifier['type']) && isset($identifier['identifier'])) {
        if ($identifier['type'] === 'ISBN_13') {
            $isbn13 = $identifier['identifier'];
        } elseif ($identifier['type'] === 'ISBN_10') {
            $isbn10 = $identifier['identifier'];
        }
    }
}

try {
    // テーブルが存在しない場合は作成
    $createTable = "
        CREATE TABLE IF NOT EXISTS book_clicks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title TEXT,
            authors TEXT,
            image_url TEXT,
            description TEXT,
            industry_identifiers JSON,
            isbn13 VARCHAR(20),
            isbn10 VARCHAR(15),
            button_type VARCHAR(20),
            click_datetime DATETIME,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ";
    $pdo->exec($createTable);

    // データを挿入
    $stmt = $pdo->prepare("
        INSERT INTO book_clicks 
        (title, authors, image_url, description, industry_identifiers, isbn13, isbn10, button_type, click_datetime) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $result = $stmt->execute([
        $title,
        $authors,
        $imageUrl,
        $description,
        json_encode($industryIdentifiers, JSON_UNESCAPED_UNICODE),
        $isbn13,
        $isbn10,
        $buttonType,
        date('Y-m-d H:i:s', strtotime($clickDateTime))
    ]);

    if ($result) {
        $insertId = $pdo->lastInsertId();
        
        echo json_encode([
            'success' => true,
            'message' => 'Book data saved successfully',
            'insert_id' => $insertId,
            'data' => [
                'title' => $title,
                'authors' => $authors,
                'industryIdentifiers' => $industryIdentifiers,
                'clickDateTime' => $clickDateTime,
                'buttonType' => $buttonType,
                'isbn13' => $isbn13,
                'isbn10' => $isbn10
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to save data']);
    }

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    error_log("General error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Error: ' . $e->getMessage()]);
}
?>
