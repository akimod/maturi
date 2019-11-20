<?php
/*********************データベース情報等の読み込み **********/
include('session.php');
require_once("C:\MAMP\htdocs\data\db_info.php");

/********************* データベースへ接続、データベース選択 **********/
$s = new pdo("mysql:host=$SERV;dbname=$DBNM",$USER,$PASS);

//ログインしているユーザー情報の表示
print <<<eot1
<!DOCTYPE html>
<html>
  <head>
  <title>MY Page</title>
  </head>
  <body>
  <div id="mypage">
    <h1>ここはマイページです。</h1>
    <b id="welcome">ユーザーID :  $login_id </b>
    <br>
    <b id="welcome">ユーザー名 :  $login_session </b>
    <br>
    <b id="welcome">パスワード :  $login_password </b>
    <br>

eot1;
$check_attend_one=$s->query("select * from maturi_attend WHERE attend_user_name like '$login_session'");
$check_attend_two=$check_attend_one->fetch();
print "$check_attend_two[2]";
print <<<eot2
    <div>参加している祭りがある場合</div>
eot2;

//マイページ以外のリンクなど
print <<<eot3
    <b id="logout"><a href="logout.php">Log Out</a></b>
    <hr>
    <b id="maturi"><a href="maturi_after_login.php">ログイン後トップ画面へ</a></b>
    </div>
  </body>
</html>
eot3;
?>
