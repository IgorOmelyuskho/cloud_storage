<?php
	session_start();//Нельзя чтоб были пробелы до начала кода
	
	//Если нету переменной fullpath в сесии или она равна ''
	if (isset($_SESSION['fullpath']) === false || $_SESSION['fullpath'] === '') {
		echo '<script type="text/javascript">
		window.location = "login.php"
		</script>';
		exit();
	}
	//Если пришли все необходимые данные
	if (isset($_POST['oldname']) && $_POST['oldname'] !== '' && isset($_POST['newname']) && $_POST['newname'] !== '') {
		$oldPath = $_SESSION['fullpath'].'\\'.$_POST['oldname'];
		$newPath = $_SESSION['fullpath'].'\\'.$_POST['newname'];
		$result = rename($oldPath, $newPath);
		if ($result === false) {
			echo 'error';
		}
		else {
			echo 'success';
		}
	}
	else {
		echo 'error2';
	}
?>

