<?php
/*********************  データベース情報の読み込み **********/
include('session.php');
require_once("C:\MAMP\htdocs\data\db_info.php");

/*********************  データべースへ接続、データベース選択 **********/
$s = new pdo("mysql:host=$SERV;dbname=$DBNM",$USER,$PASS);

$recive_matu_name=$_GET["maturi_update"];

print "祭り情報編集ページ";
print "<br>";
try{
if(isset($_GET["active"]) ) {
  $update_matu_name = $_GET["update_matu_name"];
  $update_matu_description = $_GET["update_matu_description"];
  $s->query("update maturi_info SET description = '$update_matu_description' where sure like '$recive_matu_name' ");
  $s->query("update maturi_info SET sure = '$update_matu_name' where sure like '$recive_matu_name' ");
  $recive_matu_name=$update_matu_name;
  print "<br>更新が完了しました";
}
}catch(PDOException $e){
	die($e->getMessage());
}
print "<br>";
$re=$s->query("select * from maturi_info where sure LIKE '$recive_matu_name'");
$kekka=$re->fetch();
print "id";
print ":";
print $kekka[0];
print "<br>";
print "祭り名";
print ":";
print $kekka[1];
print "<br>";
print "作成日";
print ":";
print $kekka[2];
print "<br>";
print "祭りの説明";
print ":";
print $kekka[4];
print "<br>";
print "作成者";
print ":";
print $kekka[5];
print "<br>";

print <<<eot1
<form action="maturi_update.php" method="GET">
  <label>祭り名</label>
  <input type="text" name="update_matu_name" value='$kekka[1]'><br>
  <label>祭りの説明</label>
  <input type="text" name="update_matu_description" value='$kekka[4]'><br>
  <input class="button" type="submit" name="active" value="ユーザー情報を編集する" />
  <input type='hidden' name='maturi_update' value='$recive_matu_name'>
</form>
eot1;

print "<br>";
print "<a href='mypage.php'>スレッド一覧に戻る</a>";
?>
