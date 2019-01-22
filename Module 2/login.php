<?php

$username = $_POST['username'];

// https://stackoverflow.com/questions/4103287/read-a-plain-text-file-with-php
// Similar concept, where I was able to open a file and read through the contents, adding all the usernames in it into an array where I could check with the user input if it matches with the name in the file.

$loginfile = array();  

$openfile = fopen("/home/itavares/module1_user/users.txt", "r"); //"read"
$readline  = 1; //read one line at a time 

while (!feof($openfile)){
	//goes to next line in the text file
	$readline++;
	$getFile = fgets($openfile);
	$trimFileName = trim($getFile);
	// pushes into the array the names in the textfile
	array_push($loginfile, $trimFileName);
}

//checks if username matches the username in text files. 
if(in_array($username, $loginfile)){
	session_start();
	$_SESSION['username'] = $username;
	echo"Login Sucessful!";
	echo "<br> You will be redirected to your homepage!";
	echo'<meta http-equiv="refresh" content="2;URL=userFiles.php" />';

}else{
	print("user not exist! ");
	//redirects page to index.html automatically
	echo'<meta http-equiv="refresh" content="2;URL=index.html" />';
}






?>
