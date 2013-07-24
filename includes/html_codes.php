<?php
// Код для header и searchBar
function headerAndSearchCode(){
	$defaultText = htmlentities($_GET['keywords']='');

	echo '
		<header id="main_header">
			<div id="rightAlign">
	';
	//ссылки Войти Регистрация...
	topRightLinks();
	echo "
			</div>
			<a href=\"index.php\"><img src=\"images/logo.png\"></a>
		</header>
		
		<div id=\"top_search\">
			<form name=\"input\" action=\"tests.php\" method=\"get\">
				<input type=\"text\" id=\"keywords\" name=\"keywords\" size=\"80\" class=\"searchBox\" value=\"$defaultText\"> &nbsp;
				<select id=\"category\" name=\"category\" class=\"searchBox\">
	";
	//Все предметы должны быть сдесь
	createCategoryList();
	echo '
			</select>
			<input type="submit" value="Пошук" class="button" style="width: 100px;"/>
		</form>
		</div>
	';
}

function topRightLinks(){
	global $connect;
	if(!isset($_SESSION['user_id'])){
		echo '<a href="register.php">Зареєструватися</a> | <a href="login.php">Вхід</a>';
	}else{
		$username = getinfo($connect);
		if(isset($username))
			echo $username['name']." | ";
		echo '<a href="acc.php">Мій акаунт</a> | <a href="logout.php">Вийти</a>';
	}
}

//Вибір предметів
function createCategoryList(){
	if(ctype_digit($_GET['category'])){$x=$_GET['category'];}else{$x=999;}
	echo "<option>Усі предмети</option>";
	$i = 0;
	while(1){
		if(numberToCategory($i)=="Немає"){
			break;
		}else{
			echo "<option value=\"$i\" ";
			if($i==$x){echo ' SELECTED ';}
			echo " >";
			echo numberToCategory($i);
			echo "</option>";
		}
		$i++;
	}
}

function createCategoryMenu(){
	$i = 0;
	while(1){
		if(numberToCategory($i)=="Немає"){
			break;
		}else{
			echo "<li><a href='tests.php?category=".$i."'>".numberToCategory($i)."</a></li>";
		}
		$i++;
	}	
}

//номер до вчителя
function numberToTeacher($n){
	global $connect;
	$query = "SELECT name FROM users WHERE user_id='$n'";
	if($result = @mysqli_query($connect, $query)){
		return mysqli_result($result, 0, 'name');
	}
}

function numberToCategory($n){
	switch ($n) {
		case 0:
			$cat = "Англійська мова";
			break;

		case 1:
			$cat = "Біологія";
			break;

		case 2:
			$cat = "Географія";
			break;

		case 3:
			$cat = "Етика";
			break;

		case 4:
			$cat = "Захист Вітчизни";
			break;

		case 5:
			$cat = "Інформатика";
			break;

		case 6:
			$cat = "Історія";
			break;

		case 7:
			$cat = "Математика";
			break;

		case 8:
			$cat = "Музика";
			break;	

		case 9:
			$cat = "Право";
			break;

		case 10:
			$cat = "Санітарія";
			break;

		case 11:
			$cat = "Світова література";
			break;

		case 12:
			$cat = "Укр.мова і література";
			break;

		case 13:
			$cat = "Фізика";
			break;

		case 14:
			$cat = "Фізкультура";
			break;

		case 15:
			$cat = "Хімія";
			break;

		case 16:
			$cat = "Художня культура";
			break;

		case 17:
			$cat = "II іноземна мова";
			break;
		
		default:
			$cat = "Немає";
			break;
	}
	return $cat;
}

