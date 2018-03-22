<?php
	session_start();//Нельзя чтоб были пробелы до начала кода
	
	echo 'Password: '.$_SESSION['password'].'<br>';
	echo 'Usernamae: '.$_SESSION['username'].'<br>';
	echo 'Userpath: '.$_SESSION['userpath'].'<br>';
	echo 'Fullpath: '.$_SESSION['fullpath'].'<br>'; 
	
/* 	foreach ($_SESSION as $val){
		echo $val.'<br>';
	} */
?>

