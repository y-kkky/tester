<?php
session_start();
include 'includes/connect.php';
include 'includes/html_codes.php';

if($_SESSION['role']!=2){
	header("Location: prompt.php?x=5");
}
if(isset($_POST['submit'])){
	$error = array();
	//username
	if(empty($_POST['username'])){
		$error[] = 'Незаповнений нікнейм. ';
	}else if(ctype_alnum($_POST['username'])){
		$username = $_POST['username'];
	}else{
		$error[] = 'Сторонні символи у нікнеймі. ';
	}

	//name
	if(empty($_POST['name'])){
		$error[] = 'Незаповнене Ф.І.О';
	}else{
		$name = $_POST['name'];
	}

    //password
    if(empty($_POST['password'])){
    	$error[] = 'Незаповнений пароль. ';
    }else if(preg_match("/^([-_:a-zA-Z0-9]{5,})+$/", $_POST['password'])){
    	$password = mysqli_real_escape_string($connect, $_POST['password']);
    	$password_hash = '00p'.sha1(md5($password));
    }else{
    	$error[] = 'Неправильний пароль.';
    }

    //subject
    $subject=numberToCategory($_POST['subject']);

    if(empty($error)){
    	//нет ошибок
    	$result = mysqli_query($connect, "SELECT * FROM users WHERE username='$username'")
    	or die(mysqli_error($connect));
    	if(mysqli_num_rows($result)==0){
    		//good
    		$result2 = mysqli_query($connect, "INSERT INTO users (user_id,username,password,role,subject,name) 
    		VALUES ('','$username','$password_hash',1,'$subject','$name' )") or die(mysqli_error($connect));
    		if(!$result2){
    			die('Неможливо додати данні до бази данних: '.mysqli_error($connect));
    		}else{
    			header('Location: prompt.php?x=6');
    		}
    	}else{
    		header('Location: prompt.php?x=2');
    	}
    }else{
    	$error_message = '<span class="error">';
    	foreach ($error as $key => $values) {
    		$error_message.="$values";
    	}
    	$error_message.="</span><br/><br/>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Реєстрація вчителів</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/forms.css">
	<link rel="stylesheet" type="text/css" href="css/register.css">
	<link rel="stylesheet" type="text/css" href="css/lab_foot.css">
	<link rel="stylesheet" type="text/css" href="css/footer.css">
	<meta charset="utf-8">
</head>
<body>
	<div id="wrapper">
		<?php headerAndSearchCode();?>
		<aside id="left_side">
			<img src="images/reg.PNG" />
		</aside>

		<section id="right_side">
			<form id="generalform" class="container" method="post" action="">
				<h3>Реєстрація вчителів</h3>
				<?php echo @$error_message; ?>
				<div class="field">
					<label for="username">Логін:</label>
					<input type="text" class="input" id="username" name="username" maxlength="20"/>
					<p class="hint">Максимально 20 символів (тільки англійські букви та цифри)</p>
				</div>	
				<div class="field">
					<label for="name">Ф.І.О:</label>
					<input type="text" class="input" id="name" name="name" maxlength="80"/>
					<p class="hint">Максимально 80 символів</p>
				</div>			
				<div class="field">
					<label for="subject">Предмет:</label>
					<select id="subject" name="subject" class="input">
						<?php createCategoryList();?>
					</select>
					<p class="hint">Предмет, який вчитель викладає</p>
				</div>
				<div class="field">
					<label for="password">Пароль:</label>
					<input type="password" class="input" id="password" name="password" maxlength="20"/>
					<p class="hint">Максимально 20 символів, мінімально 5 (Англійські букви, цифри, крапка, двокрапка, тире і нижнє підкреслення)</p>
				</div>
				<input type="submit" name="submit" type="submit" class="button" value="Готово"/>
			</form>
		</section>
		<?php footerCode(); ?>
	</div>
</body>
</html>
<?php mysqli_close($connect); ?>