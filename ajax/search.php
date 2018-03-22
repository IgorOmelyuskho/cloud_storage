<?php
	session_start();
	
	date_default_timezone_set('Europe/Kiev');
	
	$foundObjects = array();
	
	//Если есть перемення username в сесии и она не равна ''
	if (isset($_SESSION['username']) === true && $_SESSION['username'] !== '') {
		if (isset($_POST['path']) && $_POST['path'] !== '' && isset($_POST['name']) && $_POST['name'] !== '') {
			if ($_POST['path'] === 'search-in-all-folders') {
				SearchFolderOrFile($_SESSION['userpath'], $_POST['name']);
			}
			else if ($_POST['path'] === 'search-in-current-folder') {
				SearchFolderOrFile($_SESSION['fullpath'], $_POST['name']);
			}
			else {
				SearchFolderOrFile($_SESSION['userpath'].'\\'.$_POST['path'], $_POST['name']);
			}
		}
		else {
			exit();	
		}
	}
	else{
		echo '<script type="text/javascript">
		window.location = "login.php"
		</script>';
		exit();
	}	
	
	function SearchFolderOrFile($path, $searchName) { 
		global $foundObjects;
		$tempFolder = $_SESSION['username'].'_temp';
		$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
		foreach($objects as $name) {
			if (basename($name) === '.' || basename($name) === '..' || strpos($name, $tempFolder) > 0) continue;
			//echo 'NAME: '.$name;
			$str1 = ''.$name;
			$str2 = $_SESSION['userpath'].'\\';
			$str3 = str_replace($str2, '', $str1);
			$obj = new FoundFileOrFolder($_POST['name'], 'type', ''.$str3);
			if (basename($str3) === $searchName) {
				array_push($foundObjects, $obj);
			}
		} 
		echo json_encode($foundObjects, JSON_UNESCAPED_UNICODE);
	} 
	
	class FoundFileOrFolder {
		function __construct($name, $type, $path) {
			$this->name = $name;
			$this->type = $type;
			$this->path = $path;
		}
		
		public $name = 'default';
		public $type = 'default';		
		public $path = 'default';
	}
	
	
?>																							