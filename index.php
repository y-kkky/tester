<?php
session_start();
include "includes/connect.php";
include "includes/html_codes.php";
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Головна сторінка</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/forms.css">
	<meta charset="utf-8">
        <script>
        alert(window.location.hostname);
        window.counter = 0;
        function incr(){
            window.counter++;
            if(window.counter == 15){
                window.location = "paskkhalka.php";
            }
        }
        </script>
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
			<p align="center"><img src="images/main.jpg"/></p>
		</section>
		<div class="push"></div>
		<?php footerCode(); ?>
	</div>
</body>
</html>
<?php mysqli_close($connect); ?>