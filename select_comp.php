<?php
//2. DB接続します

$db_name = 'gsky_bookmark'; // データベース名
$db_host = 'mysql3108.db.sakura.ne.jp'; // DBホスト名
$db_id = 'gsky_bookmark'; // DBユーザー名
$db_pw = 'kramkoob_8260'; // DBパスワード
$charset = 'utf8mb4'; // 文字コード

try {
  //Password:MAMP='root',XAMPP=''
  $pdo = new PDO("mysql:dbname=$db_name;charset=$charset;host=$db_host",$db_id,$db_pw);
} catch (PDOException $e) {
  exit('DBConnection Error:'.$e->getMessage());
}

//２．データ登録SQL作成
$sql = "SELECT * FROM gs_bookmark_table";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

//３．データ表示
$values = "";
if($status==false) {
  $error = $stmt->errorInfo();
  exit("SQLError:".$error[2]);
}

//全データ取得
$values =  $stmt->fetchAll(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC[カラム名のみで取得できるモード]
$json = json_encode($values,JSON_UNESCAPED_UNICODE);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ブックマーク表示</title>
<link rel="stylesheet" href="css/range.css">
<link href="css/bootstrap.min.css" rel="stylesheet">
<style>div{padding: 10px;font-size:16px;}</style>
</head>
<body id="main">
<!-- Head[Start] -->
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
      <a class="navbar-brand" href="index.php">データ登録</a>
      </div>
    </div>
  </nav>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<div>
    <div class="container jumbotron">


<table>
<?php foreach($values as $v){ ?>
  <tr>
    <td><?=$v["title"]?></td>
    <td><?=$v["URL"]?></td>
    <td><?=$v["comment"]?></td>
  </tr>
<?php } ?>
</table>


  </div>
</div>
<!-- Main[End] -->
<script>
  const a = '<?php echo $json; ?>';
  console.log(JSON.parse(a));
</script>
</body>
</html>