<?php
include('session.php');


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
<b id="logout"><a href="logout.php">Log Out</a></b>
<hr>
<b id="maturi"><a href="maturi_after_login.php">ログイン後トップ画面へ</a></b>
</div>
</body>
</html>
eot1;
?>
