<?php
session_start();
include 'Config.php';
//$langue  = isset($_SESSION['langue'])?$_SESSION['langue']:LANGUE;
$host = explode('.', $_SERVER['HTTP_HOST']);
$langue = LANGUE;
$fileJson = file_get_contents("lang/$langue.json");
$traductions = (json_decode($fileJson, true));


if (DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}
if (!count($_GET)) {
    $args = DEFAULT_VIEW;
} else {
    $args = $_GET['args'];
}
$args =  array_filter(explode('/', $args));
$page = array_shift($args).'.php';
if (!file_exists($page)) {
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
    include("404.php");exit;
}
include($page);
