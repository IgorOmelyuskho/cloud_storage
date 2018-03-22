<?php
	session_start();//Нельзя чтоб были пробелы до начала кода
	
	//Если есть перемення username в сесии и она не равна ''
	if (isset($_SESSION['username']) === true && $_SESSION['username'] !== '') {	
		if (isset($_SESSION['fullPath']) === false) {
			
		}	
	}
	else {
		echo '<script type="text/javascript">
		window.location = "login.php"
		</script>'; 
		exit();
	}
	
	if (isset($_POST['savefile']) && $_POST['savefile'] !== '' && isset($_POST['pathtofile']) && $_POST['pathtofile'] !== '') {
		if (isset($_POST['value']) === false){
			echo 'Not valid query';
			exit();
		}
		$fp = fopen($_POST['pathtofile'], 'w'); // Открываем файл в режиме записи 
		$test = fwrite($fp, $_POST['value']); // Запись в файл
		if ($test === false){
			echo 'Error writing to file';
			exit();
		} 
		fclose($fp); //Закрытие файла
		echo 'success';
	}
	else {
		echo 'error in zipfile.php';
	}
	
	
?>			