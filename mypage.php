<?php
/*********************データベース情報等の読み込み **********/
include('session.php');
require_once("C:\MAMP\htdocs\data\db_info.php");

/********************* データベースへ接続、データベース選択 **********/
$s = new pdo("mysql:host=$SERV;dbname=$DBNM",$USER,$PASS);


if(isset($_GET['delete'])) {
    echo "削除ボタンが押下されました";
    print "SQLカフェのテーブルを初期化しました";
  $delete = $_GET['delete'];
	$s->query("DELETE FROM maturi_info where sure like '$delete'");
	$s->query("DELETE FROM maturi_attend where attend_matu_name like '$delete'");
  $s->query("DELETE FROM maturi_media where matu_name like '$delete'");
}
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
    <a href="user_update.php">ユーザー情報を編集する</a>
eot1;


print <<<eot2
    <div>参加している祭りがある場合</div>
eot2;
$check_attend_one=$s->query("select * from maturi_attend WHERE attend_user_name like '$login_session'");
while($check_attend_two=$check_attend_one->fetch()){
  print "$check_attend_two[2]";
  print "<br>";

}
print "<hr>";
$check_organize_one=$s->query("select * from maturi_info WHERE maturi_user_name like '$login_session'");
while($check_organize_two=$check_organize_one->fetch()){
  print "<br>";
  print "自分が主催している祭りの情報";
  print "<br>";
  print "$check_organize_two[0]";
  print "<br>";
  print "$check_organize_two[1]";
  print "<br>";
  print "$check_organize_two[2]";
  print "<br>";
  print "$check_organize_two[3]";
  print "<br>";
  print "$check_organize_two[4]";
  print "<br>";
  print "$check_organize_two[5]";
  print "<br>";
  print "<hr>";
  $check_count_one=$s->query("select count(attend_matu_name) from maturi_attend where attend_matu_name like '$check_organize_two[1]'");
  $check_count_two=$check_count_one->fetch();
  print "この祭りに参加している人の数は";
  print "$check_count_two[0]";
  print "人です。";
  print "<br>";
  $check_human_one=$s->query("select attend_user_name from maturi_attend where attend_matu_name like '$check_organize_two[1]'");
  while($check_human_two=$check_human_one->fetch()){
    print "この祭りに参加している人は";
    print "$check_human_two[0]";
    print "さんです";
    print "<br>";
  }
  print "<hr>";
print <<<eot2_1
  <form action="mypage.php" method="GET">
    <input class="button" type="submit" name="active" value="この投稿を削除する" />
    <input type='hidden' name='delete' value='$check_organize_two[1]'>
  </form>
  <hr>
eot2_1;
}

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
