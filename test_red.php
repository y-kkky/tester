<?php
session_start();
include 'includes/connect.php';
include 'includes/html_codes.php';

if(!isset($_SESSION['role'])||$_SESSION['role']==0){
	header("Location: prompt.php?x=5");
}

if(isset($_GET['test_id'])){
	$test_id = mysqli_real_escape_string($connect, $_GET['test_id']);
}
$_SESSION['test_id'] = $test_id;

$hoppp = "SELECT DISTINCT status,time FROM tests WHERE test_id='$test_id'";
if($ress = mysqli_query($connect, $hoppp)){
	$status = mysqli_result($ress,0,'status');
	$time = mysqli_result($ress,0,'time');
}
//Проверка, решали его или нет
$proverka = "SELECT * FROM tests_stat WHERE test_id='$test_id'";
if($result = mysqli_query($connect, $proverka)){
	if(mysqli_num_rows($result)>0){
		header("Location: prompt.php?x=13");
	}
}


$userinfo = getinfo($connect);
$teacher_id = $userinfo['user_id'];

$query = "SELECT * FROM questions WHERE test_id='$test_id' ORDER BY test_id DESC";
if($result = mysqli_query($connect, $query)){
	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_array($result)){
			$questions[] = $row;
		}
	}else{
		$questions = 'Нема питань';
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
	<style type="text/css">
	#niz{
		width: 250px;
		float: left;
	}
	#timme{
		width: 300px;
	}
	</style>
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
			<?php showQuestList($questions); ?><br><br>

			<?php if($status==0) echo "<a href=test_change_stat.php?test_id=$test_id&status=1 onclick=\"return confirm('Ви впевнені, що хочете це зробити?');\"><input  type=\"button\" id=\"niz\" name=\"change_stat\" value=\"Перевести в неактивні\" class=\"button\"/></a><br><br>";else echo "<a href=test_change_stat.php?test_id=$test_id&status=0 onclick=\"return confirm('Ви впевнені, що хочете це зробити?');\"><input  type=\"button\" id=\"niz\" name=\"change_stat\" value=\"Перевести в активні\" class=\"button\"/></a><br><br>"?>
				
				<a href="red_quest_1p.php" ><input type="submit" id="niz" onclick="return confirm('Ви впевнені, що хочете створити питання?');" name="add_quest" value="Додати питання" class="button"/></a><br><br>
				<form action=<?php echo "test_change_time.php?test_id=$test_id"; ?> method="post"><input type="text" name="timme" id="timme" placeholder="Введіть новий час у форматі 00:00:00"/><input type="submit" id="niz" name="change_time" value="Змінити час проходження тесту" class="button"/></form><br>
				<?php echo "Час проходження тесту: $time";?>
		</section>
		<div class="push"></div>
		<?php footerCode(); ?>
	</div>
</body>
</html>
<?php mysqli_close($connect); ?>