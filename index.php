<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bookmark</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>div{padding: 10px;font-size:16px;}</style>
</head>
<body>
<!-- Head[Start] -->
<header>

</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<form method="POST" action="insert.php">
    <div class="jumbotron">
        <fieldset>
            <legend>ブックマーク</legend>
             <label>タイトル<input type="text" name="name"></label><br>
             <label>URL<input type="text" name="url"></label><br>
             <label><textArea name="comment" rows="4" cols="40"></textArea></label><br>
             <input type="submit" value="送信">
        </fieldset>
    </div>
</form>
<!-- Main[End] -->

    
</body>
</html>