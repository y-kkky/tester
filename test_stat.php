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

if(isset($_GET['test_id'])){
	$test_id = trim(mysqli_real_escape_string($connect, $_GET['test_id']));
}

$user_id = $_SESSION['user_id'];

//Проверка на количество вопросов
$total = 0;
$query_quest = "SELECT question_id,price FROM questions WHERE test_id='$test_id'";
if($res_quest = mysqli_query($connect, $query_quest)){

	while($row = mysqli_fetch_assoc($res_quest)){
        $total += $row['price'];
    }
}

//Класс
$class = '%';

if(isset($_POST['submit'])){
	if(isset($_POST['sel'])){
		$class = $_POST['sel'];
		if($class=="Усі класи"){
			$class = '%';
		}
	}
}

//Собираем айди пользователей из этого класса
$sel_id = "SELECT DISTINCT user_id FROM users WHERE class LIKE '$class' AND class IS NOT NULL AND class <> ''";
if ($res_sel = mysqli_query($connect, $sel_id)) {
	while ($ro = mysqli_fetch_assoc($res_sel)) {
		$user_ids[] = $ro['user_id'];
	}
}

//Подозрение в читерстве
$chit = "SELECT user_id FROM cheat WHERE test_id='$test_id'";
if($result_chit = mysqli_query($connect, $chit)){
	while ($row = mysqli_fetch_assoc($result_chit)) {
		$all_chit[$row['user_id']] = $row['user_id'];
	}
}
if(isset($all_chit)){
	foreach ($all_chit as $value) {
		$qqq = "SELECT DISTINCT user_id FROM tests_stat WHERE user_id='$value'";
		if($resqqq = mysqli_query($connect, $qqq)){
			if(mysqli_num_rows($resqqq)>0){
				unset($all_chit[$value]);
			}
		}
	}
}
//Проверяем тест на активность
if(!isActive($test_id)||$_SESSION['role']!='0'){
	function showStat($user_ids){
		global $connect, $marks, $user_id, $quest_num, $test_id, $class, $all_chit, $total;
		$marks = array();
        if(isset($user_ids)){
            foreach ($user_ids as $value) {
                $query = "SELECT * FROM marks WHERE test_id='$test_id' AND user_id='$value' ORDER BY mark DESC";
                if($result = mysqli_query($connect, $query)){
                    while($row = mysqli_fetch_assoc($result)){
                        $marks[] = $row;
                    }
                }else{
                    echo mysqli_error($connect);
                }
            }
        }
		
			echo '<table border=4>';
			echo "<tr><th>Учень</th><th>Бали</th><th>Оцінка (за 12-ти бальною системою)</th><th></th></tr>";
			if(isset($marks)){
				foreach (@$marks as $key => $value) {
				echo '<tr>';
				echo '<td>'.numberToTeacher($value['user_id']).'</td>';
				echo '<td>'.round($value['mark'], 2).'</td>';
				echo '<td>'.round(($value['mark']*12)/$total, 2).'</td>';
				echo '<td align="center"><a href="test_stat_max.php?test_id='.$test_id.'&user_id='.$value['user_id'].'"><input type="submit" class="button" value="Детальніше"/></a>';
				echo '</tr>';
				}
				if(isset($all_chit)){
					foreach (@$all_chit as $value) {
						echo '<tr style="background: red; color: white;">';
						echo '<td>'.numberToTeacher($value).'</td>';
						echo '<td>----------</td>';
						echo '<td colspan=2 style="text-align: center;">----------</td>';
						echo '</tr>';
					}
				}
			}
			echo '</table>';
		
	}
}else{
	$marks = array();
	function showStat($user_ids){
		global $connect, $marks, $user_id, $quest_num, $test_id, $all_chit, $total;
		foreach ($user_ids as $value) {
			$query = "SELECT * FROM marks WHERE test_id='$test_id' AND user_id='$value' ORDER BY mark DESC";
			if($result = mysqli_query($connect, $query)){
				while($row = mysqli_fetch_assoc($result)){
					$marks[] = $row;
				}
			}else{
				echo mysqli_error($connect);
			}
		}
			echo '<table border=4>';
			echo "<tr><th>Учень</th><th>Бали</th><th>Оцінка (за 12-ти бальною системою)</th></tr>";
			foreach ($marks as $key => $value) {
				if($value['user_id']==$user_id){
					echo '<tr style="font-weight: bold; background: yellow; color: #006699;">';
				}else{
					echo '<tr>';
				}
				echo '<td>'.numberToTeacher($value['user_id']).'</td>';
				echo '<td>'.round($value['mark'], 2).'</td>';
				echo '<td>'.round(($value['mark']*12)/$total, 2).'</td>';
				echo '</tr>';
			}
			if(isset($all_chit)){
				foreach (@$all_chit as $value) {
						echo '<tr style="background: red; color: white;">';
						echo '<td>'.numberToTeacher($value).'</td>';
						echo '<td>----------</td>';
						echo '<td>----------</td>';
						echo '</tr>';
					}
				}
			echo '</table>';
		
	}
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Статистика</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/forms.css">
	<link rel="stylesheet" type="text/css" href="css/test.css">
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

		<section id="main_section"><br><br><br>
			<h1>Статистика</h1><br><br>
			<form action=<?php echo "test_stat.php?test_id=$test_id"; ?> method="POST">
				<select name="sel" style="width: 100px;">
					<option selected="Усі класи" name="Усі класи">Усі класи</option>
					<?php showClasses($test_id); ?>
				</select>
				<input type="submit" name="submit" value="Відсортувати" style="width: 100px;"/>
			</form><br>
			<?php showStat($user_ids); ?>
		</section>
		<div class="push"></div>
		<?php footerCode(); ?>
	</div>
</body>
</html>
<?php mysqli_close($connect); ?>