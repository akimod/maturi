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
print <<<eot0
<h2 id="mypage"><a href="mypage.php">My Page</a></h2>
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

$su_d=isset($_POST["su"])? htmlspecialchars($_POST["su"]):null;
$description_d=isset($_POST["description"])? htmlspecialchars($_POST["description"]):null;

try{
  $s->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	if($su_d<>""){
		$s->query("insert into maturi_info(sure,niti,aipi,description,maturi_user_name) values('$su_d',now(),'$ip','$description_d','$login_session')");
	}
}catch(PDOException $e){
  die($e->getMessage());
}
/*******************スレッド表示用***************/
print <<<eot1_2
<div style="font-size:20pt">(祭り一覧)</div>
eot1_2;

$re=$s->query("select * from maturi_info");
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
	<div><input type="submit" value="作成"></div>
	</form>
	<hr>
	<span style="font-size:20pt">(祭りの検索)</span>
	<a href="keizi_search.php">検索するときはここをクリック</a>
	<hr>
	<span style="font-size:20pt">(祭りの投稿を削除)</span>
	<a href="keizi_syokika.php">祭りの投稿を削除するにはここをクリック</a>
	<hr>
	<span style="font-size:20pt">(祭りの画像、動画を投稿したい方　*現在は使用停止中)</span>
	<a href="media_index.php">ここをクリック</a>
	<hr>

	</body>

	</html>

eot3;

?>
