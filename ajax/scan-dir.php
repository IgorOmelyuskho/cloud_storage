<?php
	session_start();//Нельзя чтоб были пробелы до начала кода
	//var_dump($_SESSION);
	
	set_time_limit(5); 
	date_default_timezone_set('Europe/Kiev');
	
	//Если есть перемення username в сесии и она не равна ''
	if (isset($_SESSION['username']) === true && $_SESSION['username'] !== '') {
		//echo session_id().'<br>'; //id сесии
		
		if ( strlen($_SESSION['userpath']) > strlen($_SESSION['fullpath']) ) {
			echo 'error in scandir.php';
			exit();
		}
		
		showScanDir($_POST['path']);		
	}
	else{
		echo '<script type="text/javascript">
		window.location = "login.php"
		</script>';
		exit();
	}
	
	//Отображает список папок и файлов в папке $folder
	function showScanDir($folder) {
		$countFilesAndFolders = 0;
		$arr = array();
		
		//echo "<div class='dir fd back'>".'..'.'</div>';
		$obj = new FileAndFolder('folder', 'Back', '..', '..', '..', '..', '..', '..');
		array_push($arr, $obj);
		
		if ($_POST['direction'] === 'forward') {
			if ($folder !== '') {
				$_SESSION['fullpath'] .='\\'.$folder;
			}
		} 
		else if ($_POST['direction'] === 'back') {
			back();
		}
		else if ($_POST['direction'] === 'home') {
			$_SESSION['fullpath'] = $_SESSION['userpath'];
		}
		else if ($_POST['direction'] === 'first') {
			
		}
		
		$files = scandir($_SESSION['fullpath']);
 		if ($files === false) {
			echo 'scandir return false';
			back();
			exit();
		} 
		
		$tempFolder = $_SESSION['username'].'_temp';
		foreach($files as $file) {
			if ( ($file === '.') || ($file === '..') || ($file === $tempFolder) ) continue;	
			
			//$name = iconv("WINDOWS-1251", "UTF-8", $file);
			$name = $file;
			
			if (is_dir($_SESSION['fullpath'].'/'.$file)) { 
				$pathToFile = $_SESSION['fullpath'].'\\'.$name;
				$size = showSize($pathToFile, false);
				$info = new SplFileInfo($pathToFile);//Можно получать свойства файла через SplFileInfo()
				$extension = '-';
				$access = accessRights($pathToFile);
				$changed = date("Y-m-d H:i:s.", filemtime($pathToFile));
				$create = date("Y-m-d H:i:s.", filectime($pathToFile));
				$owner = /* posix_getpwuid */(fileowner($pathToFile));
				$obj = new FileAndFolder('folder', $name, $extension, $size, $access, $changed, $create, $owner);
				array_push($arr, $obj);
			}
			else {
				$pathToFile = $_SESSION['fullpath'].'\\'.$name;
				$size = showSize($pathToFile, false);
				$info = new SplFileInfo($pathToFile);
				$extension = $info->getExtension();
				$access = accessRights($pathToFile);
				$changed = date("Y-m-d H:i:s.", filemtime($pathToFile));
				$create = date("Y-m-d H:i:s.", filectime($pathToFile));
				$owner = /* posix_getpwuid */(fileowner($pathToFile));
				$obj = new FileAndFolder('file', $name, $extension, $size, $access, $changed, $create, $owner);
				array_push($arr, $obj);
			}
		}
		
		$fullPath = $_SESSION['fullpath'];
		$currentFolder = str_replace($_SESSION['userpath'], '', $fullPath);
		if ($currentFolder === '') {
			$currentFolder = '\\';	
		}
		array_push($arr, $currentFolder);
		echo json_encode($arr, JSON_UNESCAPED_UNICODE);
	}
	
	//Вызывается если нажали на папку '..'(тоесть хотим переместится на одну папку вверх)
	function back() {
		$strlength = strlen($_SESSION['fullpath']);
		$newstr = substr($_SESSION['fullpath'], 0, $strlength);
		$newstr2 = '';
		$exit = false;
		for ($i = $strlength - 1; $exit === false; $i--) {
			if ($newstr[$i] === '/' || $newstr[$i] === '\\') {
				$newstr2 = substr($newstr, 0, $i);
				$exit = true;
				if ( strlen($newstr2) >= strlen($_SESSION['userpath']) ) {
					$_SESSION['fullpath'] = $newstr2;
				}
			}
		}
	}
	
	//Возвращает размер файла в (B, KB, MB, GB, TB) #$precision количество цифр после точки
	function FBytes($bytes, $precision = 2) {
		$units = array('B', 'KB', 'MB', 'GB', 'TB');
		$bytes = max($bytes, 0);
		$pow = floor( ($bytes?log($bytes):0)/log(1024) );
		$pow = min($pow, count($units)-1);
		$bytes /= pow(1024, $pow);
		return round($bytes, $precision).' '.$units[$pow];
	}
	
	//Возвращает размер файла или папки
	//если $format=true или не указан, то вместо размера в байтах, возвращается строка, содержащая уже отформатированный размер - например не «10253», а «10.01 Kb».
	function showSize($f, $format = true) { 
        if($format) { 
			$size = showSize($f, false); 
			if($size <= 1024) return $size.' B'; 
			else if($size <= 1024*1024) return round($size/(1024),2).' KB'; 
			else if($size <= 1024*1024*1024) return round($size/(1024*1024),2).' MB'; 
			else if($size <= 1024*1024*1024*1024) return round($size/(1024*1024*1024),2).' GB'; 
			else if($size <= 1024*1024*1024*1024*1024) return round($size/(1024*1024*1024*1024),2).' TB'; 
			else return round($size/(1024*1024*1024*1024*1024),2).' PB'; 
		}
		else { 
			if( is_file($f) ) return filesize($f); 
			$size = 0; 
			$dh = opendir($f); 
			while( ($file = readdir($dh) ) !== false) { 
				if($file === '.' || $file === '..') continue; 
				if(is_file($f.'\\'.$file)) {
					$size += filesize($f.'\\'.$file); 
				}
				else {
					$size += showSize($f.'\\'.$file, false); 
				}
			} 
			closedir($dh); 
			return $size + filesize($f); 
		} 
	} 
	
	class FileAndFolder {
		function __construct($type, $name, $extension, $size, $access, $changed, $create, $owner) {
			$this->type = $type;
			$this->name = $name;
			$this->extension = $extension;
			$this->size = $size;
			$this->access = $access;
			$this->changed = $changed;
			$this->create = $create;
			$this->owner = $owner;
		}
		
		public $type = 'default';		
		public $name = 'default';
		public $extension = 'default';
		public $size = 'default';
		public $access = 'default';
		public $changed = 'default';
		public $create = 'default';
		public $owner = 'default';
	}
	
	//Отображение полных прав доступа
	function accessRights($file) {
		$perms = fileperms($file);
		
		switch ($perms & 0xF000) {
			case 0xC000: // сокет
			$info = 's';
			break;
			case 0xA000: // символическая ссылка
			$info = 'l';
			break;
			case 0x8000: // обычный
			$info = 'r';
			break;
			case 0x6000: // файл блочного устройства
			$info = 'b';
			break;
			case 0x4000: // каталог
			$info = 'd';
			break;
			case 0x2000: // файл символьного устройства
			$info = 'c';
			break;
			case 0x1000: // FIFO канал
			$info = 'p';
			break;
			default: // неизвестный
			$info = 'u';
		}
		
		// Владелец
		$info .= (($perms & 0x0100) ? 'r' : '-');
		$info .= (($perms & 0x0080) ? 'w' : '-');
		$info .= (($perms & 0x0040) ?
		(($perms & 0x0800) ? 's' : 'x' ) :
		(($perms & 0x0800) ? 'S' : '-'));
		
		// Группа
		$info .= (($perms & 0x0020) ? 'r' : '-');
		$info .= (($perms & 0x0010) ? 'w' : '-');
		$info .= (($perms & 0x0008) ?
		(($perms & 0x0400) ? 's' : 'x' ) :
		(($perms & 0x0400) ? 'S' : '-'));
		
		// Мир
		$info .= (($perms & 0x0004) ? 'r' : '-');
		$info .= (($perms & 0x0002) ? 'w' : '-');
		$info .= (($perms & 0x0001) ?
		(($perms & 0x0200) ? 't' : 'x' ) :
		(($perms & 0x0200) ? 'T' : '-'));
		
		return $info;
	}
?>																		