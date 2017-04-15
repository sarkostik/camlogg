<?php 
/*
select * 
from FlowSiteLarge, UserFlow
where RelationId = '5-3-2017 21:9:47'
and UserFlow.FlowId = FlowSiteLarge.FlowId
and UserFlow.UserId = '2'
*/

$serverRoot = $_SERVER['DOCUMENT_ROOT'];
include_once $serverRoot.'/framework/includes/db_connect.php';
include_once $serverRoot.'/framework/includes/functions.php';
sec_session_start(); 

$sessionUser = $_SESSION['username'];
$isPost = false;
$dbResponse = false;
$rtnMsg = false;

if ($sessionUser !== null){
	if ($_GET['contentId'] != ""){
		
		$file = $_GET['content'];
		$dir = $_GET['contentId'];

		$dbResponse = "/home/olle/uploads/".$dir."/".$file;
		
		/*
		<img style="max-width: 100px" SRC="data:image/gif;base64,<?php echo base64_encode(file_get_contents("/home/olle/test.jpg"));?>">
		*/
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



function listContent(){	
	$userId = $_SESSION['user_id'];
	$relationId = $_GET['relationId'];

	$queryLine = 
	"select * ".
	"from FlowSiteLarge, UserFlow ".
	"where RelationId = '".$relationId."' ".
	"and UserFlow.FlowId = FlowSiteLarge.FlowId ".
	"and UserFlow.UserId = '".$userId."'"
	
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




?>