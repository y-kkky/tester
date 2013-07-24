<?php
session_start();
include 'includes/connect.php';
include 'includes/html_codes.php';

if(!isset($_SESSION['user_id'])){
	header("Location: prompt.php?x=10");
}
$row = getinfo($connect);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Акаунт</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/forms.css">
	<link rel="stylesheet" type="text/css" href="css/acc.css">
        <link rel="stylesheet" type="text/css" href="css/footer.css">
	<meta charset="utf-8">
</head>
<body>
	<div id="wrapper">
		<?php headerAndSearchCode();?>
		<aside id="main_aside">
			<?php infoAsideCode(); ?>
		</aside>

		<section id="main_section" align="center">
				<?php infoCode($row); ?>
		</section>
		<?php footerCode(); ?>
	</div>
</body>
</html>
<?php mysqli_close($connect); ?>