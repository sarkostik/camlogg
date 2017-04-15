<?php 

$serverRoot = $_SERVER['DOCUMENT_ROOT'];
include_once $serverRoot.'/framework/includes/db_connect.php';
include_once $serverRoot.'/framework/includes/functions.php';
sec_session_start(); 

$isSession = $_SESSION['username'];
$isPost = false;
$dbResponse = false;

if ($isSession != null){
	if (isset($_POST['project'])){
		$isPost = true;	
		$kluff = date("YmdHis");
		$userId = $_SESSION['user_id'];
		$kluff = $kluff.$userId;
		$dbResponse = dbNewProject($_POST['project'],$kluff);
		if ($dbResponse === true){
			$myNewProject = array(						
			"ProjectId" => $kluff,				
			"UserId" => $userId			
			);	
		}		
	}
	else{
		$a = require_once $serverRoot.'/framework/views/error.php';	
		$b =  false;
		echo $a;
	}
}

if ($isSession != null && $isPost){	
	$rtnMsg = array(						
	"Response" => $b,				
	"Session" =>$isSession,				
	"Debug" => $isPost,	
	"CreatedProject" => $dbResponse,
	"ProjectDetails" => $myNewProject
	);	

	header('Content-type: application/json');
	echo json_encode($rtnMsg, JSON_FORCE_OBJECT);
}

function dbNewProject($projectData,$kluff){		
	$projectData = json_decode($projectData);
	//SANERA!!
	$projectName = $projectData->{'name'};
	$projectOwner = $projectData->{'projectOwner'};
	$projectStartDate = $projectData->{'startDate'};
	$projectStatus = $projectData->{'isActive'};
	$myMembers = $projectData->{'members'};
	$userId = $_SESSION['user_id'];

	$a = count($myMembers);
	$userProject = "insert into UserProject (`ProjectId`,`UserId`) values ('".$kluff."','".$userId."');";

	for ($b = 0; $b < $a; $b++){
		$userProject .= "insert into UserProject (`ProjectId`,`UserId`) values ('".$kluff."','".$myMembers[$b]."');";
	}
	
	try 
	{
    	$conn = new PDO("mysql:dbname=camlogg;charset=utf8", USER, PASSWORD);    	
    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	$query = $conn->prepare("insert into WorkProject (`ProjectId`,`ProjectName`,`StartDate`,`Status`,`Owner`,`UserId`) values ".
    		"('".$kluff."','".$projectName."','".$projectStartDate."',".$projectStatus.",'".$projectOwner."','".$userId."');".$userProject);

		return $query->execute();  							
    }
	catch(PDOException $e)
	{		
		return $e->getMessage();		
    }	


}



?>