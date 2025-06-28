<?php
//エラー表示
ini_set("display_errors", 1);

//1. POSTデータ取得
$name = $_POST["name"];
$url = $_POST["url"];
$comment = $_POST["comment"];


//2. DB接続します
$db_name = ''; // データベース名
$db_host = ''; // DBホスト名
$db_id = ''; // DBユーザー名
$db_pw = ''; // DBパスワード
$charset = ''; // 文字コード

try {
  //Password:MAMP='root',XAMPP=''
  $pdo = new PDO("mysql:dbname=$db_name;charset=$charset;host=$db_host",$db_id,$db_pw);
} catch (PDOException $e) {
  exit('DB_Connection:'.$e->getMessage());
}


//３．データ登録SQL作成
$sql = "INSERT INTO gs_bookmark_table(input_date, title, URL, comment)VALUES (sysdate(), :name, :url, :comment)";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':url', $url, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();

//４．データ登録処理後
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("SQL_error:".$error[2]);
}else{
  //５．select_comp.phpへリダイレクト
  header("Location: select_comp.php");
  exit;
}
?>
