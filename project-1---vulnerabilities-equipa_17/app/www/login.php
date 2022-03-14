<?php
session_start();
include('db_config.php');

if(empty($_POST['username']) || empty($_POST['password'])){
	header('Location: index.php');
	exit();
}

$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT user_id, username FROM Client WHERE username='{$username}' and password='{$password}'";

$result= mysqli_query($connection, $query);

$row = mysqli_num_rows($result);

if($row == 1){
	$_SESSION['username'] = $username;
	header('Location: main.php');
	exit();
}else{
	$_SESSION['not_auth'] = true;
	header('Location: index.php');
	exit();
}

?>

