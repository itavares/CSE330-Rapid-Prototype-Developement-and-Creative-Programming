<?php

 $mysqli = new mysqli('localhost','wustl_inst','wustl_pass','newsWebsiteDB');
$filter = '/^[a-z0-9.-]+$/i';
$newUsername = $mysqli ->real_escape_string($_POST['registerUsername']);
if( !preg_match($filter, $newUsername) ){
	echo "Username contains invalid characters! Numbers and letters only !<br>";
	//automatical returns to index.html
	die();
	 echo'<meta http-equiv="refresh" content="6;URL=index.html" />';
	 
}

$userRegisterPassword = $mysqli ->real_escape_string($_POST['userPassword']);
if( !preg_match($filter, $userRegisterPassword) ){
	echo "--> Username contains invalid characters! Numbers and letters only! ";
	die();
	 echo'<meta http-equiv="refresh" content="6;URL=index.html" />';
}
$userRegisterPasswordCheck = $mysqli ->real_escape_string($_POST['userPasswordCheck']);
if( !preg_match($filter, $userRegisterPasswordCheck) ){
	echo "-->Password contains invalid characters! Numbers and letters only!<br> ";
	die();
	 echo'<meta http-equiv="refresh" content="6;URL=index.html" />';
}

//compares both passwords to ensure they are the same for registration

if($userRegisterPasswordCheck != $userRegisterPassword ){
	echo "-->Passwods don't match! Please try again. <br>";
	die();
	echo'<meta http-equiv="refresh" content="6;URL=index.html" />';
}

//select the users from the table containing user's infomation
$stmt = $mysqli->prepare ("SELECT username from userTable");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
}

// check for valid username if exists in table
$checkUsernameValidation = false;
$stmt -> execute();
$result = $stmt->get_result();
while($row = $result->fetch_assoc()){
	if($row['username']==$newUsername){
		$checkUsernameValidation = true;
		echo "This username is being used! Please, chose a different name!";
		//redirects to index.html once again
		// echo'<meta http-equiv="refresh" content="3;URL=index.html" />';

	}
}


//if user is not in database, add new user and password
if($checkUsernameValidation == false){
	$stmt = $mysqli->prepare("INSERT INTO userTable (username,usernameHashPassword) VALUES(?,?)");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
	}

	$userPasswordHashed = password_hash($userRegisterPassword, PASSWORD_BCRYPT);
	$stmt->bind_param('ss',$newUsername,$userPasswordHashed);
	$stmt->execute();
	$stmt->close();
}
else{
	echo "Something went wrong...";
	echo'<meta http-equiv="refresh" content="2;URL=index.html" />';
}

// Lets user know account was created with sucess 
echo "New User Created!";
echo'<meta http-equiv="refresh" content="2;URL=index.html" />';
// echo'<meta http-equiv="refresh" content="2;URL=index.html" />';

?>



