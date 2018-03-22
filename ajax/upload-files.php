<?php
	session_start();//Нельзя чтоб были пробелы до начала кода
	
	//Если нету переменной fullpath в сесии или она равна ''
	if (isset($_SESSION['fullpath']) === false || $_SESSION['fullpath'] === '') {
		echo '<script type="text/javascript">
		window.location = "login.php"
		</script>';
		exit();
	}
	
	/* 	if (isset($_POST['upload']) === false) {
		echo 'error in uploadfile.php';
		exit();
	} */
	
	//echo $_FILES.'<br>';
	//echo var_dump($_FILES);
	
	for ($i = 0; $i < count($_FILES); $i++){
		if ( 0 < $_FILES["file$i"]['error'] ) {
			echo 'Error: '.$_FILES["file$i"]['error'].'<br>';
			exit();
		}
		else {
			move_uploaded_file($_FILES["file$i"]['tmp_name'], $_SESSION['fullpath'].'\\'.$_FILES["file$i"]['name']);			
		}
	}
	echo 'success';
		
?>