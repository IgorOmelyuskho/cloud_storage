<?php
	session_start();//Нельзя чтоб были пробелы до начала кода
	
	//Если есть файл сесии для текущего пользователя
	if (count($_SESSION) > 0) {
		//echo 'Hi '.$_SESSION['username'].'!<br>';
		//var_dump($_SESSION); //переменные сессии хранятся на сервере
		session_write_close();
		//echo 'count($_SESSION) > 0<br>';
		//echo 'count: '.count($_SESSION).'<br>';
	}
	else {
		echo '<script type="text/javascript">
		window.location = "login.php"
		</script>';
		exit();
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>User Page</title>
		<meta name="description" content="Site Name">	
		<link href="userpage.css" rel="stylesheet">
		<link href="jquery-ui.css" rel="stylesheet" type="text/css"/>
		<script type='text/javascript' src='jquery-3.3.1.js'></script>	
		<script type='text/javascript' src='jquery-ui.js'></script>	
		<script type='text/javascript' src='userpage.js'></script>	
		<script type='text/javascript' src='google-chart.js'></script>
	</head>
	
	<body>	
		<h3>Cloudstorage</h3>
		
		
		<!--debug-->
 		<!--<button id='show-cookie'>Show All Cookie</button>
		<button id='show-user-text'>Show User Text</button>
		<button id='exit'>Exit</button>
		<button id='show-session'>Show SESSION</button>
		<button id='remove-temp'>Remove temp</button> 
		
		<div id='user-text' style='background:#7a88a7'>Usertext</div>
		<div id='session-data' style='background:green'>Session</div>-->
		
		<div id='analyze-size'>
			<div class='analyze-size-title'>
				<div style='display:inline;color:white;margin-left:5px'>Folder size</div>
				<img id='close-image-3' class='close-image' src='images_16/close.png'>
			</div>
			<div id='chart'></div>
			<div id='table'></div>
		</div>
		
		<div id='search-file'>
			<div class='search-file-title'>
				<div style='display:inline;color:white;margin-left:5px'>Search file</div>
				<img id='close-image-4' class='close-image' src='images_16/close.png'>
			</div>
			<div id='search-info'>
				<label for='search-file-name'>File name</label>
				<input id='search-file-name' type='text'>
				<label for='search-in-folder'>Search In Folder</label>
				<input id='search-in-folder' type='text'>
				<div>
					<input name='search-settings' style='display:inline' id='search-in-all-folders' type='radio' checked='checked'>
					<label style='display:inline'>Search in all folders</label>
				</div>
				<div>
					<input name='search-settings' style='display:inline' id='search-in-current-folder' type='radio'>
					<label style='display:inline'>Search in current folder</label>
				</div>
				<div>
					<input name='search-settings' style='display:inline' id='search-in-selected-folder' type='radio'>
					<label style='display:inline'>Search in selected folder</label>
				</div>
			</div>
			<div id='search-table'></div>
			<footer>
				<button id='search-btn-search' class='search-btn'>Search</button>
				<button id='search-btn-close' class='search-btn'>Close</button>
			</footer>
		</div>
		
		<div class='top-panel'>
			<div id='btn-home' class='panel-btn'>
				<img src='images_32/home.png'>
				<div>Home</div>
			</div>
			<div id='btn-new-folder' class='panel-btn'>
				<img src='images_32/new-folder.png'>
				<div>New folder</div>
			</div>
			<div id='btn-refresh' class='panel-btn'>
				<img src='images_32/refresh.png'>
				<div>Refresh</div>
			</div>
			<div id='btn-upload' class='panel-btn'>
				<img src='images_32/upload.png'>
				<div>Upload files</div>
			</div>
			<div id='btn-analyze-size' class='panel-btn'>
				<img src='images_32/analyze-size.png'>
				<div>Analyze size</div>
			</div>
			<div id='btn-search-file' class='panel-btn'>
				<img src='images_32/search-files.png'>
				<div>Search files</div>
			</div>
		</div>
		
		<div id='current-folder' style='background:#95b9b9;padding:5px'>Current folder: /</div>
		
		<div id='files-and-folders'>
			<div id='obj-name' class='name column'>
				<div class='first-row'>Name</div>
			</div>
			<div id='obj-extension' class='extension column'>
				<div class='first-row'>Extension</div>
			</div>
			<div id='obj-size' class='size column'>
				<div class='first-row'>Size</div>
			</div>
			<div id='obj-owner' class='owner column'>
				<div class='first-row'>Owner</div>
			</div>
			<div id='obj-access' class='access column'>
				<div class='first-row'>Access</div>
			</div>
			<div id='obj-changed' class='changed column'>
				<div class='first-row'>Changed</div>
			</div>
			<div id='obj-create' class='create column'>
				<div class='first-row'>Create</div>
			</div>
		</div>
		
		<div id='show-file'>
			<div id='path-to-file' style='display:none'>test</div>
			<div id='show-file-title'>
				<div id='file-name'></div>
				<img id='close-image-1' class='close-image' src='images_16/close.png'>
			</div>
			<div class='show-file-toolbar'>
				<button id='save-file-btn' class='show-file-toolbar-btn'>Save</button>
				<button id='settings-btn' class='show-file-toolbar-btn'>Settings</button>
			</div>
			<div class='show-file-center'>
				<div id='container-row-numbers'></div>
				<textarea id='text-area' wrap='off'></textarea>
			</div>
			<div class='file-info'>
				<div id='text-length' class='file-info-item'>length:</div>
				<div id='row-count' class='file-info-item'>lines:</div>
				<div id='cursor-on-line' class='file-info-item'>cursor on line:</div>
				<div id='cursor-pos' class='file-info-item'>cursor position:</div>
				<div id='selected' class='file-info-item'>selected:</div>
			</div>
		</div>
		
		<div id='show-file-settings'>
			<div class='file-settings-title'>
				<div style='display:inline;color:white;margin-left:5px'>Settings</div>
				<img id='close-image-5' class='close-image' src='images_16/close.png'>
			</div>
			<div class='settings'>
				<div  style='margin-left:10px;'>Font size</div>
				<input id='font-size-input' type='text' style='margin-left:10px'>
				<div  style='margin-top:10px;margin-left:10px'>Font color</div>
				<input id='font-color-input' type='text' style='margin-left:10px'>
				<div  style='margin-top:10px;margin-left:10px'>Background color</div>
				<input id='background-color-input' type='text' style='margin-left:10px'>
				
				<button id='settings-btn-ok' style='min-width:50px'>Ok</button>
			</div>
		</div>
		
		<div id='upload-files'>
			<div id='upload-files-title'>
				<div id='browse-file' style='max-width:100%;margin-left:5px;color:white;'>Browse file</div>
				<img id='close-image-2' class='close-image' src='images_16/close.png'>
			</div>
			<div class='upload-files-toolbar'>
				<div id='browse' class='upload-files-btn'>Browse</div>
				<div id='upload' class='upload-files-btn'>Upload</div>
			</div>
			<div id='upload-file-center' class='upload-file-center'>
			</div>
			<div class='upload-file-footer'></div>
			<input name='userfile' id='input' type='file' multiple style='display:none'>
		</div>
		
		<div id='folder-menu' class='context-menu'>
			<div id='open-folder' class='menu-item'>
				<img src='images_16/open.png'>
				<div class='menu-text'>Open</div>
			</div>
			<div id='rename-folder' class='menu-item'>
				<img src='images_16/rename.png'>
				<div class='menu-text'>Rename</div>
			</div>
			<div id='create-copy-folder' class='menu-item'>
				<img src='images_16/create-copy.png'>
				<div class='menu-text'>Create copy</div>
			</div>
			<div id='download-folder-as-archive' class='menu-item'>
				<img src='images_16/download-archive.png'>
				<div class='menu-text'>Download as archive</div>
			</div>
			<div id='create-archive-folder' class='menu-item'>
				<img src='images_16/create-archive.png'>
				<div class='menu-text'>Create archive</div>
			</div>
			<div id='remove-folder' class='menu-item'>
				<img src='images_16/remove.png'>
				<div class='menu-text'>Remove</div>
			</div>
		</div>
		
		<div id='context-menu' class='context-menu'>
			<div id='new-file' class='menu-item'>
				<img src='images_16/newfile.png'>
				<div class='menu-text'>New file</div>
			</div>
			<div id='refresh' class='menu-item'>
				<img src='images_16/refresh.png'>
				<div class='menu-text'>Refresh</div>
			</div>
			<div id='new-folder' class='menu-item'>
				<img src='images_16/mkdir.png'>
				<div class='menu-text'>New folder</div>
			</div>
			<div id='upload-file' class='menu-item'>
				<img src='images_16/upload.png'>
				<div class='menu-text'>Upload files</div>
			</div>
		</div>
		
		<div id='file-menu' class='context-menu'>
			<div id='edit-file' class='menu-item'>
				<img src='images_16/edit.png'>
				<div class='menu-text'>Edit</div>
			</div>
			<div id='create-archive-file' class='menu-item'>
				<img src='images_16/create-archive.png'>
				<div class='menu-text'>Create archive</div>
			</div>
			<div id='create-copy-file' class='menu-item'>
				<img src='images_16/create-copy.png'>
				<div class='menu-text'>Create copy</div>
			</div>
			<div id='download-file-as-archive' class='menu-item'>
				<img src='images_16/download-archive.png'>
				<div class='menu-text'>Download as archive</div>
			</div>
			<div id='view-file' class='menu-item'>
				<img src='images_16/view.png'>
				<div class='menu-text'>View</div>
			</div>
			<div id='rename-file' class='menu-item'>
				<img src='images_16/rename.png'>
				<div class='menu-text'>Rename</div>
			</div>
			<div id='download-file' class='menu-item'>
				<img src='images_16/download-basic.png'>
				<div class='menu-text'>Download file</div>
			</div>
			<div id='remove-file' class='menu-item'>
				<img src='images_16/remove.png'>
				<div class='menu-text'>Remove</div>
			</div>
		</div>
		
	</body>
	
</html>																																		