<?php
session_start();
include 'includes/connect.php';
include 'includes/html_codes.php';

if(!isset($_SESSION['role'])||$_SESSION['role']==0){
	header("Location: prompt.php?x=5");
}
$row = getinfo($connect);
$counters = getCounters($connect);

$counter = $counters['test_counter'];

$next_test_counter = $counter + 1;
$_SESSION['test_id']=$counter;

if(isset($_GET['name'])&&!empty($_GET['name'])&&!empty($_GET['time'])){
	$name = mysqli_real_escape_string($connect, $_GET['name']);
	$time = mysqli_real_escape_string($connect, $_GET['time']);
	$subject = $row['subject'];
	$teacher_id = $row['user_id'];
	$query = "INSERT INTO tests (name,subject,teacher_id,time) VALUES ('$name','$subject','$teacher_id','$time')";
	if(@$result=mysqli_query($connect, $query)){
		$query2 = "UPDATE counters SET test_counter='$next_test_counter'";
		if(@$result2 = mysqli_query($connect, $query2)){
			header("Location: create_test1p.php");
		}else{
			header("Location: prompt.php?x=8");
		}
	}else{
		header("Location: prompt.php?x=8");
	}
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Створення тесту</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/forms.css">
	<link rel="stylesheet" type="text/css" href="css/acc.css">
	<link rel="stylesheet" type="text/css" href="css/test.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
	<meta charset="utf-8">
</head>
<body>
	<div id="wrapper">
		<?php headerAndSearchCode();?>
		<aside id="main_aside">
			<?php infoAsideCode(); ?>
		</aside>

		<section id="main_section" align="center">
			<form action="create_test.php" id="generalform" method="GET" name="forma" style="padding: 40px; text-align: center; font-size: 15px;">
				<div class="field">
					<label>Назва тесту: </label> 
					<input type="text" name="name" id="input"/>
				</div>
				<div class="field">
					<label style="width: 350px;">Час проходження тесту (у форматі 00:00:00): </label>
					<input type="text" name="time" id="input" maxlength=8/>
				</div>
				<input type="submit" value="Далі" class="button"/>
			</form>
		</section>
		<?php footerCode(); ?>
	</div>
</body>
</html>
<?php mysqli_close($connect); ?>