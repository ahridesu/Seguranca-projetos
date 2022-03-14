<?php
session_start();
include('db_config.php');

if(empty($_POST['email']) || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['conf_password'])){
	header('Location: register.php');
	exit();
}

$email = trim($_POST['email']);
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$confpass = trim($_POST['conf_password']);

$check = "SELECT COUNT(*) AS total FROM Client WHERE username = '$username'";
$result = mysqli_query($connection, $check);
$row = mysqli_fetch_assoc($result);

if($row['total'] == 1){
    $_SESSION['invalid_user'] = true;
    header('Location: register.php');
    exit;
}

if(strcmp($password, $confpass) != 0){
    $_SESSION['nomatch_password'] = true;
    header('Location: register.php');
    exit;
}

$query = "INSERT INTO Client(username, password, email) VALUES ('$username', '$password', '$email')";
$result = mysqli_query($connection, $query);
$_SESSION['user_created'] = true;

header('Location: register.php');
exit;

?>