<?php
    if(isset($_GET["target"]) && $_GET["target"] !== ""){
        $target = $_GET["target"];
    }
    else{
        header("Location: media_index.php");
    }
    $MIMETypes = array(
        'png' => 'image/png',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'mp4' => 'video/mp4'
    );
    try {
      /*$user = "root";
      $pass = "";
      $pdo = new PDO("mysql:host=127.0.0.1;dbname=mediatest;charset=utf8", $user, $pass);*/
      /***************** データベース情報等の読み込み *********/
      require_once("C:\MAMP\htdocs\data\db_info.php");
      /***************** データベースへ接続、データベースの選択 *********/
      $pdo = new pdo("mysql:host=$SERV;dbname=$DBNM;charset=utf8",$USER,$PASS);
      $sql = "SELECT * FROM maturi_media WHERE fname = :target;";
      $stmt = $pdo->prepare($sql);
      $stmt -> bindValue(":target", $target, PDO::PARAM_STR);
      $stmt -> execute();
      $row = $stmt -> fetch(PDO::FETCH_ASSOC);
      header("Content-Type: ".$MIMETypes[$row["extension"]]);
      echo ($row["raw_data"]);
    }
    catch (PDOException $e) {
        echo("<p>500 Inertnal Server Error</p>");
        exit($e->getMessage());
    }
?>
