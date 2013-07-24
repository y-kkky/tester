<?php
session_start();
include 'includes/connect.php';
include 'includes/html_codes.php';

if(!isset($_SESSION['role'])||$_SESSION['role']==0){
	header("Location: prompt.php?x=5");
}

if(isset($_GET['question_id'])){
	$question_id = mysqli_real_escape_string($connect, $_GET['question_id']);
}

$query = "SELECT * FROM questions WHERE question_id='$question_id'";
if($result = mysqli_query($connect, $query)){
	while($row = mysqli_fetch_assoc($result)){
		$question[] = $row;
	}
	$test_id = mysqli_result($result,0,'test_id');
}

//Получаем test_id
$test_id = mysqli_result($result,0,'test_id');

$type = $question[0]['type'];
$variant_num = $question[0]['variant_num'];

//Получаем варианты по даному тесту
$get_var_ids = "SELECT variant_id FROM variants WHERE question_id='$question_id'";
if($result_vids = mysqli_query($connect, $get_var_ids)){
	while ($row = mysqli_fetch_assoc($result_vids)) {
		$varids[$row['variant_id']] = $row['variant_id'];
	}
}

//Редактирование данных вопросов
if(isset($_POST['submit1'])||isset($_POST['submit2'])||isset($_POST['submit3'])){
    if(isset($_POST['price'])){
        $price = $_POST['price'];
    }
	if($type==1){
			if(isset($_POST['question'])&&isset($_POST['counter'])){
				$question = $_POST['question'];
				$right_answer = $_POST['counter'];
				for($i=1;$i<=$variant_num;$i++){
					$variant[$i] = $_POST['variant'.$i];
				}
				
				$query = "UPDATE questions SET body='$question',right_answer='$right_answer',price='$price' WHERE question_id='$question_id'";
				if(mysqli_query($connect, $query)){
					$counter = key($varids);
					foreach ($variant as $value) {
						if(mysqli_query($connect, "UPDATE variants SET variant='$value' WHERE variant_id='$counter'")){

						}else{
							header("Location: prompt.php?x=8");
						}
						$counter++;
					}
				}else{
					header("Location: prompt.php?x=8");
				}
			}
		}else if($type==2){
			if(isset($_POST['question'])){
				$question2 = $_POST['question'];
				$f_variant = array();
				for($i=1;$i<=$variant_num;$i++){
					$f_variant[$i] = $_POST['1_variant'.$i];
				}
				$s_variant = array();
				for($b=1;$b<=$variant_num;$b++){
					$s_variant[$b] = $_POST['2_variant'.$b];
				}
				$query = "UPDATE questions SET body='$question2',price='$price' WHERE question_id='$question_id'";
				if(mysqli_query($connect, $query)){
					$contter = key($varids);
					foreach ($f_variant as $value) {
						if(mysqli_query($connect, "UPDATE variants SET question_id='$question_id',variant='$value' WHERE variant_id='$contter'")){

						}else{
							header("Location: prompt.php?x=8");
						}
						unset($varids[$contter]);
						$contter++;
					}
					$bbb = 0;			
					$right1 = array();
					$cuntter = key($varids);
					foreach ($s_variant as $value) {
						$right1[$bbb] = $value;
						$bbb++;
						$serialize = serialize($right1);
						if(mysqli_query($connect, "UPDATE variants SET question_id='$question_id',variant='$value' WHERE variant_id='$cuntter'")){
							if(mysqli_query($connect, "UPDATE questions SET right_answer='$serialize' WHERE question_id='$question_id'")){

							}else{
								header("Location: prompt.php?x=8");
							}
						}else{
							header("Location: prompt.php?x=8");
						}
						$cuntter++;
					}
				}else{
					header("Location: prompt.php?x=8");
				}
			}else{
				header("Location: prompt.php?x=1");
			}
		}else if($type==3){
			if(isset($_POST['question'])&&isset($_POST['right'])){
				$question = $_POST['question'];
				$right = $_POST['right'];
				$query = "UPDATE questions SET body='$question',right_answer='$right',price='$price' WHERE question_id='$question_id'";
				if(mysqli_query($connect, $query)){
				}else{
					header("Location: prompt.php?x=8");
				}
			}
		}

		header("Location: test_red.php?test_id=$test_id");

	}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Тести</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/forms.css">
	<link rel="stylesheet" type="text/css" href="css/acc.css">
	<link rel="stylesheet" type="text/css" href="css/test.css">
	<style type="text/css">
	label{
		text-align: left;
		float: left;
		width: 150px;
	}
	#generalform{
		width: 840px;
		height: 460px;
	}
	</style>
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
			<?php echo "<form id='generalform' action='test_red_q.php?question_id=".$question_id."' method='POST'>";?>
				<?php redQuestion($question_id);?>
			</form>
		</section>
		<div class="push"></div>
		<?php footerCode();?>
	</div>
</body>
</html>
<?php mysqli_close($connect); ?>