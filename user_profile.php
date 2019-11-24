<?php
/*********************データベース情報等の読み込み **********/
include('session.php');
require_once("C:\MAMP\htdocs\data\db_info.php");

/********************* データベースへ接続、データベース選択 **********/
$s = new pdo("mysql:host=$SERV;dbname=$DBNM",$USER,$PASS);

$receive_user = $_GET['profile'];

$receive_user_one=$s->query("select * from login WHERE username like '$receive_user'");
$receive_user_two=$receive_user_one->fetch();
//ログインしているユーザー情報の表示
print <<<eot1
<!DOCTYPE html>
<html>
  <head>
  <title>MY Page</title>
  </head>
  <body>
  <div id="mypage">
    <h1>ここはユーザープロフィールです。</h1>
    <b id="welcome">ユーザーID :  $receive_user_two[0] </b>
    <br>
    <b id="welcome">ユーザー名 :  $receive_user_two[1] </b>
    <br>
    <b id="welcome">パスワード :  $receive_user_two[2] </b>
    <br>
    <b id="welcome">ユーザータイプ :  $receive_user_two[3] </b>
    <br>
    <a href="mypage.php">マイページに戻る</a>
  </div>
  </body>
</html>
eot1;
?>
