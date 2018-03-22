<?php
	$pathToSession = session_save_path();	
	$files = scandir($pathToSession);
	if ($files === false) {
		exit();
	}
	//Удаляет все файлы сессий с нулевым размером
	foreach($files as $file) {
		$basename = basename($file);
		$str1 = substr($basename, 0, 5);
		$size = filesize($pathToSession.'\\'.$file);
		if ($str1 === 'sess_' && $size === 0) {
			unlink($pathToSession.'\\'.$file);
		}
	}
?>

