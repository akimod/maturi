<?php

/***************** データベース情報等の読み込み *********/
require_once("C:\MAMP\htdocs\data\db_info.php");
/***************** データベースへ接続、データベースの選択 *********/
$s = new pdo("mysql:host=$SERV;dbname=$DBNM",$USER,$PASS);
/*****************  タイトル,画像等の表示 *********/
print <<<eot1
	<!DOCTYPE html>
	<html>
	<head>
	<meta charset="utf-8">
	<title>祭り参加者マッチングページ</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<link rel="stylesheet" href="maturi.css">
	</head>
	<body style="background-color:white" class="text-center">
	<div class="keizi_top_title narrow" style="font-size:35pt;background-color:#c1d114">
	祭りマッチングページトップ
	</div>
	<h2>見たい祭りの番号をクリックしてください</h2>
	<hr>
eot1;
print <<< eot1_2
<h2>表示タイプ選択</h2>
<form action="maturi_top.php" method="post">
<p>
<input type="radio" name="display_type" value="past">過去
<input type="radio" name="display_type" value="now">現在
</p>
<p>
<input type="submit" value="変更する">
</p>
</form>
eot1_2;
/***************** クライアントIPアドレス取得 *********/
$ip=getenv("REMOTE_ADDR");
/***************** スレッド名の変数$su_dにデータがあればtbj0に挿入 *********/
$su_d=isset($_GET["su"])? htmlspecialchars($_GET["su"]):null;
if($su_d<>""){
	$s->query("insert into tbj0(sure,niti,aipi) values('$su_d',now(),'$ip')");
}
/*******************スレッド表示用***************/
$display_type = isset($_POST["display_type"])? htmlspecialchars($_POST["display_type"]):null;
if($display_type =="" || $display_type =="now"){
$re=$s->query("select * from maturi_info where end_display >= now()");
print <<<eot1_2_1
<div style="font-size:20pt">(現在募集している祭り一覧)</div>
eot1_2_1;


while($kekka=$re->fetch()){
print <<<eot2_3

<a href="nouser_keizi.php?gu=$kekka[0]">$kekka[0] $kekka[1]</a>
<br>
$kekka[2]作成
<br>
eot2_3;
}
}elseif($display_type =="past"){
$re=$s->query("select * from maturi_info where end_display <= now()");
print <<<eot1_2_1
<div style="font-size:20pt">(過去に募集していた祭り一覧)</div>
eot1_2_1;
while($kekka=$re->fetch()){
print <<<eot2_4
<a href="keizi.php?gu=$kekka[0]">$kekka[0] $kekka[1]</a>
<br>
$kekka[2]作成
<br>
eot2_4;
}
}
/***************** スレッド作成用フォーム、検索フォームへのリンク *********/
print <<<eot3
	<hr>
	<span style="font-size:20pt">(祭りに参加したい方、祭りを主催する方)</span>
	<a href="index.php">ログインするときはここをクリック</a>
	<hr>
	</body>
	</html>
eot3;
?>