//footer
function footerCode(){
	echo '
		<footer id="main_footer">
		<div class="bar"/>
		<table width="100%">
		<tbody>
		<tr>
			<td align="center">© 2013. Розробили Круковський Ярослав | Петраков Данило</td>
			<a href="#" id="toTop" onclick="incr();">ВГОРУ</a>
			<td align="right"><a href="feedback.php">Зворотний зв`язок</a>&nbsp;|
                        <a href="rules.php">Правила сайту</a></td>
		</tr>
		</tbody>
		</table>
		</footer>
';
}

// Профиль пользователя
function changeInfoCode($info){
	if($info['role']!=1){
		echo "<div id=\"userinfo\" style='text-align: left; font-size: 16px;'><br><br>
				Ваше ім'я: ".$info['name']."<br>
				Ваша пошта: ".$info['email']."<br>
				Ваш клас: ".$info['class']."<br>
			</div><br><br><br>
			<label style='width: 800px; text-align: left; font-size: 30px;'>Введіть нову інформацію (тількі потрібну)</label><br><br><br><br>
			<form action=\"acc_update.php\" method='POST' style='text-align: left;'>
				<table>
				<tr><td width=150px>Ім'я: </td><td><input type=\"text\" name='name'/></td></tr>
				<tr><td>Пошта: </td><td><input type=\"text\" name='email'/></td></tr>
				<tr><td>Клас: </td><td><input type=\"text\" name='class'/></td></tr>
				</table>
				<br><br><input type=\"submit\" value=\"Готово\" name=\"submit\" class=\"button\"/></form><br><br>
				<form action=\"acc_update.php\" method='POST' style='text-align: left;'>
				<table>
				<tr><td width=150px>Поточний пароль: </td><td><input type=\"password\" name='oldpass'/></td></tr>
				<tr><td>Новий пароль: </td><td><input type=\"password\" name='newpass1'/></td></tr>
				<tr><td>Повторіть пароль: </td><td><input type=\"password\" name='newpass2'/></td></tr>
				</table>
				<br><br><input type=\"submit\" value=\"Готово\" name=\"submit2\" class=\"button\"/></form>
		";
	}else if($info['role']==1){
		echo '<br><br><label style="width:800px; text-align: center;">При потребі редагування</label>';
		echo '<br><br><br><br><label style="width:800px; text-align: center;"> зверніться до адміністратора</label>';
	}
	
}
//Меню в профиле
function infoAsideCode(){
	echo "
	<h1>Акаунт</h1>
			<ul id=\"menu\">
				<li><a href=\"acc.php\">Мій акаунт</a></li>
			    <li><a href=\"acc_update.php\">Редагувати</a></li>
			</ul><br><br>
			<h1>Тестування</h1>
			<ul id=\"menu\">
				<li><a href=\"tests.php\">Всі тести</a></li>";
			if($_SESSION['role']!=0){echo "<li><a href=\"create_test.php\">Створити тест</a></li><li><a href=\"test_own.php\">Мої тести</a></li>";}
			echo '</ul><br>';
	;
}
//Изменть профиль
function infoCode($info){
	if($info['role']==0){
        if($info['class']==''){
            echo "<script>
                alert('Будь ласка, заповніть поле `клас` у форматі XY, де Х це номер класу, а У - це літера. Наприклад: 1А, 11Г.');
                window.location = '/acc_update.php';
            </script>";
        }
	echo "<div id=\"userinfo\"><br><br>
				<label>Ім'я: </label>
				<h1 align='left'>".$info['name']."</h1><br><br>
				<label>Логін: </label>
				<h1 align='left'>".$info['username']."</h1><br><br>
				<label>Пошта: </label>
				<h1 align='left'>".$info['email']."</h1><br><br>
				<label>Клас: </label>
				<h1 align='left'>".$info['class']."</h1>
			</div>";
		}else if($info['role']==1){
			echo "<div id=\"userinfo\"><br><br>
				<label>Ім'я: </label>
				<h1 align='left'>".$info['name']."</h1><br><br>
				<label>Логін: </label>
				<h1 align='left'>".$info['username']."</h1><br><br>
				<label>Предмет: </label>
				<h1 align='left'>".$info['subject']."</h1>
			</div>";
	} else if($info['role']==2){
		echo "<div id=\"userinfo\"><br><br>
				<label>Ім'я: </label>
				<h1 align='left'>".$info['name']."</h1><br><br>
				<label>Логін: </label>
				<h1 align='left'>".$info['username']."</h1><br><br><br><br><br>
				<label style='width: 500px;'>Адміністратор сайту</label>
			</div>";
	}
}

//Создать варианты для 1 типа
function createVariants1($n){
	for ($i=1; $i <= $n ; $i++) {
		echo "<font size=4 color=white>".$i.".</font> <input type='text' name='variant".$i."' style='padding: 1px; width: 100px;'/><br>";
	}
}

//Создать варианты для 2 типа
function createVariants2($n){
	echo "<div class=\"field\">
			<label>Введіть питання: </label>
			<input type=\"text\" name=\"question\" style=\"padding: 5px; width: 600px;\"/><br><br>
		</div>
	<div class=\"field\">
			<label style='width: 500px;'>Введіть інформацію для першого стовпчика: </label><br>
		</div><div class='field'>";
	for ($b=1; $b <= $n ; $b++) {
		echo '<font size=4 color=white>'.$b.'.</font> <input type="text" name="1_variant'.$b.'" style="padding: 1px; width: 100px;"/><br>';
	}
	echo "</div><br><br><div class=\"field\">
			<label style='width: 500px;'>Введіть інформацію для другого стовпчика: </label><br>
		</div><div class='field'>";
	for ($z=1; $z <= $n ; $z++) {
		echo '<font size=4 color=white>'.$z.'.</font> <input type="text" name="2_variant'.$z.'" style="padding: 1px; width: 100px;"/><br>';
	}
	echo '</div>';
}

//Создать вопрос по типу и количеству вариантов
function createQuestion($type, $variant_num){
	if($type==1){
		echo "
		<div class=\"field\">
			<label>Введіть питання: </label>
			<input type=\"text\" name=\"question\" style=\"padding: 5px; width: 600px;\"/><br><br>
		</div>
		<div class=\"field\">";
		createVariants1($variant_num);
		echo "</div>
		<div class=\"field\">
			<label style=\"width: 300px;\">Номер правильної відповіді: </label>
			<input type=\"text\" name=\"counter\" style=\"padding: 5px; width: 50px;\"/><br><br>
		</div>
		";
	}else if($type==2){
		createVariants2($variant_num);
	}else if($type==3){
		echo "
		<div class=\"field\">
			<label style='width: 500px; text-align: left;'>Введіть текст задачі: </label>
			<textarea name=\"question\" style=\"padding: 5px; width: 600px; height: 100px;\"></textarea><br><br>
		</div>
		<div class=\"field\">
			<label style=\"width: 300px;\">Введіть правильну відповідь: </label>
			<input type=\"text\" name=\"right\" style=\"padding: 5px; width: 150px;\"/><br><br>
		</div>
		";
	}
}

//Показать вопрос для редактирования
function redQuestion($question_id){
	global $connect;
	$question = array();
	$query = "SELECT * FROM questions WHERE question_id='$question_id'";
	if($result = mysqli_query($connect, $query)){
		while($row = mysqli_fetch_assoc($result)){
			$question[] = $row;
		}
	}
	
	$type = $question[0]['type'];
	$variant_num = $question[0]['variant_num'];

	//Вытаскиваем варианты
	if($type!=3){
		$query_var = "SELECT variant FROM variants WHERE question_id='$question_id' ORDER BY variant_id";
			if($result_var = mysqli_query($connect, $query_var)){
				while ($row1 = mysqli_fetch_assoc($result_var)) {
					$variants[] = $row1['variant'];
				}
			}
		}
	
	if($type==1){
		echo "
		<div class=\"field\">
			<label>Введіть питання: </label>
			<input type=\"text\" value='".$question[0]['body']."' name=\"question\" style=\"padding: 5px; width: 600px;\"/><br><br>
		</div>
		<div class=\"field\">";
		//
		//createVariants1($variant_num);
		for ($i=1; $i <= $variant_num ; $i++) {
			echo "<font size=4 color=white>".$i.".</font> <input type='text' value='{$variants[$i-1]}' name='variant".$i."' style='padding: 1px; width: 100px;'/><br>";
		}
		//
		echo "</div>
		<div class=\"field\">
			<label style=\"width: 300px;\">Номер правильної відповіді: </label>
			<input type=\"text\" name=\"counter\" value='".$question[0]['right_answer']."' style=\"padding: 5px; width: 50px;\"/><br><br>
		</div>
		        <div class=\"field\">
                    <label>Введіть ціну завдання: </label>
                    <input type=\"text\" id=\"price\" name=\"price\" value=\"{$question[0]['price']}\"/>
                </div>
		";
		echo '<input type="submit" name="submit1" value="Готово" class="button"/>';
	}else if($type==2){
		
			echo "<div class=\"field\">
				<label>Введіть питання: </label>
				<input type=\"text\" name=\"question\" value='".$question[0]['body']."' style=\"padding: 5px; width: 600px;\"/><br><br>
			</div>
		<div class=\"field\">
				<label style='width: 500px;'>Введіть інформацію для першого стовпчика: </label><br>
			</div><div class='field'>";
		for ($b=1; $b <= $variant_num ; $b++) {
			echo '<font size=4 color=white>'.$b.'.</font> <input type="text" value="'.$variants[$b-1].'" name="1_variant'.$b.'" style="padding: 1px; width: 100px;"/><br>';
			unset($variants[$b-1]);
		}
		echo "</div><br><br><div class=\"field\">
				<label style='width: 500px;'>Введіть інформацію для другого стовпчика: </label><br>
			</div><div class='field'>";
		for ($z=1; $z <= $variant_num ; $z++) {
			echo '<font size=4 color=white>'.$z.'.</font> <input type="text" value="'.$variants[$z+$variant_num-1].'" name="2_variant'.$z.'" style="padding: 1px; width: 100px;"/><br>';
		}
		echo '</div>';
        echo "<div class=\"field\">
                    <label>Введіть ціну завдання: </label>
                    <input type=\"text\" id=\"price\" name=\"price\" value=\"{$question[0]['price']}\"/>
                </div>";
		echo '<input type="submit" name="submit2" value="Готово" class="button"/>';
	}else if($type==3){
		echo "
		<div class=\"field\">
			<label style='width: 500px; text-align: left;'>Введіть текст задачі: </label>
			<textarea name=\"question\" style=\"padding: 5px; width: 600px; height: 100px;\">".$question[0]['body']."</textarea><br><br>
		</div>
		<div class=\"field\">
			<label style=\"width: 300px;\">Введіть правильну відповідь: </label>
			<input type=\"text\" name=\"right\" value='".$question[0]['right_answer']."' style=\"padding: 5px; width: 150px;\"/><br><br>
		</div>
		<div class=\"field\">
                    <label>Введіть ціну завдання: </label>
                    <input type=\"text\" id=\"price\" name=\"price\" value=\"{$question[0]['price']}\"/>
                </div>
		";
		echo '<input type="submit" name="submit3" value="Готово" class="button"/>';
	}
	
}

//Функция для показа теста
function showTest($arr){
	if($arr!='Немає тестів'){
		echo "<table><tr><th>Назва тесту</th><th>Предмет</th><th>Вчитель, що створив тест</th><th>Статус</th></tr>";
		foreach ($arr as $key => $value) {
			if($value['status']==0)
				$status = 'Активний';
			else
				$status = 'Неактивний';
			echo "
			<tr>
				<td>".$value['name']."</td>
				<td>".$value['subject']."</td>
				<td>".numberToTeacher($value['teacher_id'])."</td>
				<td>".$status."</td>
				<td><a href='test_solve.php?test_id=".$value['test_id']."'><input type='submit' onclick=\"return confirm('Ви впевнені, що хочете почати? У вас є одна спроба для розв`язання тесту. Під час розв`язання ні в якому разі не оновлюйте і не закривайте сторінку!!!');\" class='button' value='Розв`язати'/></a></td>
				<td><a href='test_stat.php?test_id=".$value['test_id']."'><input type='submit' class='button' value='Статистика'/></a></td>
			</tr>
			<tr>
				<td colspan=6><hr></td>
			</tr>
			";
		}
		echo "</table>";
	}else{
		echo "<br><br><label>Тестів немає</label>";
	}
}
//Функция для показа теста РЕДАКТИРОВАНИЕ
function showTestRed($arr){
	if($arr!='Немає тестів'){
		echo "<table><tr><th>Назва тесту</th><th>Предмет</th><th>Вчитель, що створив тест</th></tr>";
		foreach ($arr as $key => $value) {
			echo "
			<tr>
				<td>".$value['name']."</td>
				<td>".$value['subject']."</td>
				<td>".numberToTeacher($value['teacher_id'])."</td>
				<td><a href='test_red.php?test_id=".$value['test_id']."'><input type='submit' class='button' value='Редагувати'/></a></td>
			</tr>
			<tr>
				<td colspan=5><hr></td>
			</tr>
			";
		}
		echo "</table>";
	}else{
		echo "<br><br><label>Тестів немає</label>";
	}
}

