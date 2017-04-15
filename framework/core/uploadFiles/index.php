<?php 

$abba = $_FILES['qquuid'];


$rtnMsg = array(	
"success" => true,
"error" => null,
"message" => $abba
);	

header('Content-type: application/json');
echo json_encode($rtnMsg, JSON_FORCE_OBJECT);
?>