<?php
//Load core function
require_once 'config.php';
require_once 'functions.php';
ob_start();

//Always display errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$page = detectPage();

//Connect database
$db = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8", $DB_USER, $DB_PASSWORD);
$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

//Detect login, profileID
$currentuser = null;
$userprofile = null;

if (isset($_SESSION['userId']))
{
	$currentuser = findUserById($_SESSION['userId']);
}

if (isset($_SESSION['userIdProfile']))
{
	$userprofile = findUserById($_SESSION['userIdProfile']);
}