function showClasses($test_id){
	global $connect;
	$query_first = "SELECT DISTINCT user_id FROM tests_stat WHERE test_id='$test_id'";
	if ($res = mysqli_query($connect, $query_first)) {
		while ($row = mysqli_fetch_assoc($res)) {
			$user_ids[] = $row['user_id'];
		}
	}
	foreach ($user_ids as $value) {
		$query = "SELECT DISTINCT class FROM users WHERE user_id='$value' ORDER BY class";
		if($result = mysqli_query($connect, $query)){
			while ($row = mysqli_fetch_assoc($result)) {
				if ($row['class']!='') {
					$classes[] = $row['class'];
				}
                $classes = array_unique($classes);
				
			}
		}
	}
	
	
	foreach ($classes as $value) {
		echo "<option name=".$value.">".$value."</option>";
	}
	return $user_ids;
}

function numberToType($n){
	switch ($n) {
		case 1:
			return 'Питання з варіантами';
			break;

		case 2:
			return 'З`єднай варіанти';
			break;

		case 3:
			return 'Задача';
			break;	

		default:
			# code...
			break;
	}
}

//Функция для показа теста
function showQuestList($questions){
	if($questions!='Нема питань'){
		$counterr = 1;
		echo "<table><tr><th>Номер</th><th>Запитання</th><th>Тип питання</th><th>Редагувати</th><th>Видалити</th></tr>";
		foreach ($questions as $key => $value) {
			echo "
			<tr>
				<td>".$counterr.".</td>
				<td>".$value['body']."</td>
				<td>".numberToType($value['type'])."</td>
				<td><a href='test_red_q.php?question_id=".$value['question_id']."'><input type='submit' class='button' value='Редагувати'/></a></td>
				<td><a href='test_red_del.php?question_id=".$value['question_id']."&test_id=".$value['test_id']."'><input type='submit' class='button' onClick=\"return confirm('Ви впевнені, що хочете видалити питання?')\" value='Видалити'/></a></td>
			</tr>
			<tr>
				<td colspan=5><hr></td>
			</tr>
			";
			$counterr++;
		}
		echo "</table>";
	}else{
		echo "<br><br><label>Питань немає</label>";
	}
}

