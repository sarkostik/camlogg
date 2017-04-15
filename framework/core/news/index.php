<?php 

$myNews = 'nisse';

try {
	$conn = new PDO("mysql:dbname=camlogg;charset=utf8", USER, PASSWORD);
	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		

	$query = $conn->prepare("select * from News");
	
	$myNews = $query->execute();  					
}
catch(PDOException $e)
{	
	$myNews = $e->getMessage();
	//return $e->getMessage();
}



?>