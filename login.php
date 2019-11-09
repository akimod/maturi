<?php
session_start(); // Starting Session
$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
if (empty($_POST['username']) || empty($_POST['password'])) {
$error = "Username or Password is invalid";
}
else
{
// Define $username and $password
$username=$_POST['username'];
$password=$_POST['password'];
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
//$connection = mysql_connect("localhost", "root", 1234);
//$connection = new pdo("mysql:host=localhost;dbname=db1","root",1234);
$connection = mysqli_connect("localhost", "root", 1234, "db1");
// To protect MySQL injection for Security purpose
$username = stripslashes($username);
$password = stripslashes($password);
//$username = mysql_real_escape_string($username);
$username = mysqli_real_escape_string($connection,$username);
//$password = mysql_real_escape_string($password);
$password = mysqli_real_escape_string($connection,$password);
// Selecting Database
//$db = mysql_select_db("db1", $connection);
// SQL query to fetch information of registerd users and finds user match.
$query = mysqli_query($connection,"select * from login where password='$password' AND username='$username'");
$rows = mysqli_num_rows($query);
if ($rows == 1) {
$_SESSION['login_user']=$username; // Initializing Session
header("location: profile.php"); // Redirecting To Other Page
} else {
$error = "Username or Password is invalid";
}
mysqli_close($connection); // Closing Connection
}
}
?>
