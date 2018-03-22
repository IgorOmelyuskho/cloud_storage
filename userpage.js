var folderMenu = null;
var fileMenu = null;
var contextMenu = null;
var showFile = null;
var arrayName = [];
var arraySize = [];
var arrayAccess = [];
var arrayChanged = [];
var arrayCreate = [];
var arrayOwner = [];
var arrayExtension = [];
var numbersArray = [];
var targetFolder = null;
var targetFile = null;
var fileNotSave = false;

window.onload = function() {
	document.body.onmousedown = bodyMouseDown;
	document.body.oncontextmenu = bodyOnContextMenu;
	
	//Need internet connection for google.charts
	google.charts.load('current', {'packages':['corechart']});
	google.charts.load('current', {'packages':['table']});	
	
	$('#show-file').resizable({helper:'helperstyle', minHeight:150, minWidth:600, handles:'all', autoHide:true});
	$('#show-file').draggable({opacity: 0.5});
	
	$('#upload-files').resizable({helper:'helperstyle', minHeight:190, minWidth:200, handles:'all', autoHide:true});
	$('#upload-files').draggable({opacity: 0.5});
	
	$('#analyze-size').draggable({opacity: 0.5});
	
	$('#search-file').draggable({opacity: 0.5});
	$('#search-file').resizable({helper:'helperstyle', minWidth:100, handles:'e', autoHide:true});
	
	$('#show-file-settings').draggable({opacity: 0.5});
	$('#show-file-settings').resizable({helper:'helperstyle', minWidth:100, handles:'e', autoHide:true});
	
	$('#obj-name').resizable({helper:'helperstyle', minWidth:100, handles:'e', autoHide:true});
	$('#obj-extension').resizable({helper:'helperstyle', minWidth:100, handles:'e', autoHide:true});
	$('#obj-size').resizable({helper:'helperstyle', minWidth:100, handles:'e', autoHide:true});
	$('#obj-access').resizable({helper:'helperstyle', minWidth:100, handles:'e', autoHide:true});
	$('#obj-owner').resizable({helper:'helperstyle', minWidth:100, handles:'e', autoHide:true});
	$('#obj-changed').resizable({helper:'helperstyle', minWidth:100, handles:'e', autoHide:true});
	$('#obj-create').resizable({helper:'helperstyle' ,minWidth:100, handles:'e', autoHide:true});
	
	showFile = document.getElementById('show-file');
	folderMenu = document.getElementById('folder-menu');
	fileMenu = document.getElementById('file-menu');																																								
	contextMenu = document.getElementById('context-menu');
	
	document.getElementById('open-folder').onmousedown = function(e) {
		openDir(targetFolder[0].innerHTML);
	}
	
	document.getElementById('rename-folder').onmousedown = function(e) {
		renameFileOrFolder('folder');
	}
	
	document.getElementById('remove-folder').onmousedown = function(e) {
		var result = confirm('do you want to remove folder?');
		if (result === true) {
			removeFileOrFolder('folder');
		}
	}
	
	document.getElementById('settings-btn-ok').onmousedown = function(e) {
		$('#text-area').css('font-size', parseInt($('#font-size-input').val()) + 'px');
		$('#container-row-numbers').css('font-size', parseInt($('#font-size-input').val()) + 'px');
		$('#text-area').css('color', $('#font-color-input').val());
		$('#text-area').css('background', $('#background-color-input').val());
	}
	
	document.getElementById('create-archive-folder').onmousedown = function(e) {
		var dataForSend = {'zip': 'folder', 'name': targetFolder[0].innerHTML};
		$.ajax({
			type:'POST',
			url:'ajax/create-archive.php',
			data:dataForSend,
			response:'text',
			success: function(data) {					
				if (data.indexOf('success') === -1) {
					alert(data);
				} 
				updateFilesAndFolders();
			}
		});
	}
	
	document.getElementById('download-folder-as-archive').onmousedown = function(e) {
		var dataForSend = {'downloadasarchive': 'folder', 'name': targetFolder[0].innerHTML};
		$.ajax({
			type:'POST',
			url:'ajax/download-as-archive.php',
			data:dataForSend,
			response:'text',
			success: function(data) {
				//alert(data);
				var linkToFile = document.createElement('a');
				linkToFile.setAttribute('href', data);
				linkToFile.setAttribute('download','');
				onload=linkToFile.click();
				updateFilesAndFolders();
			}
		});
	}
	
	document.getElementById('create-copy-folder').onmousedown = function(e) {
		var dataForSend = {'createcopy': 'folder', 'name': targetFolder[0].innerHTML};
		$.ajax({
			type:'POST',
			url:'ajax/create-copy.php',
			data:dataForSend,
			response:'text',
			success: function(data) {					
				if (data.indexOf('success') === -1) {
					alert(data);
				} 
				updateFilesAndFolders();
			}
		});
	}
	
	document.getElementById('edit-file').onmousedown = function(e) {
		openFile(targetFile[0].innerHTML, false);
	}
	
	document.getElementById('rename-file').onmousedown = function(e) {
		renameFileOrFolder('file');
	}
	
	document.getElementById('create-archive-file').onmousedown = function(e) {
		var dataForSend = {'zip': 'file', 'name': targetFile[0].innerHTML};
		$.ajax({
			type:'POST',
			url:'ajax/create-archive.php',
			data:dataForSend,
			response:'text',
			success: function(data) {					
				if (data.indexOf('success') === -1) {
					alert(data);
				} 
				updateFilesAndFolders();
			}
		});
	}
	
	document.getElementById('download-file-as-archive').onmousedown = function(e) {
		var dataForSend = {'downloadasarchive': 'file', 'name': targetFile[0].innerHTML};
		$.ajax({
			type:'POST',
			url:'ajax/download-as-archive.php',
			data:dataForSend,
			response:'text',
			success: function(data) {	
				var linkToFile = document.createElement('a');
				linkToFile.setAttribute('href', data);
				linkToFile.setAttribute('download','');
				onload = linkToFile.click();
				//updateFilesAndFolders();
			}
		});
	}
	
	document.getElementById('create-copy-file').onmousedown = function(e) {
		var dataForSend = {'createcopy': 'file', 'name': targetFile[0].innerHTML};
		$.ajax({
			type:'POST',
			url:'ajax/create-copy.php',
			data:dataForSend,
			response:'text',
			success: function(data) {					
				if (data.indexOf('success') === -1) {
					alert(data);
				} 
				updateFilesAndFolders();
			}
		});
	}
	
	document.getElementById('view-file').onmousedown = function(e) {
		openFile(targetFile[0].innerHTML, true);
	}
	
	document.getElementById('download-file').onmousedown = function(e) {
		var dataForSend = {'download': 'file', 'name': targetFile[0].innerHTML};
		$.ajax({
			type:'POST',
			url:'ajax/download.php',
			data:dataForSend,
			response:'script',
			success: function(data) {		
				var linkToFile = document.createElement('a');
				linkToFile.setAttribute('href', data);
				linkToFile.setAttribute('download','');
				onload = linkToFile.click();
			}
		});
	}
	
	document.getElementById('remove-file').onmousedown = function(e) {;
		var result = confirm('do you want to remove file?');
		if (result === true) {
			removeFileOrFolder('file');
		}
	}
	
	document.getElementById('new-file').onmousedown = function(e) {
		newFileOrFolder('file');
	}
	
	document.getElementById('new-folder').onmousedown = function(e) {
		newFileOrFolder('folder');
	}
	
	document.getElementById('refresh').onmousedown = function(e) {
		updateFilesAndFolders();
	}
	
	document.getElementById('upload-file').onmousedown = function(e) {
		document.getElementById('upload-files').style ='display:flex';
	}
	
	
	document.getElementById('browse').onclick = function(e) {
		showFileDialog();
	}
	
	document.getElementById('input').onchange = function(e) {
		var center = document.getElementById('upload-file-center');
		var files = document.getElementById('input').files;
		for (var i = 0; i < files.length; i++) {
			var div = document.createElement('div');
			div.innerHTML = files[i].name + '<br>';
			div.className = 'browse-file';
			center.appendChild(div);
		}
	}
	
	document.getElementById('upload').onclick = function(e) {
		var center = document.getElementById('upload-file-center');
		center.innerHTML = '';	
		var form_data = new FormData();
		var allFiles = $('#input').prop('files');
		if (allFiles.length === 0) {
			return false;
		}
		
		for (var i = 0; i < allFiles.length; i++) {
			var str = 'file' + i;
			form_data.append(str, allFiles[i]);
		}	
		
		$.ajax({
			url: 'ajax/upload-files.php',
			dataType: 'text',
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,
			type: 'post',
			success: function(data) {
				if (data.indexOf('success') === -1) {
					alert(data);
				} 
				updateFilesAndFolders();
			}
		});
	}
	
	//debug
/* 	document.getElementById('remove-temp').onmousedown = function(e) {
		$.ajax({
			url: 'ajax/remove-temp-files.php',
			dataType: 'text',
			data: {},
			type: 'post',
			success: function(php_script_response){
				alert(php_script_response);
			}
		});
	}
	
	document.getElementById('show-cookie').onclick = function(e) {
		alert(document.cookie);
	}
	
	document.getElementById('show-session').onclick = function(e) {
		$.ajax({
			type:'POST',
			url:'ajax/get-session-info.php',
			response:'text',
			success: function(data) {						
				document.getElementById('session-data').innerHTML = data; 
			}
		});
	}	
	
	document.getElementById('show-user-text').onclick = function(e) {
		$.ajax({
			type:'POST',
			url:'ajax/show-user-text.php',
			response:'text',
			success: function(data) {							
				document.getElementById('user-text').innerHTML = data;
			}
		});
	}
	
	document.getElementById('exit').onclick = function(e) {
		window.location = 'logout.php';
	} */
	
	document.getElementById('btn-home').onclick = function(e) {
		updateFilesAndFolders({'path': '', 'direction': 'home'});
	}
	
	document.getElementById('btn-new-folder').onmousedown = function(e) {
		newFileOrFolder('folder');
	}
	
	document.getElementById('btn-refresh').onmousedown = function(e) {
		updateFilesAndFolders();
	}
	
	document.getElementById('btn-upload').onmousedown = function(e) {
		document.getElementById('upload-files').style ='display:flex';
	}
	
	document.getElementById('btn-analyze-size').onmousedown = function(e) {
		document.getElementById('analyze-size').style = 'display:flex';
		var dataForSend = {'path': '', 'direction': 'first'}
		$.ajax({
			type:'POST',
			url:'ajax/scan-dir.php',
			data:dataForSend,
			response:'text',
			success: function(data) {						
				try {
					var obj = JSON.parse(data);
				}
				catch(err) {
					alert('cannot JSON.parse in document.getElementById(btn-analyze-size).onmousedown');
					alert('data:' + data);
					return;
				}
				
				//Need internet connection for google.charts
				try {
					google.charts.setOnLoadCallback(drawTable(obj));
					drawTable(obj);
				}
				catch(err) {
					alert(err.message);
				}
				try {
					google.charts.setOnLoadCallback(drawChart(obj));
					drawChart(obj);
				}
				catch(err) {
					alert(err.message);
				}
			}
		});
	}
	
	document.getElementById('btn-search-file').onmousedown = function(e) {
		document.getElementById('search-file').style = 'display:flex';
	}
	
	document.getElementById('close-image-2').onmousedown = function(e) {
		document.getElementById('upload-files').style.display = 'none';
	}
	
	document.getElementById('close-image-3').onclick = function(e) {
		document.getElementById('analyze-size').style = 'display:none';
	}
	
	document.getElementById('close-image-4').onclick = function(e) {
		document.getElementById('search-file').style = 'display:none';
	}
	
	document.getElementById('close-image-5').onclick = function(e) {
		document.getElementById('show-file-settings').style = 'display:none';
	}
	
	document.getElementById('search-btn-close').onclick = function(e) {
		document.getElementById('search-file').style = 'display:none';
	}
	
	document.getElementById('search-btn-search').onclick = function(e) {
		var radio1 = document.getElementById('search-in-all-folders');
		var radio2 = document.getElementById('search-in-current-folder');
		var radio3 = document.getElementById('search-in-selected-folder');
		
		var path = document.getElementById('search-in-folder').value;
		var name = document.getElementById('search-file-name').value;		
		if (name === '') {
			alert('Enter file name for search');
			return;
		}
		
		if (radio1.checked) {
			path = 'search-in-all-folders';
		}
		else if (radio2.checked) {
			path = 'search-in-current-folder';
		}
		
		var dataForSend = {'path': path, 'name': name}
		$.ajax({
			type:'POST',
			url:'ajax/search.php',
			data:dataForSend,
			response:'text',
			success: function(data) {						
				try {
					var obj = JSON.parse(data);
				}
				catch(err) {
					alert('cannot JSON.parse in document.getElementById(btn-search-files).onmousedown');
					alert('data:' + data);
					return; 
				}
				//Need internet connection for google.charts
				try {
					google.charts.setOnLoadCallback(drawTableSearch(obj));
					drawTableSearch(obj);
				}
				catch(err) {
					alert(err.message);
				}
			}
		});
	}
	
	updateFilesAndFolders({'path': '', 'direction': 'first'});
}

