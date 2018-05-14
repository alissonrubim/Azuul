<?php
	session_start();
	
	error_reporting(E_ALL); 
	ini_set('display_errors', 1);

	include_once('mvc/mvc.php');
	include_once('mvc/mvc.controller.php');

	$mvc = new MVC();
	$mvc->execute(
		isset($_GET['controller']) ? $_GET['controller'] : 'home', 
		isset($_GET['action']) ? $_GET['action'] : 'index'); 
?>