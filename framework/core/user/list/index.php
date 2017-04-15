<?php 

$serverRoot = $_SERVER['DOCUMENT_ROOT'];
include_once $serverRoot.'/framework/includes/db_connect.php';
include_once $serverRoot.'/framework/includes/functions.php';
sec_session_start(); 

$sessionId = $_SESSION['user_id'];
//$sessionId = 1;
$isPost = false;
$dbResponse = false;
$rtnMsg = false;

if ($sessionId !== null){
	$dbResponse = $rtnDbQuery = dbNewProject($sessionId);			
}
else
	$sessionUser = false;
	

$rtnMsg = array(						
"Response" => true,				
"Session" =>$sessionId,				
"Users" => $dbResponse,
);


header('Content-type: application/json');
echo json_encode($rtnMsg, JSON_FORCE_OBJECT);


function dbNewProject($sessionId){		
	$userId = $_SESSION['user_id'];

	$queryLine =
	"SELECT username, secure_login.members.id ".
	"FROM secure_login.members, secure_login.groups ".
	"where groups.id = members.id ".
	"and groups.memberid = '".$sessionId."' ".
	"and not members.id = '".$sessionId."'";
	
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
  					"Username" => $row['0'],
					"UserId" => $row['1'],		
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