function drawChart(JSONObj) {
	if (JSONObj === null || JSONObj === undefined) {
		console.log('JSONObj === null || JSONObj === undefined');
		return;
	}
	
	var dataArr = [];
	dataArr.push(['Name', 'Size']);
	
	for (var i = 1; i < JSONObj.length; i++) { //for i = 1 ( i = 0 (Back) )
		var row = [];
		row.push(JSONObj[i]['name']);
		row.push( parseInt( JSONObj[i]['size']) );
		dataArr.push(row);
	}
	
	var data = google.visualization.arrayToDataTable(dataArr);
	
	var options = {
		title: 'My Daily Activities',
		backgroundColor: {fill: '#ded9ec'},
		chartArea: {left:100, top:35, height:'75%'},
		tooltip: {ignoreBounds: true}
	};
	
	var chart = new google.visualization.PieChart(document.getElementById('chart'));
	
	chart.draw(data, options);
}

function drawTable(JSONObj) {
	if (JSONObj === null || JSONObj === undefined){
		console.log('JSONObj === null || JSONObj === undefined');
		return;
	}
	
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Name');
	data.addColumn('number', 'Size');
	
	var rows = [];
	for (var i = 1; i < JSONObj.length; i++) { //for i = 1 ( i = 0 (Back) )
		var row = [];
		row.push(JSONObj[i]['name']);
		row.push( parseInt(JSONObj[i]['size']) );
		rows.push(row);
	}
	data.addRows(rows);
	
	var options = {
		showRowNumber: true,
		width: '100%',
		height: '100%',
		cssClassNames: {tableRow: 'table-row-style', oddTableRow: 'table-row-style'}
	}
	
	var table = new google.visualization.Table(document.getElementById('table'));
	
	table.draw(data, options);
}

