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
	if (isset($_POST['createcopy']) && $_POST['createcopy'] !== '' && isset($_POST['name']) && $_POST['name'] !== '') {
		if ($_POST['createcopy'] === 'file'){
			$name = $_SESSION['fullpath'].'\\'.$_POST['name'];
			createFileCopy($name);
			echo 'success';
		}
		else if ($_POST['createcopy'] === 'folder') {
			$name = $_SESSION['fullpath'].'\\'.$_POST['name'];
			createFolderCopy($name);
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
	
	function createFileCopy($file){
		//echo $file.'<br>';
		$basename = basename($file);//$_POST['name']
		$newname = 'copy - '.$basename;
		//echo $basename.'<br>';
		//echo $newname.'<br>';
		$newfile =  str_replace($basename, $newname, $file);
		//echo $file;
		if (!copy($file, $newfile)) {
			echo "Canot copy file $file...\n";
			exit();
		}
	}
	
	function createFolderCopy($folder){
		$basename = basename($folder);//$_POST['name']
		$newname = 'copy - '.$basename;
		$newfolder = str_replace($basename, $newname, $folder);
		full_copy($folder, $newfolder);
	}	
	
	function full_copy($source, $target) {
		if (is_dir($source))  {
			@mkdir($target);
			$d = dir($source);
			while (false !== ($entry = $d->read())) {
				if ($entry === '.' || $entry === '..') continue;
				full_copy("$source/$entry", "$target/$entry");
			}
			$d->close();
		}
		else copy($source, $target);
	}
?> 

