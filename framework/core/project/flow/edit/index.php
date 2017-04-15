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
		$eg = usrValid($_POST['project']);
		if ($eg === true){
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
		else
			$dbResponse = "Not a valid user!";
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
	"UpdatedProject" => $eg,
	"ProjectDetails" => $myNewProject
	);	

	header('Content-type: application/json');
	echo json_encode($rtnMsg, JSON_FORCE_OBJECT);
}

function usrValid($projectData){
	$userId = $_SESSION['user_id'];	
	$projectData = json_decode($projectData);	
	$projectId = $projectData->{'projectId'};
	$sqlLine = "SELECT UserId FROM `WorkProject` WHERE ProjectId='".$projectId."'";

	try
	{
    	$conn = new PDO("mysql:dbname=camlogg;charset=utf8", USER, PASSWORD);
    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	$query = $conn->prepare($sqlLine);
		
		$rtn;
		$x = 0;
		if($query->execute()){
			while ($row = $query->fetch()) {
				$rtn = $row['0'];
  			}
  			if($rtn == $userId)
  				return true;
  			else
  				return false;
		}
		else
			return false;
    }
	catch(PDOException $e)
	{
		return $e->getMessage();
    }
}

function dbNewProject($projectData,$kluff){		
	$projectData = json_decode($projectData);
	//SANERA!!
	$projectName = $projectData->{'name'};
	$projectId = $projectData->{'projectId'};
	$projectOwner = $projectData->{'projectOwner'};
	$projectStartDate = $projectData->{'startDate'};
	$projectEndDate = $projectData->{'endDate'};
	$projectStatus = $projectData->{'isActive'};
	$myMembers = $projectData->{'members'};
	
	$userId = $_SESSION['user_id'];

	$a = count($myMembers);
	$userProject = "insert into UserProject (`ProjectId`,`UserId`) values ('".$projectId."','".$userId."');";

	for ($b = 0; $b < $a; $b++){
		$userProject .= "insert into UserProject (`ProjectId`,`UserId`) values ('".$projectId."','".$myMembers[$b]."');";
	}

	$sqlLine = 
	"delete from UserProject where ProjectId='".$projectId."';".
	"update WorkProject set ProjectName='".$projectName."' ".
	", StartDate='".$projectStartDate."' ".
	", Status='".$projectStatus."' ".
	", EndDate='".$projectEndDate."' ".
	", Owner='".$projectOwner."' ".
	"where ProjectId='".$projectId."';";


	try 
	{
    	$conn = new PDO("mysql:dbname=camlogg;charset=utf8", USER, PASSWORD);    	
    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	$query = $conn->prepare($sqlLine.$userProject);

		return $query->execute();  							
    }
	catch(PDOException $e)
	{		
		return $e->getMessage();		
    }	


}



?>