function drawTableSearch(JSONObj) {
	if (JSONObj === null || JSONObj === undefined){
		console.log('JSONObj === null || JSONObj === undefined');
		return;
	}
	
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Name');
	data.addColumn('string', 'Path');
	
	var rows = [];
	for (var i = 0; i < JSONObj.length; i++) { 
		var row = [];
		row.push(JSONObj[i]['name']);
		row.push(JSONObj[i]['path']);
		rows.push(row);
	}
	data.addRows(rows);
	
	var options = {
		showRowNumber: true,
		width: '100%',
		height: '100%',
		cssClassNames: {tableRow: 'table-row-style', oddTableRow: 'table-row-style'}
	}
	
	var table = new google.visualization.Table(document.getElementById('search-table'));
	
	table.draw(data, options);
}

//Показывает диалог для выбора файла
function showFileDialog(){
	document.getElementById('input').click();
}

//Вызываем когда нада переименовать папку либо файл
function renameFileOrFolder(obj) {
	var oldName = undefined;
	var newName = undefined;
	if (obj === 'file') {
		oldName = targetFile[0].innerHTML;
		newName = prompt('Enter the new file name with the extension');
	}
	else if (obj === 'folder') {
		oldName = targetFolder[0].innerHTML;
		newName = prompt('Enter the new folder name');
	}
	else {
		return false;
	}
	if (newName === undefined || newName === '') {
		return false;
	}
	
	var dataForSend = {'oldname': oldName, 'newname': newName};
	$.ajax({
		type:'POST',
		url:'ajax/rename.php',
		data:dataForSend,
		response:'text',
		success: function(data) {							
			if (data.indexOf('success') === -1) {
				alert(data);
			} 
			updateFilesAndFolders();
		}
	});
}

