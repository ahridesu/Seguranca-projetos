<?php
session_start();
include('db_config.php');

if(empty($_POST['newpassword']) || empty($_POST['conf_newpassword']) || empty($_POST['oldpassword'])){
	header('Location: pass_page.php');
	exit();
}

$oldpassword = mysqli_real_escape_string($connection,trim($_POST['oldpassword']));
$newpassword = mysqli_real_escape_string($connection,trim($_POST['newpassword']));
$confpass = mysqli_real_escape_string($connection,trim($_POST['conf_newpassword']));
$username = $_SESSION['username'];

$uppercase = preg_match('@[A-Z]@', $newpassword);
$lowercase = preg_match('@[a-z]@', $newpassword);
$number    = preg_match('@[0-9]@', $newpassword);
$specialChars = preg_match('@[^\w]@', $newpassword);

if(strcmp($newpassword, $confpass) != 0){
    $_SESSION['nomatch_password'] = true;
    header('Location: pass_page.php');
    exit;
}

if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($newpassword) < 8) {
    $_SESSION['password_weak'] = true;
    header('Location: pass_page.php');
    exit;
}

$query = "SELECT password FROM Client WHERE username='$username'";
$pass = mysqli_query($connection, $query);
$row = $pass->fetch_assoc();

if(strcmp($oldpassword, $row['password']) == 0){
    $query = "UPDATE Client SET password='$newpassword' WHERE username='$username'";
    $result = mysqli_query($connection, $query);
    $_SESSION['password_changed'] = true;
    header('Location: pass_page.php');
    exit;
}

$_SESSION['wrong_oldpass'] = true;
header('Location: pass_page.php');
exit;


?>

