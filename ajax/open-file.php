<?php
	//header('Content-Type: application/json; charset=utf-8');
	session_start();//Нельзя чтоб были пробелы до начала кода
?>

<?php
	//Если нету переменной fullpath в сесии или она равна ''
	if (isset($_SESSION['fullpath']) === false || $_SESSION['fullpath'] === '') {
		echo '<script type="text/javascript">
		window.location = "login.php"
		</script>';
		exit();
	}
	
	$fileName = $_POST['path'];
	$readonly = 'false';
	$fileText = 'txt';
	$pathToFile = 'path';
	if ($_POST['readonly'] === 'true') {
		$readonly = 'true';
	} 
	
	//$filetext = readfile($_SESSION['fullpath'].'\\'.$_POST['path']);	
	$pathToFile = $_SESSION['fullpath'].'\\'.$_POST['path'];
	
	if (filesize($pathToFile) === 0) {
		$fileText = ''; 
	}
	else {
		$fileText = file_get_contents($pathToFile, true);
		//$fileText = normJsonStr($fileText);
	}
	
	if ($fileText === false) {
		//$fileText = file_get_contents($pathToFile, true);	
		//$fileText = normJsonStr($fileText);
		$fileText = "Error: file_get_contents() $pathToFile return false";
	}
	
	$arr = array('filename' => $fileName, 'readonly' => $readonly, 'filetext' => $fileText, 'pathtofile' => $pathToFile);
	//$arr = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
	//Не работает если в файле русские буквы
	//echo json_encode($arr);
	echo json_encode($arr, JSON_UNESCAPED_UNICODE);
	//echo $arr['filename'].'<br>';
	//echo $arr['readonly'].'<br>';
	//echo $arr['filetext'].'<br>';
	//echo $arr['pathtofile'].'<br>';

	function normJsonStr($str) {//Нельзя использовать если в файле есть html код
		$str = preg_replace_callback('/\\\\u([a-f0-9]{4})/i', create_function('$m', 'return chr(hexdec($m[1])-1072+224);'), $str);
		return iconv('cp1251', 'utf-8', $str);
	}
?>


