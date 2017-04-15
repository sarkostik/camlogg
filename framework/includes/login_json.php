<?php


include_once 'db_connect.php';
include_once 'functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

$isValid = false;
$rtnMsg = "";

if (isset($_POST['email'], $_POST['p'])) {
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
"loginResponse" => $isValid,	
"message" => $rtnMsg,				
);	



header('Content-type: application/json');
echo json_encode($rtnArr, JSON_FORCE_OBJECT);


?>