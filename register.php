<?php
session_start();
include 'includes/connect.php';
include 'includes/html_codes.php';

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
		$error[] = "Незаповнене ім'я. ";
	}else{
		$name = mysqli_real_escape_string($connect, $_POST['name']);
	}
	@$name = trim($name);
	@$name = htmlentities($name);

	//email
	if(empty($_POST['email'])){
        $error[] = 'Незаповнена пошта. ';
    }else if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['email'])){
		$email = mysqli_real_escape_string($connect, $_POST['email']);
    }else{
		$error[] = 'Неправильна поштова скринька. ';
    }

    //password
    if(empty($_POST['password'])){
    	$error[] = 'Незаповнений пароль. ';
    }else if(preg_match("/^([-_:a-zA-Z0-9]{5,})+$/", $_POST['password'])){
    	$password = mysqli_real_escape_string($connect, $_POST['password']);
    }else{
    	$error[] = 'Неправильний пароль.';
    }

    if(empty($error)){
    	//нет ошибок
    	$result = mysqli_query($connect, "SELECT * FROM users WHERE email='$email' OR username='$username'")
    	or die(mysqli_error($connect));
    	if(mysqli_num_rows($result)==0){
    		//good
    		$activation = md5(uniqid(rand(), true));
    		$result2 = mysqli_query($connect, "INSERT INTO tempusers (user_id,username,name,email,password,activation) 
    		VALUES ('','$username','$name','$email','$password','$activation' )") or die(mysqli_error($connect));
    		if(!$result2){
    			die('Неможливо додати данні до бази данних: '.mysqli_error($connect));
    		}else{
                $url = "www.".$_SERVER['HTTP_HOST']."/activate.php?email=".urlencode($email)."&key=$activation";
                $message = "
                    <html>
                    <head>
                      <title>Активація</title>
                      <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                    </head>
                    <body>
                      <p>Натисніть на посилання: </p>
                      <a href='".$url."'>Активація</a><br>
                      Або скопіюйте посилання і вставте в адресну строку:<br>
                      ".$url."
                      

                    </body>
                    </html>
                ";
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    			mail($email, 'Підтвердження реєстрації', $message, $headers);
    			header('Location: prompt.php?x=1');
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
	<title>Реєстрація</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/forms.css">
	<link rel="stylesheet" type="text/css" href="css/register.css">
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
				<h3>Реєстрація</h3>
				<?php echo @$error_message; ?>
				<div class="field">
					<label for="username">Логін:</label>
					<input type="text" class="input" id="username" name="username" maxlength="20"/>
					<p class="hint">Максимально 20 символів (тільки англійські букви та цифри)</p>
				</div>
				<div class="field">
					<label for="username">Ім'я:</label>
					<input type="text" class="input" id="name" name="name" maxlength="20"/>
					<p class="hint">Максимально 80 символів, введіть своє ім'я та прізвище</p>
				</div>
				<div class="field">
					<label for="email">Пошта:</label>
					<input type="text" class="input" id="email" name="email" maxlength="80"/>
					<p class="hint">Будь ласка, введіть справжню пошту. Вона буде використана для активації аккаунта.</p>
				</div>
				<div class="field">
					<label for="password">Пароль:</label>
					<input type="password" class="input" id="password" name="password" maxlength="20"/>
					<p class="hint">Максимально 20 символів, мінімально 5 (Англійські букви, цифри, крапка, двокрапка, тире і нижнє підкреслення)</p>
				</div>
				<input type="submit" name="submit" class="button" value="Готово"/>
			</form>
		</section>
		<?php footerCode(); ?>
	</div>
</body>
</html>
<?php mysqli_close($connect); ?>