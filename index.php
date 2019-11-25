<?php
include('login.php'); // Includes Login Script



if(isset($_SESSION['login_user'])){
header("location: profile.php");
}
print <<<eot1
  <!DOCTYPE html>
  <html>
  <head>
  <title>Login Form in PHP with Session</title>
  <link href="style.css" rel="stylesheet" type="text/css">
  </head>

  <body>
    <div id="main">
      <h1>ログイン画面</h1>
      <div id="login">
        <h2>Login Form</h2>
        <form action="" method="post">
        <label>UserName :</label>
        <input id="name" name="username" placeholder="username" type="text">
        <label>Password :</label>
        <input id="password" name="password" placeholder="**********" type="password">
        <input name="submit" type="submit" value=" Login ">
        <span><?php echo $error; ?></span>
        </form>
      </div>
    </div>
    <br>
    <div>
    <h3 id="maturi"><a href="maturi_top.php">トップページへ戻る</a></h3>
    </div>
eot1;


//新規登録用
print <<<eot2
    <div>
    <h3 id="maturi"><a href="newuser.php">新規会員登録はこちらから</a></h3>
    </div>
  </body>
  </html>
eot2;
?>
