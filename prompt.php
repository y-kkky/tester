<?php
session_start();
include "includes/connect.php";
include "includes/html_codes.php";
$x = @$_GET['x'];

function createMessage($x){
	if(is_numeric($x)){
		switch ($x) {
			case 0:
				$message = "Ваш аккаунт активований! Тепер Ви можете <a href=\"login.php\">УВІЙТИ</a>";
				break;
			
			case 1:
				$message = "Дякуємо за реєстрацію! Лист був відісланий на Вашу поштову скриньку. Будь ласка, активуйте Ваш аккаунт.";
				break;

			case 2:
				$message = "Користувач з цією поштовою скринькою або нікнеймом вже наявний!";
				break;
				
			case 3:
				$message = "Тест успешно збережений.";
				break;		

			case 4:
				$message = "Дякуємо за відгук!";
				break;

			case 5:
				$message = "Ви не маєте доступу до цієї сторінки!";
				break;

			case 6:
				$message = "Вчитель зареєстрований!";
				break;

			case 7;
				$message = "Інформація успішно оновлена!";
				break;

			case 8:
				$message = "Сталася помилка. Зверніться до адміністратора.";
				break;

			case 9:
				$message = "Ви вже розв'язували цей тест!";
				break;

			case 10:
				$message = "Тільки зареєстрованим користувачам доступна ця сторінка. Будь ласка, <a href='register.php'>ЗАРЕЄСТРУЙТЕСЯ</a>";
				break;

			case 11:
				$message = "Час проходження цього тесту закінчився. Він неактивний.";
				break;

			case 12:
				$mark = $_GET['mark'];
				$n = $_GET['n'];
				$test_id = $_GET['test_id'];
				$message = "Розв`язання тесту закінчено.У вас $mark вірних відповідей з $n. <a href=\"test_stat.php?test_id=$test_id\">Детальніше</a>";
				break;

			case 13:
				$message = "Цей тест не можна редагувати (його вже почали розв'язувати)";
				break;

			case 14:
				$mark = $_GET['mark'];
				$n = $_GET['n'];
				$test_id = $_GET['test_id'];
				$user_id = $_GET['user_id'];
				$message = "Розв`язання тесту закінчено.У вас $mark вірних відповідей з $n. <a href=\"test_stat_max.php?test_id=$test_id&user_id=$user_id\">Детальніше</a>";
				break;
			//место для других уведомлений
		}

		echo $message;
	}
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Важливе повідомлення</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/prompt.css">
	<meta charset="utf-8">
</head>
<body>
	<div id="wrapper">
		<?php headerAndSearchCode();?>

		<div id="outer">
			<div id="inner">
				<?php createMessage($x); ?>
			</div>
		</div>

		<?php footerCode(); ?>
	</div>
</body>
</html>
<?php mysqli_close($connect); ?>