<?php



$loginUsername = $_POST['username'];
if(!preg_match('/^[\w_\.\-]+$/', $loginUsername)){
	echo "Username contains invalid characters!";
	//autoreturn to index html
	echo'<meta http-equiv="refresh" content="2;URL=index.html" />';
}

$loginPassword = $_POST['password'];
if(!preg_match('/^[\w_\.\-]+$/', $loginPassword)){
	echo "password contains invalid characters!";
	//autoreturn to index html
	echo'<meta http-equiv="refresh" content="2;URL=index.html" />';
}


$mysqli = new mysqli('localhost','wustl_inst','wustl_pass','newsWebsiteDB');

$stmt = $mysqli->prepare ("SELECT username from userTable");

if(!$stmt){
	printf("Query prep failed: %s\n",$mysqli->error);
			echo'<meta http-equiv="refresh" content="2;URL=index.html" />';
}
$stmt->execute();
$result = $stmt->get_result();
$checkUserValidation = false;


while($row = $result->fetch_assoc()){
	if($row['username'] == $loginUsername){
		$checkUserValidation = true;
		$myquery = "SELECT COUNT(*), usernameId, usernameHashPassword FROM userTable where username ='$loginUsername' ";
		$stmt = $mysqli->prepare($myquery);
		$stmt->execute();

		//wiki to check hashed password
		$stmt->bind_result($cnt, $usernameId, $userPasswordHashed);
		$stmt->fetch();

		if($cnt == 1 && password_verify($loginPassword,$userPasswordHashed)){
			//welcome user and autodirect to homepage!
			session_start();
			//setting session variables to user information
			$_SESSION['username'] = $loginUsername;
			$_SESSION['userid']= $usernameId;
			//hash token for session
			$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(64));
			session_write_close();
			//redirects to homepage automaticly 2sec
			echo"You will be redirected \n";
			echo'<meta http-equiv="refresh" content="2;URL=homepage.php" />';
		}

		else{
			echo " Username not found/ Incorrect Password!";
			//back to index
			echo'<meta http-equiv="refresh" content="2;URL=index.html" />';
			
		}
	}
}

if($checkUserValidation = false){
	echo"user not available";
	//redirects back to index.html
	echo'<meta http-equiv="refresh" content="1;URL=index.html" />';
}



?>
