<?php
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	session_start();//Нельзя чтоб были пробелы до начала кода
	
	//Если нету переменной fullpath в сесии или она равна ''
	if (isset($_SESSION['fullpath']) === false || $_SESSION['fullpath'] === '') {
		echo '<script type="text/javascript">
		window.location = "login.php"
		</script>';
		exit();
	}
	
	if (isset($_POST['download']) && $_POST['download'] !== '' && isset($_POST['name']) && $_POST['name'] !== '') {    
		header('Content-Disposition: attachment; filename='.basename($file));
		echo serverPathToURIPath($_SESSION['fullpath'].'\\'.$_POST['name']);
		//echo "window.open($path4)";
		exit();		
	} 
	else {
		echo 'error in uploadfile.php';
		exit();
	}
	
	//Преобразует путь к файлу на сервере(C:\OSPanel\domains\cloudstorage\users\user2\qwe.txt) к URI(http://cloudstorage/users/user2/qwe.txt)
	function serverPathToURIPath($serverPath){
		$path1 = str_replace('\\', '/', $serverPath);
		$serverName = $_SERVER['SERVER_NAME'];
		$pos = strpos($path1, $serverName);
		$path2 = substr($path1, $pos);
		return str_replace($serverName, "http://$serverName", $path2);
	}
	
?>