//Удаляет файл либо папку
function removeFileOrFolder(obj) {
	var dataForSend = {};
	if (obj === 'file') {
		dataForSend = {'remove': 'file', 'name': targetFile[0].innerHTML};
	}
	else if (obj === 'folder') {
		dataForSend = {'remove': 'folder', 'name': targetFolder[0].innerHTML};
	}
	else {
		return false;
	}
	$.ajax({
		type:'POST',
		url:'ajax/remove.php',
		data:dataForSend,
		response:'text',
		success: function(data) {							
			if (data.indexOf('success') === -1) {
				alert(data);
			} 
			updateFilesAndFolders();
		}
	});
}

//Создает новый файл либо папку
function newFileOrFolder(obj) {
	var dataForSend = {};
	var name = undefined;
	if (obj === 'file') {
		name =  prompt('Enter the file name with the extension');
		dataForSend = {'create': 'file', 'name': name};
	}
	else if (obj === 'folder') {
		name =  prompt('Enter the folder name');
		dataForSend = {'create': 'folder', 'name': name};
	}
	else {
		return false;
	}
	if (name === undefined || name === '' || name === null) {
		return false;
	}
	
	$.ajax({
		type:'POST',
		url:'ajax/new-file-or-folder.php',
		data:dataForSend,
		response:'text',
		success: function(data) {	
			if (data.indexOf('success') === -1) {
				alert(data);
			} 
			updateFilesAndFolders();
		}
	});
}

