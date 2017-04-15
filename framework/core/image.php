<?php 
$serverRoot = $_SERVER['DOCUMENT_ROOT'];
include_once $serverRoot.'/framework/includes/db_connect.php';
include_once $serverRoot.'/framework/includes/functions.php';
sec_session_start(); // Our custom secure way of starting a PHP session.


$fileCreated = false;
$loggedIn = false;	
$isPost = true;		

if (isset($_POST['image']) && $_POST['p'] && $_POST['email']) {
	$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
	$password = $_POST['p']; // The hashed password.
	if (login($email, $password, $mysqli) == true) {
    	// Login success 	        
    	$loggedIn = true;   	

		$kluff = date("YmdHis");

		$msg2 = $_POST['image'];
		$msg = json_decode($msg2);
		
		$fileName = $msg->{'imageName'};
		$width = $msg->{'width'};
		$height = $msg->{'height'};
		$id = $msg->{'id'};
		$drawHistory = $msg2;
		//$drawModes = $msg[1]->{'drawModes'};


		//$msg = $msg->{'drawHistory'};
		//$msg = $msg[0];
		//$msg = $msg[0];
		//$x = $msg->{'x'};
		//$y = $msg->{'y'};

		$servername = "localhost";
		
		try {
	    	$conn = new PDO("mysql:dbname=camlogg;charset=utf8", USER, PASSWORD);
	    	// set the PDO error mode to exception
	    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		

			$query = $conn->prepare("insert into EditImage (`id`,`filename`,`width`,`height`,`drawhistory`,`timeStamp`,`userid`) values".
			"('".$id."','".$fileName."','".$width."','".$height."','".$drawHistory."','".$kluff."','".$email."')");
	    	
			$query->execute();  					
	    }
		catch(PDOException $e)
		{
			//$rtnResponse = "False";
			$alert = $e->getMessage();
	    }
		
    }
    else 	
    	$alert = "Invalid userCredentials!";
}
else{
	$isPost = false;
	$alert = "no post";			
}

$rtnMsg = array(						
"Response" => $isPost,				
"Login" => $loggedIn,
"FileCreated" => $fileCreated,
"Message" => $msg,
"Debug" => $alert
);	


header('Content-type: application/json');
echo json_encode($rtnMsg, JSON_FORCE_OBJECT);
?>

<?php

function db_stamp($imgUrl, $usr, $image){


	$mysqli = new mysqli("localhost", "omob", "!gargoyle12","psn");
	$binary = file_get_contents($_FILES['myFile']['tmp_name']);

	$finfo = new finfo(FILEINFO_MIME);
    $type = $finfo->file($_FILES['myFile']['tmp_name']);
    $mime = substr($type, 0, strpos($type, ';'));

    $date = date("Y-m-d H:i:s");	
    $serviceId = 1;

//    $query = "INSERT INTO `ImageUrls` (`Image`,`Mime`,`ClientId`,`ImgD`) VALUES('".$mysqli->real_escape_string($binary)."','".$mysqli->real_escape_string($mime)."','".$usr. "','".$date."')";


    $query = "INSERT INTO `ImageUrls` (`ImageUrl`,`ClientId`,`ServiceId`,`ImgD`,`Image`) VALUES('"
	    .$imgUrl."','"
	    .$usr."','"
	    .$serviceId."','"
	    .$date. "','"
	    .$image."')";


    $mysqli->query($query);


	/*$connection = mysql_connect("localhost", "omob", "!gargoyle12");
	$date = date("Y-m-d H:i:s");	
	$db = mysql_select_db("psn", $connection);
	$query = "insert into ImageUrls (ImgUrl, ClientId, ServiceId, ImgD, Image) values ('$imgUrl', '$usr', '1', '$date', '$image')";
	$result = mysql_query($query);*/

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