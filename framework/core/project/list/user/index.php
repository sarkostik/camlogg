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
	$userId = $_SESSION['user_id'];	
	$dbResponse = $rtnDbQuery = dbNewProject($userId);			
}
else
	$sessionUser = false;	

$rtnMsg = array(						
"Response" => $sessionUser,				
"Session" =>$isSession,				
"Users" => $dbResponse,
);


header('Content-type: application/json');
echo json_encode($rtnMsg, JSON_FORCE_OBJECT);


function dbNewProject($userId){		
	$queryLine = 
	"select camlogg.WorkProject.Projectname, username, members.id, WorkProject.ProjectId ".
	"from camlogg.WorkProject, camlogg.UserProject, secure_login.members ".
	"where camlogg.WorkProject.ProjectId = camlogg.UserProject.ProjectId ".
	"and camlogg.UserProject.UserId = secure_login.members.id ".
	"and camlogg.WorkProject.UserId = '".$userId."'".
	" and not members.id = '".$userId."'";
	
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
  					"Projectname" => $row['0'],
  					"ProjectId" => $row['3'],					
  					"Username" => $row['1'],
					"UserId" => $row['2'],						
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