//Отправляет запрос к scandir.php для обновления файлов и папок
function updateFilesAndFolders(dataForSend) {
	$.ajax({
		type:'POST',
		url:'ajax/scan-dir.php',
		data:dataForSend,
		response:'text',
		success: function(data) {						
			try {
				var JSONObj = JSON.parse(data);
			}
			catch(err) {
				alert('cannot JSON.parse in updateFilesAndFolders');
				alert('data:' + data);
				return;
			}
			filesAndFoldersOnLoad(JSONObj);
		}
	});
}

//Нужно вызвать когда приняли ответ от сервера(когда хотим посмотреть содержымое файла)
//Устанавливает обработчики и производит нужные действия
function fileOnLoad(data) {
	var textArea = document.getElementById('text-area');
	var rowCount = document.getElementById('row-count');
	var textLength = document.getElementById('text-length');
	var cursorPos = document.getElementById('cursor-pos');
	var cursorLine = document.getElementById('cursor-on-line');
	var containerForNumbers = document.getElementById('container-row-numbers');
	var closeImage = document.getElementById('close-image-1');
	var saveFileBtn = document.getElementById('save-file-btn');
	var settingsBtn = document.getElementById('settings-btn');
	var fileName = document.getElementById('file-name');
	var pathToFile = document.getElementById('path-to-file');
	var selected = document.getElementById('selected');	
	var obj = null;
	fileNotSave = false;
	
	try {
		obj = JSON.parse(data);
	}
	catch(err) {
		alert('cannot JSON.parse in fileOnLoad');
		alert('data:' + data);
		return;
	}
	
	textArea.value = obj['filetext'];
	pathToFile.innerHTML = obj['pathtofile'];
	if (obj['readonly'] === 'false') {
		fileName.innerHTML = obj['filename'];		
		textArea.removeAttribute('readonly');
	}
	else {
		fileName .innerHTML = obj['filename'] + ' (readonly)';
		textArea.setAttribute('readonly', '');
	}
	
	showFile.style.display = 'flex';
	textLength.innerHTML = 'length: ' + textArea.value.length;
	rowCount.innerHTML = 'lines: ' + textArea.value.split('\n').length;
	setNumbers(0);
	
	settingsBtn.onclick = function(e) {
		document.getElementById('show-file-settings').style = 'left:100px;top:100px;display:block';
	}
	
	saveFileBtn.onclick = function(e) {
		fileNotSave = false;
		var dataForSend = {'savefile': 'yes', 'pathtofile': pathToFile.innerHTML, 'value': textArea.value};
		$.ajax({
			type:'POST',
			url:'ajax/save-file.php',
			data:dataForSend,
			response:'text',
			success: function(data) {					
				if (data.indexOf('success') === -1) {
					alert(data);
				} 
				//updateFilesAndFolders();//Не обезательно
			}
		});
	}

	textArea.onselect = function(e) {
		selected.innerHTML = 'selected: ' + (textArea.selectionEnd - textArea.selectionStart); 
		getCursorPosition();
	}
	
	textArea.onscroll =  function(e) { 
		setNumbers(this.scrollTop);
	}
	
	textArea.onmousedown = function(e) {
		if (e.button === 0) {
			getCursorPosition();
		}
	}
	
	textArea.onmouseup = function(e) {
		if (e.button === 0){
			var cursorOnln = cursorOnLine();
			cursorLine.innerHTML = 'cursor on line: ' + cursorOnln;
		}
	}
	
	textArea.oninput = function(e) {
		fileNotSave = true;
		var textValue = textArea.value;
		textLength.innerHTML = 'length: ' + textValue.length;
		rowCount.innerHTML = 'lines: ' + textValue.split('\n').length;
		getCursorPosition();
		var cursorOnln = cursorOnLine();
		cursorLine.innerHTML = 'cursor on line: ' + cursorOnln;
	}
	
	textArea.onkeydown = function(e) {
		var cursorOnln = cursorOnLine();
		cursorLine.innerHTML = 'cursor on line: ' + cursorOnln;
	}
	
	textArea.oncontextmenu = function(e) {
		e.stopPropagation();
	}
	
	closeImage.onclick = function() {
		if (fileNotSave === true) {
			var result = confirm('do you want to save changes to the file?');
		}
		if (result === true) {
			saveFileBtn.onclick();
			saveFileBtn.onclick();
		}
		showFile.style.display = 'none';
	}
	
	//Устанавливает нужный innerHTML у div с слассом 'number' и нужное количество етих div
	//Устанавливает нужный margin-top у первого div с классом 'number'
	function setNumbers(scrollTop) {
		var style = window.getComputedStyle(textArea);
		var height = parseInt(style.getPropertyValue('height'));
		var lineHeight = parseInt(style.getPropertyValue('line-height'));
		var count = Math.floor(height / lineHeight) + 2;
		var marginTop = 0;
		
		if (containerForNumbers.childNodes.length < count) {
			for (var i = numbersArray.length; i < count; i++) {
				var div = document.createElement('div');
				div.className = 'number';
				containerForNumbers.appendChild(div);
				numbersArray[i] = div;
			}
		}
		var start = Math.floor(scrollTop / lineHeight);
		for (var i = start; i < numbersArray.length + start; i++) {
			numbersArray[i - start].innerHTML = start + i + 1 - Math.floor(scrollTop / lineHeight);
			numbersArray[i - start].style.background = 'inherit';
		}
		if (scrollTop > lineHeight) {
			marginTop = scrollTop - Math.floor(scrollTop / lineHeight) * lineHeight;
		}
		else {
			marginTop = scrollTop;
		}
		numbersArray[0].style.marginTop = -marginTop + 'px';
	} 
	
	//Позыция в тексте на которой находится курсор
	function getCursorPosition() {
		setTimeout(function() {
			cursorPos.innerHTML = 'cursor position: ' + textArea.selectionStart;
		}, 50);
	}
	
	//Возвращает номер линии на которой находится курсор
	function cursorOnLine() {
		return (textArea.value.substr(0, textArea.selectionStart).split('\n').length);
	}
	
}

