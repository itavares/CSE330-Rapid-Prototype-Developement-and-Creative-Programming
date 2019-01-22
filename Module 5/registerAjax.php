 
<?php
header("Content-Type: application/json");
ini_set("session.cookie_httponly",1);

$mysqli = new mysqli('localhost','wustl_inst','wustl_pass','calendarEvents');

$pattern = '/^[\w_\.\-]+$/';

$usernameLogin = $mysqli->real_escape_string($_POST['usernameLogin']);

//escape login name for unlike characters
if(!preg_match($pattern, $usernameLogin)){
	echo json_encode(array(
		"sucess" => false,
		"message" => "Invalid Username (check for special characters)"
	));
	exit;
}

$usernamePassword = $mysqli->real_escape_string($_POST['usernamePassword']);
if(!preg_match($pattern, $usernamePassword)){
	echo json_encode(array(
		"sucess" => false,
		"message" => "Invalid Password (check for special characters)"
	));
	exit;
}


$stmt=$mysqli->prepare("SELECT username FROM userTable");
if(!$stmt){

	echo json_encode(array(
		"sucess" =>false,
		"message"=>"query1 prep failed: %s",$mysqli->error

	));
	exit;
}
$stmt->execute();
// $stmt->close();
$fecthResult=$stmt->get_result();
//boolean to keep track of valid user
$validUsername = false;
//fetch all users
while($row=$fecthResult->fetch_assoc()){
	if($row['username'] == $usernameLogin){
		//user already exists
		$validUsername = true;
		echo json_encode(array(
			"sucess" =>false,
			"message"=>"Username already been used!"
		));
		exit;
	}
}
	 if($validUsername == false){
	 	$usernamePassword = password_hash($usernamePassword, PASSWORD_BCRYPT);
	 	$myquery = "INSERT INTO userTable (username,usernamePasswod) VALUES ('$usernameLogin','$usernamePassword')";
	 	$stmt=$mysqli->prepare($myquery);


		if(!$stmt){
			echo json_encode(array(
				"sucess" =>false,
				"message" =>"query2 prep failed: %s",$mysqli->error

			));
			exit;
		}


		$stmt->execute();
		$stmt->close();
		echo json_encode(array(
			"sucess" =>true,
			"message" =>""
		));
	}


?>