//Функция для показа вопроса
function showQuestion($questions){
	global $connect;
			$i = 1;
		foreach ($questions as $key => $value) {
			$quest_id = $value['question_id'];
            $image1 = $value['image'];
			//Разделение по типу вопросов
			if($value['type']==1){	
				$variants = array();
				$query3 = "SELECT variant FROM variants INNER JOIN questions WHERE variants.question_id='$quest_id' AND questions.question_id='$quest_id' AND questions.type=1 ORDER BY RAND()";
					if($result3 = mysqli_query($connect, $query3)){
						while($row = mysqli_fetch_array($result3)){
							$variants[] = $row['variant'];
						}
					}	
				echo '
				<div id="field">
					'.$i.') '.$value['body'].'<br><br>';
                if(isset($image1)){
                    echo "<img src='{$image1}'/><br><br>";
                }
				foreach ($variants as $value) {
					echo "<input type='radio' name='f$quest_id' value='$value'>".' '.$value.'</input>&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				echo '</div><br><hr><br>';


			}else if($value['type']==2){	
			$variants2 = array();	
			$query4 = "SELECT variant FROM variants INNER JOIN questions WHERE variants.question_id='$quest_id' AND questions.type=2 ORDER BY variant_id";
					if($result4 = mysqli_query($connect, $query4)){
						while($row1 = mysqli_fetch_array($result4)){
							$variants2[$row1['variant']] = $row1['variant'];
						}
					}		
				echo '
				<div id="field">
					'.$i.') '.$value['body'].'<br><br>';
                if(isset($image1)){
                    echo "<img src='{$image1}'/><br><br>";
                }
				$dlina = count($variants2);
				$counter = 0;
				$counter2 = 0;
				$firstp = array();
				foreach ($variants2 as $value1) {
						$secondp = array();
						$firstp[$counter] = $value1;	
						if($counter+1>= $dlina/2){
							foreach (array_reverse($variants2) as $value2) {
								$secondp[$counter2] = $value2;
								if($counter2+1>=$dlina/2)
									break;
								$counter2++;
						}break;
					}
					$counter++;
				}
				$secondp = array_reverse($secondp);

				//перемешываем элементы массива
				shuffle($secondp);				

				for($b=0;$b<$dlina/2;$b++){

					echo ''.$firstp[$b].' ------ ';
					echo '<select name=s'.$quest_id.$b.' style=" display: inline-table; width: 200px; text-align: left;">';
					echo '<option disabled selected> оберіть відповідь </option>';
					foreach ($secondp as $value) {
							echo "<option>".$value.'</option>';	
						}
					echo '</select><br>';
				}
				echo '</div><br><hr><br>';
				}else if($value['type']==3){
				echo '
				<div id="field">
					'.$i.') '.$value['body'].'<br><br>';
                if(isset($image1)){
                    echo "<img src='{$image1}'/><br><br>";
                }
					echo "<input type='text' name='t$quest_id'></input>";
				echo '</div><br><br><hr><br>';
			}
			$i = $i+1;
		}
}

//Проверяет тест на активность
function isActive($test_id){
	global $connect;
	$query = "SELECT status FROM tests WHERE test_id='$test_id'";
	if($result = mysqli_query($connect, $query)){
		if(mysqli_result($result,0,'status')==0){
			return true;
		}else{
			return false;
		}
	}
}
//Тесты
function getTestInfo(){
	$query = "SELECT * FROM tests WHERE test_id=".$_SESSION['user_id'];
	$result = mysqli_query($connect, $query);
	$row = mysqli_fetch_array($result);
	return $row;
}

//Счетчик теста
function getCounters($connect){
	$query = "SELECT * FROM counters";
	$result = mysqli_query($connect, $query);
	$row = mysqli_fetch_array($result);
	return $row;
}

function getinfo($connect){
	$query = "SELECT * FROM users WHERE user_id=".$_SESSION['user_id'];
	$result = mysqli_query($connect, $query);
	$row = mysqli_fetch_array($result);
	return $row;
}

//Создание отсутствующей функции-аналога mysql_result()
function mysqli_result($res, $row, $field=0) { 
     $res->data_seek($row); 
     $datarow = $res->fetch_array(); 
     return $datarow[$field]; 
}
?>