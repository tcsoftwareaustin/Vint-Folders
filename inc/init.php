<?
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once('functions.php');
$cwd = dirname(__FILE__);
date_default_timezone_set("America/Chicago");
if(file_exists($cwd . '/config.php')) {
	require_once($cwd . '/config.php');
} else {
	header('Location: install');
}
if(isset($_POST['login_submitted'])) {

	$location = $_POST['location'];
	
	$_SESSION['location'] = $location;
	
}
if($_SERVER['SCRIPT_NAME']!='/login.php') {
	if(!isset($_SESSION['location'])) {
		header('Location: login');
	}
}