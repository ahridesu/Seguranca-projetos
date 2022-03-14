<?php
session_start();
include('db_config.php');

if(empty($_POST['email']) || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['conf_password'])){
	header('Location: register.php');
	exit();
}

$email = mysqli_real_escape_string($connection,trim($_POST['email']));
$username = mysqli_real_escape_string($connection,trim($_POST['username']));
$password = mysqli_real_escape_string($connection,trim($_POST['password']));
$confpass = mysqli_real_escape_string($connection,trim($_POST['conf_password']));

$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number    = preg_match('@[0-9]@', $password);
$specialChars = preg_match('@[^\w]@', $password);

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

if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
    $_SESSION['password_weak'] = true;
    header('Location: register.php');
    exit;
}

$query = "INSERT INTO Client(username, password, email) VALUES ('$username', md5('$password'), '$email')";
$result = mysqli_query($connection, $query);
$_SESSION['user_created'] = true;

header('Location: register.php');
exit;

?>