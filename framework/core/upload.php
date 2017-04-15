<?php 
$serverRoot = $_SERVER['DOCUMENT_ROOT'];
include_once $serverRoot.'/framework/includes/db_connect.php';
include_once $serverRoot.'/framework/includes/functions.php';
include_once $serverRoot.'/framework/core/work/decode.php';
sec_session_start(); // Our custom secure way of starting a PHP session.

$fileCreated = false;
$loggedIn = false;	
$isPost = true;		
$mySession = $_SESSION;
$user = $mySession['username'];

if (isset($_FILES['myFile']) && $_POST['p'] && $_POST['email'] && $_POST['workObj']) {
	$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
	$password = $_POST['p']; // The hashed password.
	
	if ($user != null || $user != "") {
    	// Login success 	        
    	$loggedIn = true;
    	$workObj = decodeWorkObj($_POST['workObj']);
    	$company = decodeWorkObj($_POST['companyObj']);
        $files = $_FILES['myFile']['name'];
		$uploadPath = $serverRoot."/uploads/";		
		$project = $workObj->projectId;
		$flow = $workObj->workFlowId;

		$path = $uploadPath."/".$user."/".$company->companyId."/".$project."/".$flow."/";

		if (!file_exists($path)){			
			mkdir($path, 0777, true);
			mkdir($path."/thumbs", 0777, true);
		}

		$kluff = date("YmdHis");



		$path = '/home/olle/uploads/';

		if (move_uploaded_file($_FILES['myFile']['tmp_name'], $path. $kluff.$_FILES['myFile']['name'])) {			
			$fileCreated = true; 
			$msg = "completed";
			if($isStamp = db_stamp($kluff, $files, $email))
				$isStamp = array('id' => $kluff, 'file' => $files);
			else
				$isStamp = false;
		} 			
    }
    else 	
    	$msg = "Invalid userCredentials!";
}
else{
	$isPost = false;
	$msg = "no post";			
}

$rtnMsg = array(	
"Session" => $user,
"Response" => $isPost,				
"Login" => $loggedIn,
"FileCreated" => $fileCreated,
"Message" => $msg,
"Debug" => $workObj,
"Timestamp" => $isStamp
);	


header('Content-type: application/json');
echo json_encode($rtnMsg, JSON_FORCE_OBJECT);
?>

<?php

function getExtension($str) {
    $i = strrpos($str,".");
    if (!$i) { return ""; }
    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return strtolower($ext);
}    

function db_stamp($id, $fileName, $userid){
		
	try {
    	$conn = new PDO("mysql:dbname=camlogg;charset=utf8", USER, PASSWORD);
    	// set the PDO error mode to exception
    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		

		$query = $conn->prepare("insert into Image (`id`,`filename`,`userid`) values".
		"('".$id."','".$fileName."','".$userid."')");
    	
		return $query->execute();  					
    }
	catch(PDOException $e)
	{		
		return $e->getMessage();
    }

}

function make_thumb($src, $dest, $desired_width) {
	try{
		/* read the source image */
		$source_image = imagecreatefromjpeg($src);
		$width = imagesx($source_image);
		$height = imagesy($source_image);
		
		/* find the "desired height" of this thumbnail, relative to the desired width  */
		$desired_height = floor($height * ($desired_width / $width));
		
		/* create a new, "virtual" image */
		$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
		
		/* copy source image at a resized size */
		imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
		
		/* create the physical thumbnail image to its destination */
		imagejpeg($virtual_image, $dest);
		return true;
	}
	catch(Execption $ex){
		return false;
	}	
}

function restructureFilesArray($files)
{
    $output = [];
    foreach ($files as $attrName => $valuesArray) {
        foreach ($valuesArray as $key => $value) {
            $output[$key][$attrName] = $value;
        }
    }
    return $output;
}
?>