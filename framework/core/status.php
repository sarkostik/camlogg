<?php 

$serverRoot = $_SERVER['DOCUMENT_ROOT'];
include_once $serverRoot.'/framework/includes/db_connect.php';
include_once $serverRoot.'/framework/includes/functions.php';
include_once $serverRoot.'/framework/core/work/decode.php';
include_once $serverRoot.'/framework/core/news/index.php';
sec_session_start(); // Our custom secure way of starting a PHP session.

$a = $_SESSION;

if ($a == null){
	$a = false;
	$msg = "no session";
}
else{
	$a = true;
	$news = $myNews;
	$msg = "We have session: ".$_SESSION['username'];	
}

$rtnMsg = array(						
"Response" => true,				
"Session" => $a,
"Message" => $msg,
"UserId" => $_SESSION['user_id'],
"News" => $myNews
);	


header('Content-type: application/json');
echo json_encode($rtnMsg, JSON_FORCE_OBJECT);

?>