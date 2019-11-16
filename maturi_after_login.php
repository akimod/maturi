<?php

include('session.php');

/***************** データベース情報等の読み込み *********/

require_once("C:\MAMP\htdocs\data\db_info.php");

/***************** データベースへ接続、データベースの選択 *********/

$s = new pdo("mysql:host=$SERV;dbname=$DBNM",$USER,$PASS);

/*****************  タイトル,画像等の表示 *********/

print <<<eot0

<h2 id="logout"><a href="logout.php">Log Out</a></h2>

eot0;

/**    ユーザー別表記テスト用スレッド**/

if($login_session=="sample"){

print <<< sample

<h2>ようこそ $login_session さん</h2>

sample;

}

elseif($login_session=="test"){

print <<< test

<h2>ようこそ $login_session さん</h2>

test;

}

/***   ユーザー別表記テスト用スレッド end **/
/***コメントだよどうしようかな/
print <<<eot1

	<!DOCTYPE html>

	<html>

	<head>

	<meta charset="utf-8">

	<title>祭り参加者マッチングページ</title>

	</head>

	<body style="background-color:white" class="text-center">

	<div class="keizi_top_title narrow" style="font-size:35pt;background-color:#c1d114">

	祭りマッチングページトップ

	</div>

	<h2>見たい祭りの番号をクリックしてください</h2>

	<hr>

eot1;



/***************** クライアントIPアドレス取得 *********/

$ip=getenv("REMOTE_ADDR");

/***************** スレッド名の変数$su_dにデータがあればtbj0に挿入 *********/

$su_d=isset($_GET["su"])? htmlspecialchars($_GET["su"]):null;

if($su_d<>""){

	$s->query("insert into tbj0(sure,niti,aipi) values('$su_d',now(),'$ip')");

}

/*******************スレッド表示用***************/
print <<<eot1_2
<div style="font-size:20pt">(祭り一覧)</div>
eot1_2;

$re=$s->query("select * from tbj0");
while($kekka=$re->fetch()){
print <<<eot2_3
<a href="keizi.php?gu=$kekka[0]">$kekka[0] $kekka[1]</a>
<br>
$kekka[2]作成
<br>
<form method="GET" action="hensyu.php">
　祭り
<input type='submit' name='hensyu' value='$kekka[0]'>
の名前を編集
</form>
eot2_3;
}











/***************** スレッド作成用フォーム、検索フォームへのリンク *********/

print <<<eot3

	<hr>

	<div style="font-size:20pt">(新規祭り投稿)</div>

	新しく祭りを投稿するときは、ここから

	<br>

	<form method="GET" action="maturi_after_login.php">

	新しく投稿する祭りのタイトル

	<input type="text" name="su" size="50">

	<div><input type="submit" value="作成"></div>

	</form>

	<hr>

	<span style="font-size:20pt">(祭りの検索)</span>

	<a href="keizi_search.php">検索するときはここをクリック</a>

	<hr>

	<span style="font-size:20pt">(祭りの投稿を削除)</span>

	<a href="keizi_syokika.php">祭りの投稿を削除するにはここをクリック</a>

	<hr>
	<span style="font-size:20pt">(祭りの画像、動画を投稿したい方)</span>
	<a href="media_index.php">ここをクリック</a>
	<hr>

	</body>

	</html>

eot3;

?>
