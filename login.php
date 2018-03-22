<?php
	session_start();//Нельзя чтоб были пробелы до начала кода
	//var_dump($_SESSION);
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Log In</title>
		<meta name="description" content="Site Name">	
		<link href="login.css" rel="stylesheet">
	</head>
	<body>
		<?php
			//Если была нажата кнопка submit
			if (isset($_POST['submit'])) {
				$errors = array();	
				
				if ( trim($_POST['username'] === '') ) {
					$errors[] = 'Enter username';
				}
				if ( trim($_POST['password'] === '') ) {
					$errors[] = 'Enter password';
				}					
				
				//Открываем соединение с сервером MySQL
				$connection = mysqli_connect('localhost', 'mybduser', 'Ry7nsWHnyq0FktFz');
				if (!$connection) {
					//Если не соединились с сервером MySQL
					$errors[] = 'Did not connect to the MySQL server';
					//mysqli_close($connection);
					exit(mysqli_error());					
				}
				
				//Выбираем базу данных MySQL
				$db = mysqli_select_db($connection, 'mydatabase');
				if (!$db) {
					//Если не выбрали бвзу данных MySQL
					$errors[] = 'Did not choose a database of MySQL data';
					//mysqli_close($connection);
					exit(mysqli_error());
				}
				
				if ($connection && $db) {
					//Устанавливаем кодировку клиента
					mysqli_set_charset($connection, 'utf8');
				}		
				
				//Проверим есть ли пользователь с таким именем
				$query = mysqli_query($connection, 'SELECT * FROM users'); 
				$userNameExists = false;
				$user = null;
				while($row = mysqli_fetch_array($query)) {	
					if ($row['username'] === $_POST['username']) {
						$userNameExists = true;	
						$user = $row;
						break;
					}	
				}
				
				//Если в цыкле не нашли пользователя с таким именем
				if ($userNameExists === false) {
					$errors[] = 'No user with that name';
					mysqli_close($connection);
				}
				//Если в цыкле нашли пользователя с таким именем, то проверим совпадают ли пароли 
				else {
					//Если пароли не совпадают
					if ($user['password'] !== $_POST['password']) {
						$errors[] = 'Incorrect password';
						mysqli_close($connection);
					}
				}
				
				//Если в массиве $errors нет ощибок(есть нужное имя и пароли совпадают),то перенаправим 
				//пользователя на страницу userpage.php
				if (empty($errors)) {	
					//Создадим в сессии переменные
					$_SESSION['username'] = $user['username'];
					$_SESSION['password'] = $user['password'];
					//$_SESSION['datesignup'] = $user['datesignup'];
					$_SESSION['userpath'] = getcwd().'\\users\\'.$user['username'];//"C:\OSPanel\domains\cloudstorage\users\userx"
					$_SESSION['fullpath'] = getcwd().'\\users\\'.$user['username'];
					//var_dump($_SESSION); //переменные сессии хранятся на сервере
					session_write_close();	
					
					//Перенаправим пользователя на страницу userpage.php
					//header('Location: '.'userpage.php'); //Не работает
					//include 'userpage.php';//Не работает		
					echo '<script type="text/javascript">
					window.location = "userpage.php"
					</script>';
					mysqli_close($connection);
					exit();
				}
				//Если в массиве $errors есть ошибки то выведем первую
				else {
					echo '<div style="color:red; font-size:30px; text-align:center">'.array_shift($errors).'</div><hr>';
					//mysqli_close($connection);
				}				
			}
		?>
		
		<form action='login.php' method='POST'>
			<h1>Log In</h1>
			<div id='divinform'>
				<input id='username' name='username' type='text' placeholder='Username' value="<?php echo @$_POST['username'];?>">
				<input id='password' name='password' type='password' placeholder='Password' value="<?php echo @$_POST['password'];?>">
				<input id='submit' name='submit' type='submit' value='Log In'>
				<a href='signup.php'>Sign Up</a>		
			</div>			
		</form>
		
	</body>
</html>		