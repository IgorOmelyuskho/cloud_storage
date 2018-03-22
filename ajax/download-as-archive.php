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
	
	if (isset($_POST['downloadasarchive']) && $_POST['downloadasarchive'] !== '' && isset($_POST['name']) && $_POST['name'] !== '') {    
		header('Content-Disposition: attachment; filename='.basename($file));
		
		if ($_POST['downloadasarchive'] === 'file') {
			$source = $_SESSION['fullpath'].'\\'.$_POST['name']; 
			$destination = $_SESSION['userpath'].'\\'.$_SESSION['username'].'_temp\\'.$_POST['name'].'.zip';
			downloadFileAsArchive($source, $destination);
		}
		else if ($_POST['downloadasarchive'] === 'folder') {
			$source = $_SESSION['fullpath'].'\\'.$_POST['name']; 
			$destination = $_SESSION['userpath'].'\\'.$_SESSION['username'].'_temp\\'.$_POST['name'].'.zip';
			downloadFolderAsArchive($source, $destination);
		}
		else {
			echo 'Not valid query';
			exit();
		}				
	} 
	else {
		echo 'error in uploadfile.php';
		exit();
	}
	
	//Преобразует путь к файлу на сервере(C:\OSPanel\domains\cloudstorage\users\user2\qwe.txt) к URI(http://cloudstorage/users/user2/qwe.txt)
	function serverPathToURIPath($serverPath) {
		$path1 = str_replace('\\', '/', $serverPath);
		$serverName = $_SERVER['SERVER_NAME'];
		$pos = strpos($path1, $serverName);
		$path2 = substr($path1, $pos);
		return str_replace($serverName, "http://$serverName", $path2);
	}
	
	function downloadFileAsArchive($source, $destination) {
		fileToZip($source, $destination);
		echo serverPathToURIPath($destination);
	}
	
	function downloadFolderAsArchive($source, $destination) {
		folderToZip($source, $destination);
		echo serverPathToURIPath($destination);
	}	
	
	function fileToZip($source, $destination) {
		$zip = new ZipArchive();		
 		if ($zip->open($destination, ZipArchive::CREATE) !== true) {
			echo "Cannot open file: $source";
			exit();
		} 
		$zip->addFile($source, basename($source));
		$zip->close();
	}
	
	function folderToZip($source, $destination) {
		$zip = new ZipArchive();
		if ($zip->open($destination, ZIPARCHIVE::CREATE) !== true) {
			echo "Cannot open source: $source";
			exit();
		}
		
		$source = str_replace( '\\', '/', realpath($source) );
		
		if (is_dir($source) === true) {
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
			
			foreach ($files as $file) {
				$file = str_replace('\\', '/', $file);
				
				// Ignore "." and ".." folders
 				if( in_array( substr($file, strrpos($file, '/')+1), array('.', '..') ) ) {
					continue;
				} 		

				$file = realpath($file);
				$file = str_replace('\\', '/', $file);
				
				if (is_dir($file) === true) {
					$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
				}
				else if (is_file($file) === true) {
					$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
				}
			}
		}
		else if (is_file($source) === true) {
			$zip->addFromString(basename($source), file_get_contents($source));
		}
		return $zip->close();
	}
?>			