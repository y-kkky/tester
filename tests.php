<?php
session_start();
include "includes/connect.php";
include "includes/html_codes.php";

if(!isset($_SESSION['user_id'])){
	header("Location: prompt.php?x=10");
}

$subject = '%';
$teacher_id = '%';
$keywords = '%';
$last_category = '%';

if(isset($_GET['category'])&&$_GET['category']!=''){
	if($_GET['category']=='Усі предмети'){
		$last_category = '%';
	}else{
		$category = trim($_GET['category']);
		$category = htmlentities($category);
		$category = mysqli_real_escape_string($connect, $category);
		$last_category = numberToCategory($category);
	}
}

if(isset($_GET['teacher_id'])){
	$teacher_id = mysqli_real_escape_string($connect, $_GET['teacher_id']);
	$teacher_id = trim($teacher_id);
	$teacher_id = htmlentities($teacher_id);
}

if(isset($_GET['keywords'])&&$_GET['keywords']!=''){
	$keywords = mysqli_real_escape_string($connect, $_GET['keywords']);
	$keywords = htmlentities($keywords);
}

$query = "SELECT * FROM tests WHERE subject LIKE '$last_category' AND teacher_id LIKE '$teacher_id' AND name LIKE '%$keywords%' ORDER BY test_id DESC";
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
			<?php showTest($arr);?>
		</section>
		<div class="push"></div>
		<?php footerCode(); ?>
	</div>
</body>
</html>
<?php mysqli_close($connect); ?>