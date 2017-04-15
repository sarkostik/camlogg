<?php 
$serverRoot = $_SERVER['DOCUMENT_ROOT'];
include_once $serverRoot.'/framework/includes/db_connect.php';
include_once $serverRoot.'/framework/includes/functions.php';
sec_session_start(); // Our custom secure way of starting a PHP session.
require_once 'timestamp.php';    


if ($_SESSION['username'] !=""){

	$timeStamp = new TimeStamp();    
	$result["setRelation"] = $timeStamp->setRelation($_POST['uuid'], $_POST['comment'], $_POST['flowId']);

	header("Content-Type: text/plain");
    echo json_encode($result);
}

?>