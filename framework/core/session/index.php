<?php 

$serverRoot = $_SERVER['DOCUMENT_ROOT'];
include_once $serverRoot.'/framework/includes/db_connect.php';
include_once $serverRoot.'/framework/includes/functions.php';
sec_session_start(); 

$isSession = $_SESSION['username'];

header('Content-type: application/json');
echo json_encode($isSession, JSON_FORCE_OBJECT);

?>