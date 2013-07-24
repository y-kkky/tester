<html>
<head>
	<title>Секретна сторінка</title>
	<link rel="stylesheet" type="text/css" href="css/style_pascslka.css">
	<script type="text/javascript">
	function changeImg(){
		document.imgg.src = "/images/on.jpg";
		setTimeout(ddi, 100);
	}
        function ddi(){
            document.getElementById("secret").style.display='block';
        }

	</script>
    <meta charset="utf-8">
</head>
<body>
	<div id="main">
		<img src="/images/off.jpg" name="imgg" onmouseout="changeImg();" align="center"/>
	</div>
	<div id="secret" style="font-size: 35px;">
		<font color='white' face='Arial'>Ви стали одним з небагатьох щасливчиків, які потрапили на секретну сторінку сайту tester. Тепер ви, окрилені щастям і радістю, можете повернутся на <a href="index.php">головну сторінку</a>.</font>
	</div>
</body>
</html>