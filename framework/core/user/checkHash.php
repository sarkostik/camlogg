<?php

$serverRoot = $_SERVER['DOCUMENT_ROOT'];

include_once $serverRoot.'/framework/includes/db_connect.php';
include_once $serverRoot.'/framework/includes/functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

$isValid = false;
$rtnMsg = "";
$response = false;

if (isset($_POST['email'], $_POST['p'])) {
    $response = true;
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['p']; // The hashed password.
    
    if (login($email, $password, $mysqli) == true) {
        // Login success 
        $isValid = true;
    } else {
        // Login failed 
        $isValid = false;
    }
} else {
    // The correct POST variables were not sent to this page. 
    $rtnMsg = "Wrong POST variable!";    
}

$rtnArr = array(	
"Response" => $response,
"Login" => $isValid,	
"Msessage" => $rtnMsg,				
);	


header('Content-type: application/json');
echo json_encode($rtnArr, JSON_FORCE_OBJECT);


?>