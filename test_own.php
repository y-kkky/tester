<?php
session_start();
include 'includes/connect.php';
include 'includes/html_codes.php';

if(!isset($_SESSION['role'])||$_SESSION['role']==0){
	header("Location: prompt.php?x=5");
}

$userinfo = getinfo($connect);
$teacher_id = $userinfo['user_id'];

$query = "SELECT * FROM tests WHERE teacher_id='$teacher_id' ORDER BY test_id DESC";
if($result = mysqli_query($connect, $query)){
	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}
	}else{
		$arr = 'Немає тестів';
	}
}else{
	header("Location: prompt.php?x=8");
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Тести</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/tests.css">
	<meta charset="utf-8">
</head>
<body>
	<div id="wrapper">
		<?php headerAndSearchCode();?>
		<aside id="main_aside">
			<ul id="menu">
				<?php createCategoryMenu(); ?>
			</ul>
		</aside>

		<section id="main_section" align="center">
			<?php showTestRed($arr);?>
		</section>
		<div class="push"></div>
		<?php footerCode(); ?>
	</div>
</body>
</html>
<?php mysqli_close($connect); ?>