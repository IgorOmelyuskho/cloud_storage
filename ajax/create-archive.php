<?php
	session_start();//Нельзя чтоб были пробелы до начала кода
	
	//Если нету переменной fullpath в сесии или она равна ''
	if (isset($_SESSION['fullpath']) === false || $_SESSION['fullpath'] === '') {
		echo '<script type="text/javascript">
		window.location = "login.php"
		</script>';
		exit();
	}
	//echo var_dump($_POST);
	//Если пришли все необходимые данные
	if (isset($_POST['zip']) && $_POST['zip'] !== '' && isset($_POST['name']) && $_POST['name'] !== '') {
		if ($_POST['zip'] === 'file'){
			$filename = $_SESSION['fullpath'].'\\'.$_POST['name'];
			fileToZip($filename);
			echo 'success';
		}
		else if ($_POST['zip'] === 'folder') {
			$filename = $_SESSION['fullpath'].'\\'.$_POST['name'];
			folderToZip($filename, $filename.'.zip');
			echo 'success';
		}
		else {
			echo 'Not valid query';
			exit();
		}
	}
	else {
		echo 'error in zipfile.php';
	}
	
	function fileToZip($filename){
		$zip = new ZipArchive();		
 		if ($zip->open($filename.'.zip', ZipArchive::CREATE) !== true) {
			//exit("Невозможно открыть <$filename>\n");
			echo "Cannot open file: $filename";
			exit();
		} 
		$zip->addFile($filename, basename($filename));
		$zip->close();
	}
	
	function folderToZip($source, $destination){
		$zip = new ZipArchive();
		if ($zip->open($destination, ZIPARCHIVE::CREATE) !== true) {
			echo "Cannot open source: $source";
			exit();
		}
		
		$source = str_replace('\\', '/', realpath($source));
		
		if (is_dir($source) === true){
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
			
			foreach ($files as $file){
				$file = str_replace('\\', '/', $file);
				
				// Ignore "." and ".." folders
				if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
				continue;
				
				$file = realpath($file);
				$file = str_replace('\\', '/', $file);
				
				if (is_dir($file) === true){
					$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
					}else if (is_file($file) === true){
					$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
				}
			}
			}else if (is_file($source) === true){
			$zip->addFromString(basename($source), file_get_contents($source));
		}
		return $zip->close();
	}

?> 

