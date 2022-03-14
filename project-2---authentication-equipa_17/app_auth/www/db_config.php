<?php
define('HOST', 'db');
define('USER', 'root');
define('PASSWORD', 'root');
define('DB', 'bookdb');

$connection = mysqli_connect(HOST, USER, PASSWORD, DB) or die ('Error: Couldnt connect');

?>
