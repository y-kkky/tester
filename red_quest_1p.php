<?php
session_start();
include 'includes/connect.php';
include 'includes/html_codes.php';

if(!isset($_SESSION['role'])||$_SESSION['role']==0){
	header("Location: prompt.php?x=5");
}
$row = getinfo($connect);
$counters = getCounters($connect);

$test_id = $_SESSION['test_id'];

$quest_id = $counters['quest_counter'];
$next_quest_counter = $quest_id + 1;
$_SESSION['quest_id'] = $quest_id;

if(isset($_GET['selection'])&&!empty($_GET['selection'])){
	$type = $_GET['selection'];
	$_SESSION['type']=$type;
	$variant_num = 0;
	if($type!=3){
		if(isset($_GET['counter'])&&!empty($_GET['counter'])){
			$variant_num = $_GET['counter'];
			$_SESSION['variant_num']=$variant_num;
		}
	}

    //Цена вопроса
    $price = 1;
    if(isset($_GET['price'])&&ctype_digit($_GET['price'])){
        $price = $_GET['price'];
    }

	$query = "INSERT INTO questions (test_id,type,variant_num) VALUES ('$test_id','$type','$variant_num')";
	if(@$result=mysqli_query($connect, $query)){
		$query2 = "UPDATE counters SET quest_counter='$next_quest_counter'";
		if(@$result2 = mysqli_query($connect, $query2)){
			header("Location: red_quest_2p.php");
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
	<meta charset="utf-8">
	<style type="text/css">
	label{
		width: 200px;
		text-align: left;
	}
	</style>
	<script type="text/javascript">
	function disa(){
		var selecc = document.getElementById("sele");
		if(selecc.selectedIndex == 2){
			document.getElementById('counter').disabled = true;
		}else{
			document.getElementById('counter').disabled = false;
		}
		
	}
	</script>
</head>
<body>
	<div id="wrapper">
		<?php headerAndSearchCode();?>
		<aside id="main_aside">
			<?php infoAsideCode(); ?>
		</aside>

		<section id="main_section" align="center">
			<form action="red_quest_1p.php" id="generalform" method="GET" name="forma" style="padding: 40px; text-align: center; font-size: 15px;">
				<div class="field">
				<label style="width: 200px; text-align: center">Виберіть тип питання:  </label>
					<select name="selection" id="sele" onchange="disa();"><br>
						<option selected="selected" value=1>Питання з варіантами</option>
						<option value=2>З`єднай варіанти</option>
						<option onChange="" value=3>Задача</option>
					</select><br><br>
				</div>
				<div class="field">
					<label>Введіть кількість варіантів (крім задачі):</label><br><br>
									<input type="text" id="counter" name="counter"/><br><br>
				</div>
                <div class="field">
                    <label>Введіть ціну завдання (якщо 1 - залиште пустим):</label><br><br>
                    <input type="text" id="price" name="price"/><br><br>
                </div>

				<input type="submit" value="  Далі  " name="go" class="button"/>
			</form>
		</section>
		<?php footerCode(); ?>
	</div>
</body>
</html>
<?php mysqli_close($connect); ?>