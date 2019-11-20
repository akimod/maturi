<?php
/***************** データベース情報等の読み込み *********/
require_once("C:\MAMP\htdocs\data\db_info.php");
/***************** データベースへ接続、データベースの選択 *********/
$s = new pdo("mysql:host=$SERV;dbname=$DBNM",$USER,$PASS);


$u_name=isset($_POST["u_name"])? htmlspecialchars($_POST["u_name"]):null;
$u_password=isset($_POST["u_password"])? htmlspecialchars($_POST["u_password"]):null;
$u_type=isset($_POST["u_type"])? htmlspecialchars($_POST["u_type"]):null;
if($u_name<>"" && $u_password<>"" && $u_type<>""){
	$s->query("insert into login(username,password,user_type) values('$u_name','$u_password','$u_type')");
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
  <form method="POST" action="newuser.php">
	<label>UserName :</label>
  <input type="text" name="u_name" size="50">
	<br>
	<label>Password :</label>
  <input type="text" name="u_password" size="50" >
	<br>
	<label>user_type :</label>
	<select name="u_type">
	<option value="organizer">主催者</option>
	<option value="participant">参加者</option>
	</select>
	<div><input type="submit" value="作成"></div>
  </form>
  <hr>
  </body>
  </html>
eot1;
?>
