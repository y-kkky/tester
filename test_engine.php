<?php
session_start();
include "includes/connect.php";
include "includes/html_codes.php";

if(!isset($_SESSION['user_id'])){
	header("Location: prompt.php?x=10");
}
$user_id = $_SESSION['user_id'];

if(isset($_GET['test_id'])){
	$test_id = $_GET['test_id'];
}

//Получаем вопросы
$query2 = "SELECT * FROM questions WHERE test_id='$test_id'";
if($result2 = @mysqli_query($connect, $query2)){
	while($row = mysqli_fetch_array($result2)){
			$questions[] = $row;
			$number_of_questions = count($questions);
		}
}

//ОЦЕНКА
$mark = 0;
$max_mark = 0;

//ОБРАБОТКА РЕЗУЛЬТАТОВ ТЕСТА
if(isset($_POST['submit'])){
	foreach ($questions as $key => $value) {
        $price = $value['price'];
		$question_id=$value['question_id'];
        $max_mark += $price;
		if ($value['type']==1) {
			$right_answer=$value['right_answer'];
			$right_answer-=1;
			$answer = $_POST["f$question_id"];
			$var_query = "SELECT variant_id, variant FROM variants WHERE question_id='$question_id'";
			$opp = array();
			if($result2 = mysqli_query($connect, $var_query)){
				while($rowx = mysqli_fetch_array($result2)){
					$opp[] = $rowx['variant'];
				}
				$l_right_answer = $opp["$right_answer"];
				$rightness = 0;
				if($answer==$l_right_answer){
					$rightness = 1;
					$mark+=$price;
				}else{
					$rightness = 0;
				}
			}
			$query = "INSERT INTO tests_stat VALUES ('$user_id','$test_id','$question_id','$answer','$rightness')";
			if($result = mysqli_query($connect, $query)){

				}else{
					echo mysqli_error($connect);
				}


		}else if($value['type']==2){
			$second_counter = 0;
			$right_answer = $value['right_answer'];
			$answer = array();
			for($ba=0;$ba<$value['variant_num'];$ba++){
				$answer[$ba] = $_POST["s$question_id$ba"];
			}
			$serialization = array();
			$serialization = serialize($answer);

			//Высчитывание правильного результата
			$right_unser = array();
			$right_unser = unserialize($right_answer);
			for($ii=0;$ii<count($answer);$ii++){
				if($answer[$ii]==$right_unser[$ii]){
					$second_counter++;
				}
			}

			$ll_right_answer = ($second_counter/$value['variant_num'])*$price;
			$mark += $ll_right_answer;
			$query15 = "INSERT INTO tests_stat VALUES ('$user_id','$test_id','$question_id','$serialization','$ll_right_answer')";
			if($result15 = mysqli_query($connect, $query15)){

				}else{
					echo mysqli_error($connect);
				}


		}else if($value['type']==3){
			$right_answer = $value['right_answer'];
			$answer = trim(mysqli_real_escape_string($connect, $_POST["t$question_id"]));
			if($right_answer == $answer){
				$rightness = 1;
				$mark+=$price;
			}else{
				$rightness = 0;
			}
			$query = "INSERT INTO tests_stat VALUES ('$user_id','$test_id','$question_id','$answer','$rightness')";
			if($result = mysqli_query($connect, $query)){

				}else{
					echo mysqli_error($connect);
				}
		}
	}
	$last_query = "INSERT INTO marks (test_id, user_id, mark) VALUES ('$test_id','$user_id','$mark')";
	if($result = mysqli_query($connect, $last_query)){

	}else{
		header("Location: propmt.php?x=8");
	}
}
if($_SESSION['role']==0){
			header("Location: prompt.php?x=12&mark={$mark}&n={$max_mark}&test_id={$test_id}");
		}else{
			header("Location: prompt.php?x=14&mark={$mark}&n={$max_mark}&test_id={$test_id}&user_id={$user_id}");
		}

?>