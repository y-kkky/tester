<?php
session_start();
include "includes/connect.php";
include "includes/html_codes.php";

if(!isset($_SESSION['role'])||$_SESSION['role']==0){
	header("Location: propmt.php?x=5");
}

if (isset($_GET['test_id'])&&!empty($_GET['test_id'])) {
	$test_id = $_GET['test_id'];
}

if(isset($_POST['change_time'])){
	if (isset($_POST['timme'])) {
		$time = mysqli_real_escape_string($connect, $_POST['timme']);
	}
	$query = "UPDATE tests SET time='$time' WHERE test_id='$test_id'";
	if(mysqli_query($connect, $query)){
		header("Location: test_red.php?test_id=$test_id");
	}
}

?>