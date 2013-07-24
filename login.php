<?php
session_start();
include "includes/connect.php";
include "includes/html_codes.php";

if(isset($_SESSION['user_id'])){
	header("Location: acc.php");
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

    //password
    if(empty($_POST['password'])){
    	$error[] = 'Незаповнений пароль. ';
    }else if(preg_match("/^([-_:a-zA-Z0-9]{5,})+$/", $_POST['password'])){
    	$password = mysqli_real_escape_string($connect, $_POST['password']);
    	$password_hash = '00p'.sha1(md5($password));
    }else{
    	$error[] = 'Пароль закороткий, або у ньому наявні сторонні символи.';
    }

    //checkbox
    if(isset($_POST['check'])){
    	$role=1;
    }else{
    	$role=0;
    }

    if(empty($error)){
    	//код входа
    	$result = mysqli_query($connect, "SELECT * FROM users WHERE username='$username' AND password='$password_hash' AND role='$role'") or die(mysqli_error($connect));
    	if(mysqli_num_rows($result)==1){
    		while($row = mysqli_fetch_array($result)){
    			$_SESSION['user_id'] = $row['user_id'];
    			$_SESSION['role'] = $row['role'];
    			header('Location: acc.php');
    		}
    	}else{
    		$error_message = '<span class="error">Логін або пароль введені неправильно</span><br/><br/>';
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
	<title>Вхід</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/forms.css">
	<link rel="stylesheet" type="text/css" href="css/login.css">
	<link rel="stylesheet" type="text/css" href="css/footer.css">
	<meta charset="utf-8">
</head>
<body>
	<div id="wrapper">
		<?php headerAndSearchCode();?>
		<aside id="left_side">
			<img src="images/login.png" />
		</aside>

		<section id="right_side">
			<form id="generalform" class="container" method="post" action="">
				<h3>Вхід</h3>
				<?php echo @$error_message; ?>
				<div class="field">
					<label for="username">Нікнейм:</label>
					<input type="text" class="input" id="username" name="username" maxlength="20"/>
				</div>
				<div class="field">
					<label for="password">Пароль:</label>
					<input type="password" class="input" id="password" name="password" maxlength="20"/>
				</div>
				<div class="field">
					<label for="check">Учитель:</label>
					<input type="checkbox" id="check" name="check"/>
				</div><br>
				<p align="left"><input type="submit" name="submit" class="button" value="Готово"/></p>
			</form>
		</section>
		<?php footerCode(); ?>
	</div>
</body>
</html>
<?php mysqli_close($connect); ?>