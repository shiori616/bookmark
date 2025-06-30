<?php
// 共通設定ファイルを読み込み
require_once 'config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// データベース接続
$database = new Database();
$pdo = $database->getConnection();

// ステータスでフィルタリング（read または tsundoku）
$status_filter = isset($_GET['status']) ? $_GET['status'] : null;

try {
    if ($status_filter && in_array($status_filter, ['read', 'tsundoku'])) {
        // 特定のステータスの書籍を取得
        $stmt = $pdo->prepare("SELECT * FROM book_clicks WHERE button_type = ? ORDER BY created_at DESC");
        $stmt->execute([$status_filter]);
    } else {
        // すべての書籍を取得
        $stmt = $pdo->prepare("SELECT * FROM book_clicks ORDER BY created_at DESC");
        $stmt->execute();
    }
    
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // industry_identifiersをJSONデコード
    foreach ($books as &$book) {
        if (isset($book['industry_identifiers'])) {
            $book['industry_identifiers'] = json_decode($book['industry_identifiers'], true);
        }
    }
    
    echo json_encode([
        'success' => true,
        'books' => $books,
        'count' => count($books)
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
