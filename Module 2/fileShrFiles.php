<!DOCTYPE html>
<html>
<head>
	<title>Shared Files</title>
</head>
<body>

	<?php
	session_start();
	$username = $_SESSION['username'];


//checks if username is contains only valid characters
$nameFilter = '/^[\w_\-]+$/';
	if(!preg_match($nameFilter, $username))
	{
		print('Username not valid!');
		echo'<meta http-equiv="refresh" content="2;URL=logout.php" />';
		exit;
	}

	$fileExtension = $_POST['file_shr_ext'];
	$file = $_POST['file_shr_name'];

	echo "<br>";


	$file_loc = "/srv/users_upload/file_shared"."/".$file;
	if($fileExtension == 'txt'){
	// header('Content-type: text/plain');
		@readfile($file_loc);
	}



	?>

</body>
</html>