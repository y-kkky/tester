<?php
session_start();
include 'includes/connect.php';
include 'includes/html_codes.php';

if(!isset($_SESSION['role'])||$_SESSION['role']==0){
	header("Location: prompt.php?x=5");
}

if(isset($_GET['question_id'])&&!empty($_GET['question_id'])){
	$question_id = $_GET['question_id'];
}
if(isset($_GET['test_id'])&&!empty($_GET['test_id'])){
	$test_id = $_GET['test_id'];
}

$query = "DELETE FROM questions WHERE question_id='$question_id'";
if($result = mysqli_query($connect, $query)){
	$query2 = "DELETE FROM variants WHERE question_id='$question_id'";
	if($result2 = mysqli_query($connect, $query2)){
		$query3 = "DELETE FROM tests_stat WHERE test_id='$test_id' AND question_id='$question_id'";
		if($result3 = mysqli_query($connect, $query3)){
			header("Location: test_red.php?test_id=$test_id");
		}else{
			header("Location: prompt.php?x=5");
		}
	}else{
		header("Location: prompt.php?x=5");
	}
}else{
	header("Location: prompt.php?x=5");
}
?>