function bodyMouseDown(e) {
	//Уберем контекстное меню
	if (e.button === 0) {
		fileMenu.style.display = 'none';
		folderMenu.style.display = 'none';
		contextMenu.style.display = 'none';
		if (targetFolder !== null) {	
			setRowBackground($(targetFolder).attr('number'), '');
		}
		if (targetFile !== null) {
			setRowBackground($(targetFile).attr('number'), '');
		}
	}
}

function bodyOnContextMenu(e) {
	if (e.target !== document.body){
		contextMenu.style.display = 'none';
		return false;
	}
	folderMenu.style.display = 'none';
	fileMenu.style.display = 'none'
	contextMenu.style.top =  e.pageY + 'px';
	contextMenu.style.left = e.pageX + 'px';
	contextMenu.style.display = 'block';
	return false;
}

//Возвращает размер файла в (B, KB, MB, GB, TB) 
function FBytes(size) {
	if(size <= 1024) return size + ' B'; 
	else if(size <= 1024*1024) return (size/(1024)).toFixed(3) + ' KB'; 
	else if(size <= 1024*1024*1024) return (size/(1024*1024)).toFixed(3) + ' MB'; 
	else if(size <= 1024*1024*1024*1024) return (size/(1024*1024*1024)).toFixed(3) + ' GB'; 
	else if(size <= 1024*1024*1024*1024*1024) return (size/(1024*1024*1024*1024)).toFixed(3) + ' TB'; 
}

