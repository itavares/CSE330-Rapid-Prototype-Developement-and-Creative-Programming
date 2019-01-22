<?php
	header("Content-Type: application/json");


	//retrive from login ajax function
	$usernameLogin = $_POST['usernameLogin'];
	$usernamePassword = $_POST['usernamePassword'];
	$startSession = false;
	// var_dump($usernameLogin );
	// var_dump($usernamePassword);


	//mysql connection
	$mysqli = new mysqli('localhost','wustl_inst','wustl_pass','calendarEvents');

	$myquery = "SELECT COUNT(*),usernameId, usernamePasswod FROM userTable WHERE username='$usernameLogin'  ";
	$stmt=$mysqli->prepare($myquery);
	if(!$stmt){
				echo json_encode(array(
					"sucess" =>false,
					"message"=>"query2 prep failed: %s",$mysqli ->error
				));
				exit;
			}
	$stmt->execute();
	$stmt->bind_result($cnt,$usernameId,$usernameHashedPassword);
	$stmt->fetch();
	//module 3, how to check for valid user and password
	if($cnt == 1 && password_verify($usernamePassword,$usernameHashedPassword)){
		ini_set("session.cookie_httponly", 1);
		session_start();
		//sets sessions userid
		$_SESSION['userid']=$usernameId;
		//sets sessions of user
		$_SESSION['username']=$usernameLogin;
		//generates token
		$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); //generate token
		$token = $_SESSION['token'];
		echo json_encode(array(
					'sucess'=>true,
					'login'=>true,
					'userid' =>$_SESSION['userid'], 
					'sessionToken'=>$token
				));
		exit;
	}
	else{
		echo json_encode(array(
					"sucess" =>false,
					'message'=> "Invalid loging (Check username or password)!"
				));
	}

?>