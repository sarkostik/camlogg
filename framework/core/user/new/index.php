<?php 

	$isLibLoaded = false;
	$serverRoot = $_SERVER['DOCUMENT_ROOT'];
	include_once $serverRoot.'/framework/includes/db_connect.php';
	include_once $serverRoot.'/framework/includes/functions.php';
	sec_session_start(); 

	$userCreated = false;
	$isSession = false;
	$session = $_SESSION['username'];	
	$error_msg = "error";
	$debug = "next line thank you";
	$isPost = false;
	$isLibLoaded = true;
	$a = "";

	$accesslevel = filter_input(INPUT_POST, 'selectAccess', FILTER_SANITIZE_STRING);
	$myproj = filter_input(INPUT_POST, 'projects', FILTER_SANITIZE_STRING);
	$count = strlen($myproj);

	$projArr = array();
	$c = "";
	$y = 0;

	for ($x = 0; $x < $count; $x++){
		if ($myproj[$x] != ","){
			$c .= $myproj[$x];
		}
		else{
			$projArr[$y] = $c;
			$c = "";
			$y++;
		}
	}
	$projArr[$y] = $c;
	
	if($session !== null){
		$isSession = true;
		$error_msg = "";

		if (isset($_POST['email'])){
			$isPost = true;		

			if (userLevel()){

				$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
			    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
			    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
			    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {		        
			        $error_msg .= 'Du har inte angett en korrekt E-mailadress';
			    }

			    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
			    if (strlen($password) != 128) {		        
			        $error_msg .= 'Fel lösenordskonfiguration';
			    }

				$prep_stmt = "SELECT id FROM members WHERE email = ? LIMIT 1";
			    $stmt = $mysqli->prepare($prep_stmt);
		    
			    if ($stmt) {
			        $stmt->bind_param('s', $email);
			        $stmt->execute();
			        $stmt->store_result();
			        
			        if ($stmt->num_rows == 1) {		            
			            $error_msg .= 'Det finns redan en användare med denna emailadress';
			        }
			    } else {
			        $error_msg .= 'databas fel';
			    }

			     if (empty($error_msg)) {		        
			        $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
			        
			        $password = hash('sha512', $password . $random_salt);
			        
			        if ($insert_stmt = $mysqli->prepare("INSERT INTO members (username, email, password, salt) VALUES (?, ?, ?, ?)")) {
			            $insert_stmt->bind_param('ssss', $username, $email, $password, $random_salt);		            
			            $userCreated = $insert_stmt->execute();
			        }

			        $prep_stmt = "SELECT id FROM members WHERE email = ? LIMIT 1";
				    $stmt = $mysqli->prepare($prep_stmt);
				    
				    if ($stmt) {
				        $stmt->bind_param('s', $email);
				        $stmt->execute();
				        $stmt->store_result();
			            $stmt->bind_result($a);
	        			$stmt->fetch();
				    }
				    
				   	$myUserId = $_SESSION['user_id'];
					$portal = "group";
					try {
				    	$conn = new PDO("mysql:charset=utf8", USER, PASSWORD);
				    	// set the PDO error mode to exception
				    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		

						$query = $conn->prepare("INSERT INTO camlogg.UserProject (`ProjectId`,`UserId`) VALUES (?,?)");
				    	
				    	for ($x = 0; $x <= $y; $x++){
				    		$query->execute(array($projArr[$x], $a));  						
				    	}					

				    	$query2 = $conn->prepare("INSERT INTO secure_login.groups (id, memberid, accesslevel, portal) VALUES (?, ?, ?, ?)");
				    	$query2->execute(array($a, $myUserId, $accesslevel,$portal));  		

				    }
					catch(PDOException $e)
					{					
						$error_msg .= $e->getMessage();
				    }
				}						
		    }	
		    else
		    	$error_msg = "Du har inte behörighet för att skapa en användare!";
		}
	}

	$counter = sizeof(projArr);
	$rtnMsg = array(						
	"Response" => $isLibLoaded,				
	"Session" => $isSession,
	"Post" => $isPost,
	"UserCreated" => $userCreated,
	"Message" => $error_msg,
	"Message2" => $msg,
	"Debug" => $_SESSION['user_id'],
	"Debug2" => $y,
	"UserId" => $myUserId,	
	"IdCreatedUser" => $a,	
	);	

	header('Content-type: application/json');
	echo json_encode($rtnMsg, JSON_FORCE_OBJECT);
?>

<?php

function validateString(){

	$filters = array
	  (
	  "username" => array
	    (
	    "filter"=>FILTER_CALLBACK,
	    "flags"=>FILTER_FORCE_ARRAY,
	    "options"=>"ucwords"
	    ),
	    "projects" => array
	    (
	    "filter"=>FILTER_CALLBACK,
	    "flags"=>FILTER_FORCE_ARRAY,
	    "options"=>"ucwords"
	    ),
	  "selectAccess" => array
	    (
	    "filter"=>FILTER_VALIDATE_INT,
	    "options"=>array
	      (
	      "min_range"=>1,
	      "max_range"=>3
	      )
	    ),
	  "email"=> FILTER_VALIDATE_EMAIL,
	  );
	return(filter_input_array(INPUT_POST, $filters));
}


function userLevel(){
	
	$myUserId = $_SESSION['user_id'];
	$portal = "group";
	try {
		$conn = new PDO("mysql:charset=utf8", USER, PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
		$query = $conn->prepare("select accesslevel from secure_login.groups where id='".$myUserId."';");
	
		$rtn = "";
		$x = 0;
		if($query->execute()){			
			while ($row = $query->fetch()) {
  				$rtn = $row['0'];								
  			}				
		}
		
		if ($rtn == "2")
			return true;
		else 
			return false;
		
	}
	catch(PDOException $e)
	{					
		$error_msg .= $e->getMessage();
	}

	return false;
}



?>