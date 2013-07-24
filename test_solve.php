<?php
session_start();
include "includes/connect.php";
include "includes/html_codes.php";

if(!isset($_SESSION['user_id'])){
	header("Location: prompt.php?x=10");
}
if(!isset($_GET['test_id'])){
	header("Location: index.php");
}
$user_id = $_SESSION['user_id'];


if(isset($_GET['test_id'])){
	$test_id = mysqli_real_escape_string($connect, $_GET['test_id']);
}

if(!isActive($test_id)){
	header("Location: prompt.php?x=11");
}

//Проверка на обновление страницы
if($_SESSION['role']==0){
	$queryy = "SELECT * FROM cheat WHERE test_id='$test_id' AND user_id='$user_id'";
	if($resulttt = mysqli_query($connect, $queryy)){
		if(mysqli_num_rows($resulttt)>0){
			header("Location: prompt.php?x=9");
		}else{
			//Добавление в систему защиты от читерства
			$dobav = "INSERT INTO cheat (test_id,user_id) VALUES ('$test_id','$user_id')";
			if($dobavres = mysqli_query($connect, $dobav)){

			}else{
				header("Location: prompt.php?x=8");
			}
		}
	}
}

//Проверка, решал ли этот человек этот тест
$proverka = "SELECT mark FROM marks WHERE user_id='$user_id' AND test_id='$test_id'";
if($result_prov = mysqli_query($connect, $proverka)){
	if(mysqli_num_rows($result_prov)>0){
		header("Location: prompt.php?x=9");
	}
}

//Получаем тест
$query = "SELECT * FROM tests WHERE test_id='$test_id'";
if($result = @mysqli_query($connect, $query)){
	$row = mysqli_fetch_array($result);
}

//Название теста
$test_name = $row['name'];
$test_time = $row['time'];

//Получаем вопросы
$query2 = "SELECT * FROM questions WHERE test_id='$test_id' ORDER BY question_id";
if($result2 = @mysqli_query($connect, $query2)){
	while($row = mysqli_fetch_array($result2)){
			$questions[] = $row;
			$number_of_questions = count($questions);
		}
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Розв`язання тесту</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/forms.css">
	<link rel="stylesheet" type="text/css" href="css/test.css">
	<link rel="stylesheet" type="text/css" href="css/timer.css">
	<meta charset="utf-8">
	<script type="text/javascript">
		function otmena(){
			window.onbeforeunload = function(){
				
			}
		}
		function startTimer() {
		var timer = document.getElementById("timer");
		var time = timer.innerHTML;
		var arr = time.split(":");
		var h = arr[0];
		var m = arr[1];
		var s = arr[2];
		if (s == 0) {
			if (m == 0) {
				if (h == 0) {
					window.onbeforeunload = function(){
					}
					document.getElementById("knop").click();
					return;
				}
				h--;
				m = 60;
				if (h < 10) h = "0" + h;
			}
			m--;
			if (m < 10) m = "0" + m;
			s = 59;
		}
		else s--;
		if (s < 10) s = "0" + s;
		document.getElementById("timer").innerHTML = h+":"+m+":"+s;
		setTimeout(startTimer, 1000);
		}
		window.onbeforeunload = function(){
			return "Ви впевнені, що хочете покинути/оновити сторінку? Ви більше не зможете повернутися до розв`язання цього тесту!!!";
		}
</script>
</head>
<body onload="startTimer()">
	<div id="wrapper">
		<?php headerAndSearchCode();?>

		<aside id="main_aside">
			<ul id="menu">
				<?php createCategoryMenu(); ?>
			</ul>
		</aside>
		
		<section id="main_section"><br><br><br>
			<div id="timer"><?php echo $test_time?></div>
			<h1><?php echo $test_name; ?></h1><br><br>
			<form <?php echo "action='test_engine.php?test_id=$test_id'"?> name="test" id="testok" method="post">				
				<?php echo showQuestion($questions);?>
				<input type="submit" name="submit" id="knop" onclick="otmena();" value="Готово" class="button"/>
			</form>
		</section>
		<div class="push"></div>
		<?php footerCode(); ?>
	</div>
</body>
</html>
<?php mysqli_close($connect); ?>