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
	if (isset($_POST['create']) && $_POST['create'] !== '' && isset($_POST['name']) && $_POST['name'] !== '') {
		$path = $_SESSION['fullpath'].'\\'.$_POST['name'];
		if ($_POST['create'] === 'file') {
			$result = fopen($path, 'w');
			if ($result === false) {
				echo 'error while create new file';
			}
			else {
				echo 'success';
			}
		}
		else if ($_POST['create'] === 'folder') {
			$result = mkdir($path);
			if ($result === false) {
				echo 'error while create new folder';
			}
			else {
				echo 'success';
			}
		}
		else {
			echo 'error not valid $_POST[create]';
		}
	}
	else {
		echo 'error in newfileorfolder.php';
	}
?>

