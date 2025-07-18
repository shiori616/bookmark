<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ブクログ初級</title>
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
                <a href="tsundoku.php" class="hover:text-blue-200 transition-colors font-medium">積読</a>
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
                <a href="tsundoku.php" class="hover:text-blue-200 transition-colors font-medium py-2">積読</a>
            </div>
        </nav>
    </header>

    <!-- 検索フォーム -->
    <div class="container mx-auto p-4">
        <form id="search-form" class="mb-4 flex flex-col md:flex-row items-center justify-center">
            <input 
                type="text" 
                name="keyword" 
                placeholder="検索キーワードを入力" 
                id="keyword" 
                class="shadow appearance-none border rounded w-4/5 md:w-auto py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2 md:mb-0 md:mr-2"
            >
            <button 
                type="button" 
                id="search-button" 
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-4/5 md:w-auto"
            >
                検索
            </button>
        </form>
    </div>

    <!-- 検索結果 -->
    <div class="result">

    </div>

    <!-- jQueryライブラリ -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- JavaScriptファイル -->
    <script src="js/script.js"></script>
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
