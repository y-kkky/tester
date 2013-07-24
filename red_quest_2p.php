<?php
session_start();
include 'includes/connect.php';
include 'includes/html_codes.php';

if(!isset($_SESSION['role'])||$_SESSION['role']==0){
	header("Location: prompt.php?x=5");
}

$row = getinfo($connect);


$counters = getCounters($connect);
$quest_id = $_SESSION['quest_id'];
$type = $_SESSION['type'];
@$variant_num = $_SESSION['variant_num'];

if(isset($_POST['submit'])||isset($_POST['submit2'])){
    //Подгрузка изображений
    $upload_dir = 'uploads/';
    $image_fieldname = "pic";
    $upload_filename = '0';

    //***********************************************//
    if($_FILES[$image_fieldname]['error']==0){
        $now = time();
        $upload_filename = $upload_dir.$now.'-'.$_FILES[$image_fieldname]['name'];
        chmod($upload_filename, 777);
        move_uploaded_file($_FILES[$image_fieldname]['tmp_name'], $upload_filename) or die();
    }
    //***********************************************//
	if($type==1){
		if(isset($_POST['question'])&&isset($_POST['counter'])){
			$question = $_POST['question'];
			$right_answer = $_POST['counter'];
			for($i=1;$i<=$variant_num;$i++){
				$variant[$i] = $_POST['variant'.$i];
			}
			$query = "UPDATE questions SET body='$question',right_answer='$right_answer',image='$upload_filename' WHERE question_id='$quest_id'";
			if(mysqli_query($connect, $query)){
				foreach ($variant as $value) {
					if(mysqli_query($connect, "INSERT INTO variants (question_id,variant) VALUES ('$quest_id','$value')")){

					}else{
						header("Location: prompt.php?x=8");
					}
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
			$query = "UPDATE questions SET body='$question2',image='$upload_filename' WHERE question_id='$quest_id'";
			if(mysqli_query($connect, $query)){
				foreach ($f_variant as $value) {
					if(mysqli_query($connect, "INSERT INTO variants (question_id,variant) VALUES ('$quest_id','$value')")){

					}else{
						header("Location: prompt.php?x=8");
					}
				}
				$bbb = 0;			
				$right1 = array();
				foreach ($s_variant as $value) {
					$right1[$bbb] = $value;
					$bbb++;
					$serialize = serialize($right1);
					if(mysqli_query($connect, "INSERT INTO variants (question_id,variant) VALUES ('$quest_id','$value')")){
						if(mysqli_query($connect, "UPDATE questions SET right_answer='$serialize' WHERE question_id='$quest_id'")){

						}else{
							header("Location: prompt.php?x=8");
						}
					}else{
						header("Location: prompt.php?x=8");
					}
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
			$query = "UPDATE questions SET body='$question',right_answer='$right',image='$upload_filename' WHERE question_id='$quest_id'";
			if(mysqli_query($connect, $query)){
			}else{
				header("Location: prompt.php?x=8");
			}
		}
	}
}
if(isset($_POST['submit'])){
	header("Location: red_quest_1p.php");
}else if(isset($_POST['submit2'])){
	header("Location: test_red.php?test_id=".$_SESSION['test_id']);
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
	<meta charset="utf-8">
	<style type="text/css">
		label{
			width: 150px;
			text-align: left;
		}
	</style>
</head>
<body>
	<div id="wrapper">
		<?php headerAndSearchCode();?>
		<aside id="main_aside">
			<?php infoAsideCode(); ?>
		</aside>

		<section id="main_section" align="center">
			<form action="red_quest_2p.php" id="generalform" method="POST" name="forma" enctype="multipart/form-data" style="padding: 40px; text-align: center; font-size: 15px;">
				<?php createQuestion($type, $variant_num);?>
                <div class="field">
                    <input type="hidden" name="MAX_FILE_SIZE" value="2000000"/>
                    <label for="pic" style="width: 200px;">Завантажте малюнок: </label>
                    <input type="file" name="pic" size="30"/>
                </div><br><br><br>
				<input type="submit" name="submit" class="button" value="Далі"/>
				<input type="submit" name="submit2" class="button" value="Завершити тест"/>
			</form>
		</section>
		<?php footerCode(); ?>
	</div>
</body>
</html>
<?php mysqli_close($connect); ?>