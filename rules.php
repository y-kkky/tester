<?php
session_start();
include "includes/connect.php";
include "includes/html_codes.php";
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Правила сайту</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/forms.css">
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

		<section id="main_section">
				<font size=7><p align='center'>Правила сайту:</p></font><br>
				
					<font size=5>1. Бажано заходити через інтернет-браузер Google Chrome (тоді ви отримаєте можливість користуватися сайтом у повній мірі). Не бажано користуватися сайтом через Internet Explorer та Opera. <br><br>
					2. Під час виконання тестів ні в якому разі не виходьте і не оновлюйте сторінку тесту. У вас є тільки одна спроба для проходження тесту. Будьте уважні!<br><br>
					3. Якщо ви помітили якусь проблему у роботі сайту, або якщо у вас є якісь побажання щодо покращення роботи сайту - скористуйтеся <a href="feedback.php">зворотним зв'язком</a>.</font>
				
		</section>
		<div class="push"></div>
		<?php footerCode(); ?>
	</div>
</body>
</html>
<?php mysqli_close($connect); ?>
