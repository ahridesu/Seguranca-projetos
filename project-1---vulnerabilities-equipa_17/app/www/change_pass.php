<?php
session_start();
include('db_config.php');

if(empty($_POST['newpassword']) || empty($_POST['conf_newpassword'])){
	header('Location: pass_page.php');
	exit();
}

$password = trim($_POST['newpassword']);
$confpass = trim($_POST['conf_newpassword']);
$username = $_SESSION['username'];

if(strcmp($password, $confpass) != 0){
    $_SESSION['nomatch_password'] = true;
    header('Location: pass_page.php');
    exit;
}else{
    $query = "UPDATE Client SET password='$password' WHERE username='$username'";
    $result = mysqli_query($connection, $query);
    $_SESSION['password_changed'] = true;
    header('Location: pass_page.php');
    exit;
}



?>