//Нужно вызвать при обновлении списка файлов и папок 
function filesAndFoldersOnLoad(JSONObj) {
	var filesAndFolders = document.getElementById('files-and-folders');
	var objName = document.getElementById('obj-name');
	var objSize = document.getElementById('obj-size');
	var objAccess = document.getElementById('obj-access');
	var objChanged = document.getElementById('obj-changed');
	var objCreate = document.getElementById('obj-create');
	var objOwner = document.getElementById('obj-owner');
	var objExtension = document.getElementById('obj-extension');
	
	objName.style.height = '';
	objSize.style.height = '';
	objAccess.style.height = '';
	objChanged.style.height = '';
	objCreate.style.height = '';
	objOwner.style.height = '';
	objExtension.style.height = '';
	
	$('.item').remove();
	
	for (var i = 0; i < JSONObj.length - 1; i++) {
		fillRows(JSONObj, i, arrayName, objName, 'name');
		fillRows(JSONObj, i, arraySize, objSize, 'size');	
		fillRows(JSONObj, i, arrayExtension, objExtension, 'extension');		
		fillRows(JSONObj, i, arrayAccess, objAccess, 'access');	
		fillRows(JSONObj, i, arrayChanged, objChanged, 'changed');		
		fillRows(JSONObj, i, arrayCreate, objCreate, 'create');	
		fillRows(JSONObj, i, arrayOwner, objOwner, 'owner');
	}
	
	var currentFolder = JSONObj[JSONObj.length - 1];
	document.getElementById('current-folder').innerHTML = currentFolder;
	
	filesAndFolders.onmousedown = mousedownFD;
}

//Заполняет все строки и столбцы данными, которые пришли с сервера
function fillRows(JSONObj, i, objArray, obj, jsonValue) {
	var newObj = document.createElement('div');
	
	if (jsonValue === 'name') {
		newObj.setAttribute('title', JSONObj[i]['name']); 
	}
	else if (jsonValue === 'changed') {
		newObj.setAttribute('title', JSONObj[i]['changed']); 	
	}
	else if (jsonValue === 'create') {
		newObj.setAttribute('title', JSONObj[i]['create']); 
	}
	
	newObj.innerHTML = JSONObj[i][jsonValue];
	
	if (jsonValue === 'size') {
		newObj.innerHTML = FBytes( parseInt(JSONObj[i][jsonValue]) );
	}
	
	objArray[i] = newObj;
	
	if (jsonValue === 'name') {
		if (JSONObj[i]['type'] === 'file') {
			newObj.innerHTML = '';
			var img = document.createElement('img');
			img.src = 'images_16/file.png';
			img.setAttribute('number', i);
			$(img).addClass('file');
			newObj.appendChild(img);
			var span = document.createElement('span');
			span.innerHTML = JSONObj[i][jsonValue];
			span.setAttribute('number', i);
			$(span).addClass('file');
			objArray[i] = span;
			newObj.appendChild(span);
		}
		else if (JSONObj[i]['type'] === 'folder') {
			newObj.innerHTML = '';
			var img = document.createElement('img');
			img.src = 'images_16/folder.png';
			img.setAttribute('number', i);
			$(img).addClass('folder');
			newObj.appendChild(img);
			var span = document.createElement('span');
			span.innerHTML = JSONObj[i][jsonValue];
			span.setAttribute('number', i);
			$(span).addClass('folder');
			objArray[i] = span;
			newObj.appendChild(span);
		}
	}
	
	if (newObj.innerHTML === '') {
		newObj.innerHTML = 'is null';
	}
	
	newObj.className = 'item ' + JSONObj[i]['type'];
	newObj.setAttribute('number', i);
	newObj.onmouseout = itemMouseOut;
	newObj.onmouseover = itemMouseOver;
	obj.appendChild(newObj);
}

