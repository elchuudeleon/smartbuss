<?php 
error_reporting(E_ALL);
ini_set('display_errors', 'true');
ini_set('display_startup_errors', 'true');
global $DEVELOP_ENVIRONMENT,$CLASS,$GLOBALS,$THEMES, $FILES, $ROOT, $URL,$DB,$SERVER,$USERDB,$PWDDB;

$DEVELOP_ENVIRONMENT = false;
$ROOT = $_SERVER["DOCUMENT_ROOT"]."/juriscon/";	
$URL = "http://localhost/juriscon/";

$GLOBALS = $ROOT ."php/";
$CLASS = $ROOT ."models/";
$THEMES = $ROOT ."tema/";
$FILE = $ROOT;

?>