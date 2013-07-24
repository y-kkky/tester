<?php
include "includes/connect.php";

$ip = $_SERVER['REMOTE_ADDR'];

if(isset($_GET['email'])&&preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_GET['email'])){
	$email = mysqli_real_escape_string($connect, $_GET['email']);
}

if(isset($_GET['key']) && (strlen($_GET['key'])==32) ){
	$key = mysqli_real_escape_string($connect, $_GET['key']);
}

if(isset($email) && isset($key)){
	$result = mysqli_query($connect, "SELECT * FROM tempusers WHERE (email='$email' AND activation='$key') LIMIT 1 ")
	or die(mysqli_error($connect));
	while ($row = mysqli_fetch_array($result)) {
		$user_id = mysqli_real_escape_string($connect, $row['user_id']);
		$username = mysqli_real_escape_string($connect, $row['username']);
		$name = $row['name'];
		$email = mysqli_real_escape_string($connect, $row['email']);
		$password = mysqli_real_escape_string($connect, $row['password']);

		//Защита пароля:
		$password_hash = '00p'.sha1(md5($password));
	}

	$result1 = mysqli_query($connect, "INSERT INTO users(user_id,username,name,email,password,role,ip) VALUES 
	('','$username','$name', '$email','$password_hash',0,'$ip')") or die(mysqli_error($connect));
	$result2  = mysqli_query($connect, "DELETE FROM tempusers WHERE user_id='$user_id'") or die(mysqli_error($connect));

	if(!$result1){
		echo "Неможливо активувати аккаунт. Зв'яжіться із адміністратором.";
	}else{
		header("Location: prompt.php?x=0");
	}
}else{
	echo "Сталося непередбачуване! Негайно зв'яжіться із адміністратором!";
}
?>
<?php mysqli_close($connect); ?>