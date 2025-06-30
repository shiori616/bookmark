<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>積読 - ブクログ初級</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- ヘッダー部分 -->
    <header class="bg-blue-600 text-white p-4 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">ブクログ</h1>
            <!-- メニューバー -->
            <nav class="hidden md:flex space-x-6">
                <a href="index.php" class="hover:text-blue-200 transition-colors font-medium">検索</a>
                <a href="read.php" class="hover:text-blue-200 transition-colors font-medium">読了済み</a>
                <a href="tsundoku.php" class="text-blue-200 font-medium border-b-2 border-blue-200">積読</a>
            </nav>
            <!-- モバイルメニューボタン -->
            <button id="mobile-menu-btn" class="md:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
        <!-- モバイルメニュー -->
        <nav id="mobile-menu" class="md:hidden mt-4 hidden">
            <div class="flex flex-col space-y-2">
                <a href="index.php" class="hover:text-blue-200 transition-colors font-medium py-2">検索</a>
                <a href="read.php" class="hover:text-blue-200 transition-colors font-medium py-2">読了済み</a>
                <a href="tsundoku.php" class="text-blue-200 font-medium py-2">積読</a>
            </div>
        </nav>
    </header>

    <!-- ページタイトル -->
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">積読の本</h2>
    </div>

    <!-- 積読書籍一覧 -->
    <div class="result">
        <div class="text-center p-4">
            <p class="text-gray-600">積読の本を読み込み中...</p>
        </div>
    </div>

    <!-- jQueryライブラリ -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- JavaScriptファイル -->
    <script src="js/tsundoku.js"></script>
    <script>
        // モバイルメニューの切り替え
        $(document).ready(function() {
            $('#mobile-menu-btn').click(function() {
                $('#mobile-menu').toggleClass('hidden');
            });
        });
    </script>

</body>
</html>
