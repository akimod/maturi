<?php

/*********************データベース情報等の読み込み **********/
require_once("C:\MAMP\htdocs\data\db_info.php");
include('session.php');


/********************* データベースへ接続、データベース選択 **********/
$s = new pdo("mysql:host=$SERV;dbname=$DBNM",$USER,$PASS);


/********************* スレッドグループ番号(gu)を取得し$gu_dに代入 **********/
$gu_d = $_GET["gu"];
$attend = isset($_GET["attend"])?htmlspecialchars($_GET["attend"]):null;


/*********************$gu_dに数字以外が含まれていたら処理を中止 **********/
if(preg_match("/[^0-9]/",$gu_d)){
print <<<eot1
	不正な値が入力されています<br>
	<a href="keizi_top.php">ここをクリックしてスレッド一覧に戻ってください</a>
eot1;

/*********************  $gu_dに数字以外が含まれていない正常な値での処理 **********/
}elseif(preg_match("/[0-9]/",$gu_d)){

/*********************  名前とメッセージを習得してタグを削除 **********/
$na_d = isset($_GET["na"])?htmlspecialchars($_GET["na"]):null;
$me_d = isset($_GET["me"])?htmlspecialchars($_GET["me"]):null;

/*********************  IPアドレスを取得 **********/
$ip = getenv("REMOTE_ADDR");

/*********************  スレッドグループ番号(gu)に一致するレコードを表示 **********/
$re=$s->query("select * from maturi_info where guru = $gu_d");
$kekka = $re->fetch();

/*********************  スレッド内容の表示文字列$sure_comを作成 **********/
$sure_com = "「".$gu_d." ".$kekka[1]."」";
//祭りの名前の代入
$maturi_title = $kekka[1];
//祭りの説明を代入
$maturi_description = $kekka[4];
$check_user_name = $kekka[5];//画像表示用
/*********************  スレッド表示のタイトル等書き出し **********/
print <<<eot2
	<!DOCTYPE html>
	<html>
	<head>
	<meta charset = "utf-8">
	<title>祭りの情報 $sure_com の情報</title>
	</head>
	<body style="background-color:silver">
	<div style="color:purple;font-size:35pt">
	$sure_com の祭りの情報です!
	</div>
	<h1>祭りのタイトル<br>$maturi_title</h1>
	<hr>
	<br>
	<h2>この祭りの説明<br>$maturi_description</h2>
	<hr>
	<br>

eot2;
//
//祭り説明世の画像もしくは動画を表示する
//DBから取得して表示する．
print 'この祭りのデータは';
print $check_user_name;
print 'が作った';
print '<hr>';


try{
$sql = "SELECT * FROM maturi_media where matu_name like '$maturi_title'";
$stmt = $s->prepare($sql);
$stmt -> execute();
while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
		echo ($row["id"]."<br/>");
		//動画と画像で場合分け
		$target = $row["fname"];
		if($row["extension"] == "mp4"){
				echo ("<video src=\"import_media.php?target=$target\" width=\"426\" height=\"240\" controls></video>");
		}
		elseif($row["extension"] == "jpeg" || $row["extension"] == "png" || $row["extension"] == "gif"){
				echo ("<img src='import_media.php?target=$target' width=\"426\" height=\"240\">");
		}
		echo ("<br/><br/>");
}
}catch(PDOException $e){
	die($e->getMessage());
}

print '<hr>';


/*********************  名前($na_d)が入力されていればtbj1にレコードを挿入 **********/
if($na_d<>""){
	$s->query("insert into tbj1 values (0,'$na_d','$me_d',now(),'$gu_d','$ip')");
}

/*********************  (attend)が入力されていればmaturi_attendにレコードを挿入 **********/
if($attend<>""){
	try{
		$s->query("insert into maturi_attend values(0,'$login_session','$maturi_title')");
		print '祭りの参加登録が完了しました';
	}catch(PDOException $e){
		die($e->getMessage());
	}
}
/*********************  水平線表示 **********/
print	"<h2>$sure_com のメッセージ</h2>";
print "<hr>";

/*********************  日時の順にレスデータを表示 **********/
$re=$s->query("select * from tbj1 where guru=$gu_d order by niti");

$i = 1;
while($kekka=$re->fetch()){
	print "$i($kekka[0]:$kekka[1]:$kekka[3])<br>";
	print nl2br($kekka[2]);
	print "<br><br>";
}

print <<<eot3
	<hr>
	<div style="font-size:18pt">
	$sure_com にメッセージを書く時にはここにどうぞ
	</div>
	<form method="GET" action="keizi.php">
	<div>名前<input type = "text" name = "na"></div>
	メッセージ
	<div>
	<textarea name="me" rows = "10" cols="70"></textarea>
	</div>
	<input type="hidden" name="gu" value="$gu_d">
	<input type ="submit" value="送信">
	</form>
	<hr>
	<a href="maturi_after_login.php">スレッド一覧に戻る</a>
	<br>
eot3;

/*********************  $gu_dに数字以外も、数字も含まれていないときの処理 **********/
}else{
	print "スレッドを選択してください。<br>";
	print "<a href='maturi_after_login.php'>ここをクリックしてスレッド一覧に戻ってください</a>";
}

$check_attend_one=$s->query("select * from maturi_attend WHERE attend_user_name like '$login_session' and attend_matu_name like '$maturi_title'");
$check_attend_two=$check_attend_one->fetch();
if($login_type == "participant" ){
if($check_attend_two[0]<>""){
	print "この祭りには参加登録済みです";
}else{
print <<<eot4
		<form method="GET" action="keizi.php">
		<input type="hidden" name="gu" value="$gu_d">
		<input type="hidden" name="attend" value="attend">
		<input type="submit" value="参加する">
		</form>
eot4;
}
}

print <<<eot5
	</body>
	</html>
eot5;
?>
