<?php
session_start();
include "includes/connect.php";
include "includes/html_codes.php";

if((isset($_GET['test_id'])&&isset($_GET['user_id']))||!isset($_SESSION['user_id'])){
	$test_id = trim(htmlentities(mysqli_real_escape_string($connect, $_GET['test_id'])));
	$user_id = htmlentities(mysqli_real_escape_string($connect, $_GET['user_id']));
	if($_SESSION['role']==0&&isActive($test_id)){
		header("Location: index.php");
	}
}else{
	header("Location: index.php");
}

//отбираем вопросы
$query_questions = "SELECT * FROM questions WHERE test_id='$test_id' ORDER BY question_id";
if($result1 = mysqli_query($connect, $query_questions)){
	while ($row = mysqli_fetch_assoc($result1)) {
		$questions[] = $row;
	}
}

foreach ($questions as $key => $value) {
	$question_ids[$value['question_id']] = $value['question_id'];
}

//отбираем ответы
$query_stat = "SELECT * FROM tests_stat WHERE user_id='$user_id' AND test_id='$test_id' ORDER BY question_id";
if($result2 = mysqli_query($connect, $query_stat)){
	while ($row1 = mysqli_fetch_assoc($result2)) {
		$stats_right[] = $row1['right'];
		$stats_answer[] = $row1['answer'];
	}
}





function showIndividual(){
	global $connect, $questions, $stats_right, $stats_answer, $s_var, $question_ids;
	$count = 0;
	$numb = 1;
	foreach ($questions as $key => $value) {

		

		echo $numb.') '.$value['body'].'<br>';
		echo 'Відповідь:<br> ';
		if($value['type']==2){
			//Выбираем варианты на данный вопрос
			$s_var = array();
		foreach ($question_ids as $value123) {
			$query123 = "SELECT variants.variant FROM variants INNER JOIN questions WHERE questions.type=2 AND variants.question_id='".$value['question_id']."' AND variants.question_id='$value123' ORDER BY variant_id";
			 if($result123 = mysqli_query($connect, $query123)){
				while($row = mysqli_fetch_assoc($result123)){
					$s_var[$row['variant']] = $row['variant'];
				}
			}else{
				echo mysqli_error($connect);
			}
		}
			$unserial = unserialize($stats_answer[$count]);
			$unserial_r = unserialize($value['right_answer']);
			$hop = 0;
			foreach ($s_var as $key2 => $value2) {
				if($unserial_r[$hop]==$unserial[$hop]){
				echo '<br><font color="green" style="font-weight: bold; font-size: 15px;">'.$value2.'  -----  '.$unserial[$hop].'</font><br>';
				}else{
				echo '<br><font color="red" style="font-weight: bold; font-size: 15px;">'.$value2.'  -----  '.$unserial[$hop].'</font><br>';
				}
				$hop++;
				if($hop>=count($unserial)){
					break;
				}
			}
			}else if($value['type']==1||$value['type']==3){
			if($stats_right[$count]==1){
				echo '<font color="green" size=4>'.$stats_answer[$count].'</font>';
			}else{
				echo '<font color="red" size=3>'.$stats_answer[$count].'</font>';
			}
		}
			echo '<br><hr><br>';
			$count++;
			$numb++;
		
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
			<?php showIndividual();?>
		</section>
		<div class="push"></div>
		<?php footerCode(); ?>
	</div>
</body>
</html>
<?php mysqli_close($connect); ?>