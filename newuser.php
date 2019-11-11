<?php
/***************** データベース情報等の読み込み *********/
require_once("C:\MAMP\htdocs\data\db_info.php");
/***************** データベースへ接続、データベースの選択 *********/
$s = new pdo("mysql:host=$SERV;dbname=$DBNM",$USER,$PASS);


$u_name=isset($_GET["u_name"])? htmlspecialchars($_GET["u_name"]):null;
$u_password=isset($_GET["u_password"])? htmlspecialchars($_GET["u_password"]):null;
if($u_name<>""){
	$s->query("insert into login(username,password) values('$u_name','$u_password')");
  print "ユーザーを作成しました";
  sleep(2);
  header("location: index.php");
}

print <<<eot1
  <!DOCTYPE html>
  <html>
  <head>
  <meta charset="utf-8">
  <title>新規登録ページ</title>
  </head>
  <body style="background-color:white" class="text-center">
  <div style="font-size:20pt">(新規ユーザー登録)</div>
  新しくユーザーを投稿するときは、ここから
  <br>
  <form method="GET" action="newuser.php">
  新しく登録するユーザーの名前
  <input type="text" name="u_name" size="50">
  <input type="text" name="u_password" size="50">
  <div><input type="submit" value="作成"></div>
  </form>
  <hr>
  </body>
  </html>
eot1;
?>
