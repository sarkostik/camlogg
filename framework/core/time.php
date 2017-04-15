<?php 

$dateTime = date("Y-m-d;H:i:s");	
$date = date("Y-m-d");	
$time = date("H:i:s");
$sec = date("s");
$kluff = date("YmdHis");

$rtnMsg = array(						
		"Response" => true,				
		"DateTime" => $dateTime,
		"Date" => $date,		
		"Time" => $time,
		"Second" => $sec,
		"BigDate" => $kluff
		);	

header('Content-type: application/json');
echo json_encode($rtnMsg, JSON_FORCE_OBJECT);




?>