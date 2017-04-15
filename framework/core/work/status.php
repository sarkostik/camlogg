<?php 

$rtnMsg = array(						
"Response" => $isPost
);	


header('Content-type: application/json');
echo json_encode($rtnMsg, JSON_FORCE_OBJECT);


?>