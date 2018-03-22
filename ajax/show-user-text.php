<?php
	session_start();//Нельзя чтоб были пробелы до начала кода
?>

<?php
	//Если есть файл сессии для текущего запроса
	if ( isset($_SESSION['username']) ) {
		//Открываем соединение с сервером MySQL
		$connection = mysqli_connect('localhost', 'mybduser', 'Ry7nsWHnyq0FktFz');
		
		//Выбираем базу данных MySQL
		$db = mysqli_select_db($connection, 'mydatabase'); 		
		if (!$connection || !$db) {
			echo 'Error (!$connection || !$db)';
			exit();
		}
		$username = $_SESSION['username'];
		$query = mysqli_query($connection, "SELECT usertext FROM users WHERE username='$username'");
		$usertext = mysqli_fetch_array($query);
		echo 'Usertext: '.$usertext['usertext'];	
		//Если нету файла сессии для текущего запроса
	} 
	else {
		echo '<script type="text/javascript">
		window.location = "login.php"
		</script>';
		exit();
	}
?>		