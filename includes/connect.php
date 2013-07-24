<?php
$host = 'mysql.hostinger.com.ua';
$user = 'u798142730_root';
$password = 'danik0726just';
$db = 'u798142730_test';

$connect = mysqli_connect($host, $user, $password, $db);

if(!$connect){
	die('Невозможно присоединиться к базе данных: '.mysqli_error($connect));
}else{
	mysqli_query($connect, "SET NAMES 'UTF8'");
	mysqli_query($connect, "SET CHARACTER SET 'UTF8'");
}
?>