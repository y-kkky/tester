<?php
session_start();
include 'includes/connect.php';
include 'includes/html_codes.php';

if(!isset($_SESSION['user_id'])){
	header("Location: prompt.php?x=10");
}

$query = "UPDATE users SET ";
$row = getinfo($connect);
if(isset($_POST['submit'])){
	if(isset($_POST['name'])&&!empty($_POST['name'])){
		$name = mysqli_real_escape_string($connect, $_POST['name']);
		$query.= "name='$name'";
		if(isset($_POST['email'])&&!empty($_POST['email'])){
			$email = mysqli_real_escape_string($connect, $_POST['email']);
			$query.= ",email='$email'";
				if(isset($_POST['class'])&&!empty($_POST['class'])){
					$class = mysqli_real_escape_string($connect, $_POST['class']);
					$query.= ",class='$class' ";
	}
	}
	}else{
		if(isset($_POST['email'])&&!empty($_POST['email'])){
			$email = mysqli_real_escape_string($connect, $_POST['email']);
			$query.= " email='$email'";
			if(isset($_POST['class'])&&!empty($_POST['class'])){
				$class = mysqli_real_escape_string($connect, $_POST['class']);
				$query.= ",class='$class' ";
		}
		}else{
			if(isset($_POST['class'])&&!empty($_POST['class'])){
				$class = mysqli_real_escape_string($connect, $_POST['class']);
				$query.= " class='$class' ";
			}
		}	
	}
	$query.= " WHERE user_id=".$_SESSION['user_id'];
	if(@mysqli_query($connect, $query)){
		header("Location: prompt.php?x=7");
	}else{
		header("Location: prompt.php?x=8");
	}
}
if(isset($_POST['submit2'])){
	if(isset($_POST['oldpass'])&&!empty($_POST['oldpass'])){
		$oldpass = mysqli_real_escape_string($connect, $_POST['oldpass']);
	}
	if(isset($_POST['newpass1'])&&!empty($_POST['newpass1'])){
		$newpass1 = mysqli_real_escape_string($connect, $_POST['newpass1']);
	}
	if(isset($_POST['newpass2'])&&!empty($_POST['newpass2'])){
		$newpass2 = mysqli_real_escape_string($connect, $_POST['newpass2']);
	}

	if($row['password']=='00p'.sha1(md5($oldpass))&&$newpass1==$newpass2){
		$newpass = '00p'.sha1(md5($newpass1));
		$query2 = "UPDATE users SET password='$newpass' WHERE user_id='".$_SESSION['user_id']."'";
		if(@mysqli_query($connect, $query2)){
			header("Location: prompt.php?x=7");
		}else{
			header("Location: prompt.php?x=8");
		}	
	}else{

	}
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Редагування аккаунта</title>
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

		<section id="main_section" align="center" style="">
				<?php changeInfoCode($row); ?>
		</section>
		<?php footerCode(); ?>
	</div>
</body>
</html>
<?php mysqli_close($connect); ?>