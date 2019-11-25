<?php

include('session.php');

/***************** データベース情報等の読み込み *********/

require_once("C:\MAMP\htdocs\data\db_info.php");

/***************** データベースへ接続、データベースの選択 *********/

$s = new pdo("mysql:host=$SERV;dbname=$DBNM",$USER,$PASS);

/*****************  タイトル,画像等の表示 *********/

//ファイルアップロード用
try{
    //ファイルアップロードがあったとき
    if (isset($_FILES['upfile']['error']) && is_int($_FILES['upfile']['error']) && $_FILES["upfile"]["name"] !== ""){
        //エラーチェック
        switch ($_FILES['upfile']['error']) {
            case UPLOAD_ERR_OK: // OK
                break;
            case UPLOAD_ERR_NO_FILE:   // 未選択
                throw new RuntimeException('ファイルが選択されていません', 400);
            case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
                throw new RuntimeException('ファイルサイズが大きすぎます', 400);
            default:
                throw new RuntimeException('その他のエラーが発生しました', 500);
        }

        //画像・動画をバイナリデータにする．
        $raw_data = file_get_contents($_FILES['upfile']['tmp_name']);

        //拡張子を見る
        $tmp = pathinfo($_FILES["upfile"]["name"]);
        $extension = $tmp["extension"];
        if($extension === "jpg" || $extension === "jpeg" || $extension === "JPG" || $extension === "JPEG"){
            $extension = "jpeg";
        }
        elseif($extension === "png" || $extension === "PNG"){
            $extension = "png";
        }
        elseif($extension === "gif" || $extension === "GIF"){
            $extension = "gif";
        }
        elseif($extension === "mp4" || $extension === "MP4"){
            $extension = "mp4";
        }
        else{
            echo "非対応ファイルです．<br/>";
            echo ("<a href=\"media_index.php\">戻る</a><br/>");
            exit(1);
        }

        //DBに格納するファイルネーム設定
        //サーバー側の一時的なファイルネームと取得時刻を結合した文字列にsha256をかける．
        $date = getdate();
        $fname = $_FILES["upfile"]["tmp_name"].$date["year"].$date["mon"].$date["mday"].$date["hours"].$date["minutes"].$date["seconds"];
        $fname = hash("sha256", $fname);
        //祭りの名前
        //$matu_name=isset($_GET["su"])? htmlspecialchars($_GET["su"]):null;
        //画像・動画をDBに格納．
        $su_d=isset($_POST["su"])? htmlspecialchars($_POST["su"]):null;
        $sql = "INSERT INTO maturi_media(maturi_user_name,fname, extension, raw_data,matu_name) VALUES (:maturi_user_name,:fname, :extension, :raw_data,:matu_name);";
        $stmt = $s->prepare($sql);
        $stmt -> bindValue(":maturi_user_name",$login_session, PDO::PARAM_STR);
        $stmt -> bindValue(":fname",$fname, PDO::PARAM_STR);
        $stmt -> bindValue(":extension",$extension, PDO::PARAM_STR);
        $stmt -> bindValue(":raw_data",$raw_data, PDO::PARAM_STR);
        $stmt -> bindValue(":matu_name",$su_d, PDO::PARAM_STR);
        $stmt -> execute();

    }

}
catch(PDOException $e){
    echo("<p>500 Inertnal Server Error</p>");
    exit($e->getMessage());
}







/***   ユーザー別表記テスト用スレッド end **/
print <<<eot1
	<!DOCTYPE html>
	<html>
	<head>
	<meta charset="utf-8">
	<title>祭り参加者マッチングページ</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <link href="maturi.css" rel="stylesheet" type="text/css">
	</head>
  <header>
  <div class="top">
    <ul>
      <li><a href="logout.php">Log Out</a></li>
      <li><a href="mypage.php">My Page</a></li>
      <li><a href="maturi_after_login.php">メイン画面へ</a></li>
    </ul>
  </div>
  </header>
	<body style="background-color:white" class="text-center">
  <br>
  <h2>ようこそ $login_session さん</h2>
  <h2>ユーザータイプは $login_type です</h2>
eot1;



/***************** クライアントIPアドレス取得 *********/

$ip=getenv("REMOTE_ADDR");

/***************** スレッド名の変数$su_dにデータがあればtbj0に挿入 *********/

$su_d=isset($_POST["su"])? htmlspecialchars($_POST["su"]):null;
$description_d=isset($_POST["description"])? htmlspecialchars($_POST["description"]):null;
$end_d=isset($_POST["end_display"])? htmlspecialchars($_POST["end_display"]):null;

try{
  $s->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	if($su_d<>""){
		$s->query("insert into maturi_info(sure,niti,aipi,description,maturi_user_name,end_display) values('$su_d',now(),'$ip','$description_d','$login_session','$end_d')");
	}
}catch(PDOException $e){
  die($e->getMessage());
}
/*******************スレッド表示用***************/
$display_type = isset($_POST["display_type"])? htmlspecialchars($_POST["display_type"]):null;
print <<< eot1_2
<hr>
<h2>表示タイプ選択</h2>
<form action="maturi_after_login.php" method="post">
<p>
<input type="radio" name="display_type" value="past">過去
<input type="radio" name="display_type" value="now">現在
</p>
<p>
<input type="submit" value="変更する">
</p>
</form>
eot1_2;


if($display_type =="" || $display_type =="now"){
$re=$s->query("select * from maturi_info where end_display >= now()");
print <<<eot1_2_1
<div style="font-size:20pt">(現在募集している祭り一覧)</div>
eot1_2_1;
while($kekka=$re->fetch()){
print <<<eot2_3

<a href="keizi.php?gu=$kekka[0]">$kekka[0] $kekka[1]</a>
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
if($login_type == "organizer"){
print <<<eot3
	<hr>
	<div style="font-size:20pt">(新規祭り投稿)</div>
	新しく祭りを投稿するときは、ここから
	<br>
	<form method="POST" enctype="multipart/form-data" action="maturi_after_login.php">
	新しく投稿する祭りのタイトル
	<input type="text" name="su" size="50">
  <br>
	<textarea name="description" rows = "10" cols="70"></textarea>
  <br>
    <label>画像/動画アップロード</label>
    <input type="file" name="upfile">
    <br>
    ※画像はjpeg方式，png方式，gif方式に対応しています．動画はmp4方式のみ対応しています．<br>
    <label>掲載終了日時を選択</label>
    <input type="datetime-local" name="end_display">
	<div><input type="submit" value="作成"></div>
	</form>
eot3;
}
print <<< eot4
	<hr>
	<span style="font-size:20pt">(祭りの検索)</span>
	<a href="keizi_search.php">検索するときはここをクリック</a>
	<hr>
	<span style="font-size:20pt">(祭りの画像、動画を投稿したい方　*現在は使用停止中)</span>
	<a href="media_index.php">ここをクリック</a>
	<hr>
	</body>
	</html>

eot4;

?>
