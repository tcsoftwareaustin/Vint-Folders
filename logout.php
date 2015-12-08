<?
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
session_destroy();
if($_SERVER['SCRIPT_NAME']!='/login.php') {
	header('Location: login');
}