function itemMouseOver(e) {
	var rowNumber = $(e.target).attr('number');
	var str = '#files-and-folders [number=' + rowNumber + ']';
	$(str).addClass('item-hover');
}

function itemMouseOut(e) {
	var rowNumber = $(e.target).attr('number');
	var str = '#files-and-folders [number=' + rowNumber + ']';
	$(str).removeClass('item-hover');
}

function setRowBackground(rowNumber, background) {
	var str = '#files-and-folders [number=' + rowNumber + ']';
	$(str).css('background', background);
}

function mousedownFD(e) {
	if (e.target.classList.contains('first-row') === true) {
		return; 
	} 
	var number = $(e.target).attr('number'); 
	var clickonFolder = arrayName[number].classList.contains('folder');
	
	if (e.button === 2) {
		//Если arrayFD[number] содержыт класс folder
		if (clickonFolder === true) {
			clickOnFolder(number, e);
		}
		else {   
			clickOnFile(number, e);
		}
	}
	else if (e.button === 0) {
		//Если arrayFD[number] содержыт класс folder
		if (clickonFolder === true) {					
			openDir(arrayName[number].innerHTML);
		}	
		//Если не содержыт класс folder
		else {
			openFile(arrayName[number].innerHTML, false);
		}
	}
	e.stopPropagation();
	return false;
}

function clickOnFolder(number, e) {
	fileMenu.style.display = 'none';
	contextMenu.style.display = 'none';
	folderMenu.style.display = 'block';
	folderMenu.style.top =  e.pageY + 'px';
	folderMenu.style.left = e.pageX + 'px';
	//Поставим стандартный цыет для предыдущего выделенного елемента
	if (targetFolder !== null && targetFolder !== undefined) {
		setRowBackground(targetFolder.attr('number'), '');//Сбросим background(если присвоить background нужный цвет то не работает)
	}
	//Должен быть выделен либо файл либо папка
	if (targetFile !== null && targetFile !== undefined) {
		setRowBackground(targetFile.attr('number'), '');
	}
	targetFolder = $('#obj-name [number=' + number + ']  span');
	setRowBackground(number, '#a2cac4');//Цвет для активного елемента
}

function clickOnFile(number, e) {
	folderMenu.style.display = 'none';
	contextMenu.style.display = 'none';
	fileMenu.style.display = 'block';
	fileMenu.style.top =  e.pageY + 'px';
	fileMenu.style.left = e.pageX + 'px';
	//Поставим стандартный цвет для предыдущего выделенного елемента
	if (targetFile !== null && targetFile !== undefined) {
		setRowBackground(targetFile.attr('number'), '');//Стандартный цвет
	}
	//Должен быть выделен либо файл либо папка
	if (targetFolder !== null && targetFolder !== undefined) {
		setRowBackground(targetFolder.attr('number'), '');//Стандартный цвет
	}
	targetFile = $('#obj-name [number=' + number + ']  span');
	setRowBackground(number, '#a2cac4');//Цвет для активного елемента
}

function openDir(folder) {
	var dataForSend = {};
	if (folder === '..' || folder === 'Back' || folder === 'back') {
		dataForSend = {'path': '', 'direction': 'back'};	
	} 
	else {
		dataForSend = {'path': folder, 'direction': 'forward'};
	}
	updateFilesAndFolders(dataForSend);
}

function openFile(file, readonly) {
	var dataForSend = {'path': file, 'openfile': 'yes', 'readonly': readonly};
	$.ajax({
		type:'POST',
		url:'ajax/open-file.php',
		data:dataForSend,
		response:'json',
		success: function(data) {							
			fileOnLoad(data);
		}
	});
}





