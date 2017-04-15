<?php

class TimeStamp {

	public function setStamp($uuid, $fileName, $flowId,$userid, $identifier){		

		$myDate = date("Y-m-d H:i:s");      
		$identifier = $_GET['identifier'];

		$flowData = "insert into FlowSiteLarge (`Id`,`Content`,`UserId`,`DateTime`, `TypeContent`, `FlowId`, `ProjectId`,`RelationId`)".
		" values ('".$uuid."','".$fileName."','".$userid."','".$myDate."','File','".$flowId."','some','".$identifier."')";

		$sqlLine = "insert into Files (`id`,`filename`,`userid`,`datetime`) values".
			"('".$uuid."','".$fileName."','".$userid."','".$myDate."');".$flowData;

		return $this->db_stamp($sqlLine);

	}

	public function setRelation($uuid, $comment, $flowId){
		//setRelation($_POST['uuid'], $_POST['comment'], $_POST['flowId']);

		$myDate = date("Y-m-d H:i:s");      
		$identifier = $_GET['identifier'];
		$userid = $_SESSION['user_id'];

		$sqlLine = "insert into FlowSiteLarge (`Id`,`Content`,`UserId`,`DateTime`, `TypeContent`, `FlowId`, `ProjectId`,`RelationId`)".
		" values ('".$uuid."','".$comment."','".$userid."','".$myDate."','SubmitFiles','".$flowId."','some','".$uuid."')";

		return $this->db_stamp($sqlLine);
	}


	function db_stamp($sqlLine){		
		
		try {
	    	$conn = new PDO("mysql:dbname=camlogg;charset=utf8");
	    	// set the PDO error mode to exception
	    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		

			$query = $conn->prepare($sqlLine);
	    	
			return $query->execute();  					
	    }
		catch(PDOException $e)
		{		
			return $e->getMessage();
	    }
	}
}


?>