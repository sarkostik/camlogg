<?php 

$userId = $_SESSION['user_id'];

$stmt = $mysqli->prepare("SELECT accesslevel, portal 
						  FROM groups 
                          WHERE memberid = ? LIMIT 1");

$stmt->bind_param('s', $userId);
$stmt->execute();
$stmt->store_result();
        
$stmt->bind_result($accesslevel, $portal);
$stmt->fetch();
?>