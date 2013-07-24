<?php
session_start();
include "includes/connect.php";
include "includes/html_codes.php";

if(!isset($_SESSION['user_id'])){
	header("Location: prompt.php?x=10");
}

$info = getinfo($connect);
$username = $info['username'];

$ip = $_SERVER['REMOTE_ADDR']; 

if(isset($_POST['email'])&&!empty($_POST['email'])&&isset($_POST['body'])&&!empty($_POST['body'])){
	echo $_POST['email'];
	$email = trim($_POST['email']);
	$email = htmlentities($email);
	$body = trim($_POST['body']);
	$body = htmlentities($body);
	$body.= "\n\n\nemail: $email";

	/* Отправление отзыва в письме
	if(mail('yarik.just@gmail.com', 'TESTER', $body)){
		header("Location: prompt.php?x=4");
	}else{
		header("Location: prompt.php?x=8");
	}
	*/

	$mail_query = "INSERT INTO feedback (message,nickname,ip) VALUES ('$body','$username','$ip')";
	if($result_m = mysqli_query($connect, $mail_query)){
		header("Location: prompt.php?x=4");
	}else{
		header("Location: prompt.php?x=8");
	}
}else{
	$error[] = "Незаповнені поля!";
    }
if(isset($_POST['submit'])){
   $error_message = '<span class="error">';
    	foreach ($error as $key => $values) {
    		$error_message.="$values";
    	}
    	$error_message.="</span><br/><br/>";
    }
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Відгук</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/forms.css">
	<link rel="stylesheet" type="text/css" href="css/lab_foot.css">
	<link rel="stylesheet" type="text/css" href="css/footer.css">
	<meta charset="utf-8">
</head>
<body>
	<div id="wrapper">
		<?php headerAndSearchCode();?>
		
		<section style="width: 100%; text-align: center;">
			<form id="feedform" action="feedback.php" method="post" align="center">
				
				<tabel>
					<tr><td><font id="vid">Залиште відгук: </font></td></tr><br><br>
					<tr><td><?php echo @$error_message; ?></td></tr>
					<tr>
						<td><font size=3 color='white'>Ваша пошта: </font></td><br><br><td><input type="text" name="email"/></td><br>
					</tr><br>
					<tr>
						<td><font size=3 color='white'>Текст відгуку (максимум 1000 символів) : </font></td><br><br><td><textarea rows=10 cols=50 name="body"></textarea></td>
					</tr><br><br>
					<tr><td><input type="submit" value="Надіслати" name="submit" class="button"/></td></tr>
			</form>
		</section>
		<div class="push"></div>
		<?php footerCode(); ?>
	</div>
</body>
</html>
<?php mysqli_close($connect); ?>