<?php
	session_start();
	//var_dump($_SESSION);
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Sign Up</title>
		<meta name="description" content="Site Name">	
		<link href="login.css" rel="stylesheet">
	</head>
	<body>
		<?php
			//Если была нажата кнопка submit
			if ( isset($_POST['submit']) ) {
				$errors = array();
				
				if ( trim($_POST['username'] === '') ) {
					$errors[] = 'Enter username';
				}
				if ( trim($_POST['password'] === '') ) {
					$errors[] = 'Enter password';
				}
				if ( trim($_POST['repeatpassword'] === '') ) {
					$errors[] = 'Enter Pe-password';
				}		
				if ($_POST['repeatpassword'] !== $_POST['password']) {
					$errors[] = 'Passwords do not match';
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
				
				//Проверим есть ли уже пользователь с таким именем
				$query = mysqli_query($connection, 'SELECT username FROM users'); 
				while($row = mysqli_fetch_array($query)) {	
					if ($row['username'] === $_POST['username']) {
						$errors[] = 'User with that name already exists';
						mysqli_close($connection);
                        break;
					}			
				}
				
				//Если в массиве $errors нет ощибок пробуем добавить нового пользователя в базу данных
                if (empty($errors)) {
				    $username = $_POST['username'];
					$password = $_POST['password'];
					date_default_timezone_set('Europe/Kiev');
					//$date = date('Y-m-d'); 
					$dateSignup = date('Y-m-d H:i:s');
					$queryInsert = mysqli_query($connection, "INSERT INTO users (username,password,datesignup) VALUES ('$username','$password','$dateSignup')");					
					echo mysqli_error($connection).'<br>';
					mysqli_close($connection);	
					//Если не получилось добавить нового пользователя в базу данных
					if ($queryInsert === false) {
						echo '<div style="color:red; font-size:30px">'.'Could not add a new user to the database'.'</div><hr>';
						exit();
					}
					//Если регистрация прошла успешно 
					$_SESSION['username'] = $username;
					$_SESSION['password'] = $password;
					//$_SESSION['datesignup'] = $dateSignup;
					
					$userDir = mkdir("users/$username", 0700);
					$tempDir = mkdir("users/$username/$username".'_temp', 0700);
					if ($userDir === false || $tempDir === false) {
						echo 'Cannot create dir'.'<br>';
					} 
					else {
						$_SESSION['userpath'] = getcwd().'\\users\\'.$username;//"C:\OSPanel\domains\cloudstorage\users\userx"
						$_SESSION['fullpath'] = getcwd().'\\users\\'.$user['username'];
					}
					
					echo '<div style="color:green; font-size:30px">'.'Registration on the site was successful, follow the "Log In" link'.'</div><hr>';
				} 
				else {
					//Если в массиве $errors есть ошибки то выведем первую 
					echo '<div style="color:red; font-size:30px; text-align:center">'.array_shift($errors).'</div><hr>';
				}				
			}
		?>
		<form action='signup.php' method='POST'>
			<h1>Sign Up</h1>
			<div id='divinform'>
				<input id='username' name='username' type='text' placeholder='Username' value="<?php echo @$_POST['username'];?>">
				<input id='password' name='password' type='password' placeholder='Password' value="<?php echo @$_POST['password'];?>">
				<input id='repeatpassword' name='repeatpassword' type='password' placeholder='Re-Password' value="<?php echo @$_POST['repeatpassword'];?>">
				<input id='submit' name='submit' type='submit' value='Sign Up'>
				<a href='login.php'>Log In</a>		
			</div>	
		</form>
	</body>
</html>