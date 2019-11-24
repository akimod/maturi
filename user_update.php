<?php
/*********************  データベース情報の読み込み **********/
include('session.php');
require_once("C:\MAMP\htdocs\data\db_info.php");

/*********************  データべースへ接続、データベース選択 **********/
$s = new pdo("mysql:host=$SERV;dbname=$DBNM",$USER,$PASS);

print "ユーザー情報編集ページ";
print "<br>";
try{
if(isset($_GET["active"]) ) {
  $update_user_name = $_GET["update_user_name"];
  $update_user_password = $_GET["update_user_password"];
  $s->query("update login SET password = '$update_user_password' where username like '$login_session' ");
  $s->query("update login SET username = '$update_user_name' where username like '$login_session' ");
  print "<br>更新が完了しました";
  mysqli_close($connection);
  session_destroy();
  header('Location: index.php'); // Redirecting To Home Page
}
}catch(PDOException $e){
	die($e->getMessage());
}
print "<br>";
$re=$s->query("select * from login where username LIKE '$login_session'");
$kekka=$re->fetch();
print "id";
print ":";
print $kekka[0];
print "<br>";
print "ユーザー名";
print ":";
print $kekka[1];
print "<br>";
print "パスワード";
print ":";
print $kekka[2];
print "<br>";
print "ユーザータイプ";
print ":";
print $kekka[3];
print "<br>";

print <<<eot1
<form action="user_update.php" method="GET">
  <label>ユーザー名</label>
  <input type="text" name="update_user_name" value='$kekka[1]'><br>
  <label>パスワード</label>
  <input type="text" name="update_user_password" value='$kekka[2]'><br>
  <input class="button" type="submit" name="active" value="ユーザー情報を編集する" />
</form>
eot1;

print "ユーザー情報変更後はユーザー画面から再度ログインしなおしてください";
print "<br>";
print "<a href='mypage.php'>スレッド一覧に戻る</a>";
?>
