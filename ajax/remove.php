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
	if (isset($_POST['remove']) && $_POST['remove'] !== '' && isset($_POST['name']) && $_POST['name'] !== '') {
		$path = $_SESSION['fullpath'].'\\'.$_POST['name'];
		if ($_POST['remove'] === 'file') {
			$result = unlink($path);
			if ($result === false){
				echo 'error while deleting file';
			}
			else {
				echo 'success';
			}
		}
		else if ($_POST['remove'] === 'folder') {
			$result = rrmdir($path);
			if ($result === false) {
				echo 'error while deleting folder';
			}
			else {
				echo 'success';
			}
		}
		else {
			echo 'error: not valid $_POST[remove]';
		}
	}
	else {
		echo 'error in remove.php';
	}
	
	function rrmdir($src) {
		$dir = opendir($src);
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file !== '.' ) && ( $file !== '..' )) {
				$full = $src . '/' . $file;
				if ( is_dir($full) ) {
					rrmdir($full);
				}
				else {
					unlink($full);
				}
			}
		}
		closedir($dir);
		rmdir($src);
		return true;
	}
?>

