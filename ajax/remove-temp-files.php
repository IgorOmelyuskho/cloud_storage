<?php
	$user_folders = scandir('users');
	if ($user_folders === false) {
		exit('error1');
	}
	
	foreach($user_folders as $user_folder) {
		if ($user_folder === '.' || $user_folder === '..') continue;
		removeFilesInTempFolder($user_folder);
	}
	
	function removeFilesInTempFolder($user_folder) {
		$path_to_temp_folder =  'users\\'.$user_folder.'\\'.$user_folder.'_temp';
		
		//echo $path_to_temp_folder;
		$temp_folder = scandir($path_to_temp_folder);
		
		foreach($temp_folder as $temp_file) {
			if ($temp_file === '.' || $temp_file === '..') continue;
			unlink($path_to_temp_folder.'\\'.$temp_file);
			echo $path_to_temp_folder.'\\'.$temp_file;
		}
	}
?>

