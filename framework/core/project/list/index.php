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
	$dbResponse = $rtnDbQuery = dbNewProject();			
}
else
	$sessionUser = false;
	

$rtnMsg = array(						
"Response" => $sessionUser,				
"Session" =>$isSession,				
"Projects" => $dbResponse,
);


header('Content-type: application/json');
echo json_encode($rtnMsg, JSON_FORCE_OBJECT);


function dbNewProject(){		
	$userId = $_SESSION['user_id'];

	$queryLine =
	"select Projectname, WorkProject.ProjectId, StartDate, secure_login.members.username, Status, EndDate ".
	"from WorkProject, UserProject, secure_login.members ".
	"where UserProject.UserId = secure_login.members.id ".
	"and WorkProject.ProjectId = UserProject.ProjectId ".
	"and UserProject.UserId='".$userId."' group by ProjectName";

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
  					"ProjectId" => $row['1'],
					"StartDate" => $row['2'],
					"Username" => $row['3'],
					"Status" => $row['4'],
					"EndDate" => $row['5'],
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