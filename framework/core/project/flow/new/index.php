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
}

if ($isSession != null){	
	$projectData = json_decode($_POST['project']);
	$rtnMsg = array(						
	"Response" => $b,				
	"Session" =>$isSession,				
	"Debug" => $isPost,	
	"CreatedProject" => $projectData,
	"ProjectDetails" => $myNewProject
	);	

	header('Content-type: application/json');
	echo json_encode($rtnMsg, JSON_FORCE_OBJECT);
}

function dbNewProject($projectData,$kluff){	
	$projectData = json_decode($projectData);
	//SANERA!!
	$flowName = $projectData->{'name'};
	$flowOwner = $projectData->{'projectOwner'};
	$flowStartDate = $projectData->{'startDate'};
	$flowStatus = $projectData->{'isActive'};
	$myMembers = $projectData->{'members'};
	$projectId = $projectData->{'project'};
	$userId = $_SESSION['user_id'];

	$a = count($myMembers);
	$userProject = "insert into UserFlow (`FlowId`,`UserId`) values ('".$kluff."','".$userId."');";

	for ($b = 0; $b < $a; $b++){
		$userProject .= "insert into UserFlow (`FlowId`,`UserId`) values ('".$kluff."','".$myMembers[$b]."');";
	}
	
	try 
	{
    	$conn = new PDO("mysql:dbname=camlogg;charset=utf8", USER, PASSWORD);    	
    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	$query = $conn->prepare("insert into WorkFlow (`FlowId`,`FlowName`,`StartDate`,`Status`,`Owner`,`UserId`,`ProjectId`) values ".
    		"('".$kluff."','".$flowName."','".$flowStartDate."',".$flowStatus.",'".$flowOwner."','".$userId."','".$projectId."');".$userProject);

		return $query->execute();  							
    }
	catch(PDOException $e)
	{		
		return $e->getMessage();		
    }	


}



?>