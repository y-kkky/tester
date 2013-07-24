<?php
session_start();
include 'includes/connect.php';
include 'includes/html_codes.php';


if(isset($_POST['submit'])){
	$error = array();

	//username
	if(empty($_POST['username'])){
	}else if(ctype_alnum($_POST['username'])){
		$username = $_POST['username'];
	}else{
	}

    //password
    if(empty($_POST['password'])){
    }else if(preg_match("/^([-_:a-zA-Z0-9]{5,})+$/", $_POST['password'])){
    	$password = mysqli_real_escape_string($connect, $_POST['password']);
    	$password_hash = '00p'.sha1(md5($password));
    }else{
    }

    if(empty($error)){
    	//код входа
    	$result = mysqli_query($connect, "SELECT * FROM users WHERE username='$username' AND password='$password_hash' AND role=2") or die(mysqli_error($connect));
    	if(mysqli_num_rows($result)==1){
    		while($row = mysqli_fetch_array($result)){
    			$_SESSION['user_id'] = $row['user_id'];
    			$_SESSION['role'] = $row['role'];
    			header('Location: acc.php');
    		}
    	}else{
    	}
    }else{
    	}
    }


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> <html xmlns="http://www.w3.org/1999/xhtml"> <head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> <title>ADMIN</title> <link rel="shortcut icon" href="/favicon.ico" /> <link rel="stylesheet" type="text/css" href="admin.css" /></head><body><div style="position: absolute; top: 0; width: 100%; line-height: 140px; background-color: #660000; font-size: 72pt; font-family: Arial; color: #CC3333;"> <span style="margin-left: 24px">ADMIN ZONE</a> </div> <div style="background-color: red; position: absolute; top: 45%; left: 50%; margin-top: -60px; margin-left: -256px;"> <form action="admin.php" method="post" name="form" id="form" enctype="multipart/form-data" style="margin: 0;"> <table align="center" valign="middle" width="500px" cellpadding="10px" cellspacing="0" border="0"> <tr><td><span style="font-size: 16pt">Login:</span></td><td width="90%"><input type="text" name="username" id="username" value="" size="25" style="height: 34px; width: 99%; font-size: 16pt;" /></td></tr> <tr><td><span style="font-size: 16pt">Pass:</span></td><td width="90%"><input type="password" name="password" id="password" value="" size="25" style="height: 34px; width: 99%; font-size: 16pt;" /></td></tr> <tr><td colspan="2"><input type="submit" name="submit" value="OK" style="height: 34px; width: 100%; font-size: 16pt;" /></td></tr> </table> </form></div> <br /></body></html>
<?php mysqli_close($connect); ?>