<?php 

$serverRoot = $_SERVER['DOCUMENT_ROOT'];
include_once $serverRoot.'/framework/includes/db_connect.php';
include_once $serverRoot.'/framework/includes/functions.php';
sec_session_start(); 

$sessionUser = $_SESSION['username'];
$isPost = false;
$dbResponse = false;
$rtnMsg = false;

if ($sessionUser !== null){

	if ($_GET['flowId'] == ""){
		$dbResponse = $rtnDbQuery = dbNewProject();				
	}else{
		$dbResponse = listFlow();
	}

	
}
else
	$sessionUser = false;
	

$rtnMsg = array(						
"Response" => $sessionUser,				
"Session" =>$isSession,				
"Flows" => $dbResponse,
);


header('Content-type: application/json');
echo json_encode($rtnMsg, JSON_FORCE_OBJECT);


function listFlow(){	
	$userId = $_SESSION['user_id'];
	$flowId = $_GET['flowId'];
	$queryLine = "SELECT * FROM FlowSiteLarge where FlowId='".$flowId."'";

	try 
	{
    	$conn = new PDO("mysql:dbname=camlogg;charset=utf8", USER, PASSWORD);    	
    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	$query = $conn->prepare($queryLine);
		
		$rtn = array();
		$x = 0;
		if($query->execute()){			
				while ($row = $query->fetch()) {
  				$rtn[$x++] = array(				
  					"Id" => $row['0'],
  					"Content" => $row['1'],
					"UserId" => $row['2'],
					"DateTime" => $row['3'],
					"RelationId" => $row['4'],					
					"TypeContent" => $row['5'],
					"FlowId" => $row['6'],
					"ProjectId" => $row['7']
				);    			
  			}	
			return $rtn;
		}
		else
			return false;

    }
	catch(PDOException $e)
	{		
		return $e->getMessage();		
    }	

}


function dbNewProject(){		
	$userId = $_SESSION['user_id'];

	$queryLine =
	"select WorkFlow.Flowname, WorkFlow.FlowId, WorkFlow.StartDate, secure_login.members.username, `WorkFlow`.`Status` ".
	"from WorkFlow, UserFlow, secure_login.members, WorkProject ".
	"where UserFlow.UserId = secure_login.members.id ".
	"and WorkFlow.ProjectId = WorkProject.ProjectId ".
	"and WorkFlow.FlowId = UserFlow.FlowId ".
	"and `WorkProject`.`Status` = '1' ".
	"and UserFlow.UserId='".$userId."' ".
	"group by Flowname";	

	try 
	{
    	$conn = new PDO("mysql:dbname=camlogg;charset=utf8", USER, PASSWORD);    	
    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	$query = $conn->prepare($queryLine);
		
		$rtn = array();
		$x = 0;
		if($query->execute()){			
				while ($row = $query->fetch()) {
  				$rtn[$x++] = array(				
  					"Flowname" => $row['0'],
  					"FlowId" => $row['1'],
					"StartDate" => $row['2'],
					"Username" => $row['3'],
					"Status" => $row['4'],					
					"FlowData" => null,
				);    			
  			}	
			return $rtn;
		}
		else
			return false;

    }
	catch(PDOException $e)
	{		
		return $e->getMessage();		
    }	
}
?>