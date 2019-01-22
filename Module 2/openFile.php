<!DOCTYPE html>
<html>
<head>
	<title>Open File</title>
	<link rel="stylesheet" href="style.css">
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


	$fileExtension = $_POST['file_ext'];
	$file = $_POST['file_name'];
	$getFile = $_POST['file_'];
	echo "<br>";

	if($getFile == "Open"){
		$fileLocation = "/srv/users_upload/".$username."/".$file;
		// echo ($fileExtension);
		// var_dump($fileExtension);

		switch ($fileExtension) {
			case "txt":
				echo"<a href = 'userFiles.php'> Go Back</a> ";
				echo "<br>";
				echo "<br>";
				@readfile($fileLocation);
				break;

			case "pdf":
				echo "b";
				header('Content-type: application/pdf');
				echo"<a href = 'userFiles.php'> Go Back</a> ";
				echo "<br>";
				echo "<br>";
				@readfile($fileLocation);
				break;

			case "jpeg":
				echo "b";
				echo"<a href = 'userFiles.php'> Go Back</a> ";
				ob_clean(); 
				header('Content-type: image/jpeg');
				@readfile($fileLocation);

				break;

			case "jpg":
				echo "b";
				echo"<a href = 'userFiles.php'> Go Back</a> ";
				ob_clean(); 
				header('Content-type: image/jpg');
				@readfile($fileLocation);

				break;

			case "png":
				echo "b";
				echo"<a href = 'userFiles.php'> Go Back</a> ";
				ob_clean(); 
				header('Content-type: image/png');
				@readfile($fileLocation);
				break;

			case "xlsx":
				echo "b";
				echo"<a href = 'userFiles.php'> Go Back</a> ";
				echo "<br>";
				header('Content-type: application/xlsx');
				@readfile($fileLocation);
				break;											
			default:
				echo "nothing";
				break;
		}
		

	}
	elseif ($getFile == "Delete") {
		//delete file
		$delFileLoc = "/srv/users_upload/".$username."/".$file;
		if(unlink($delFileLoc)){
			echo "File Deleted with Sucess!";
			echo'<meta http-equiv="refresh" content="2;URL=userFiles.php" />';
		}
		else {
			echo"something went wrong!";
			echo'<meta http-equiv="refresh" content="2;URL=userFiles.php" />';
		}

	}
	elseif ($getFile == "Share") {
		$org_fileLocation = "/srv/users_upload/".$username."/".$file;
		$file_shr_loc = "/srv/users_upload/file_shared/".$file;
		if(copy($org_fileLocation, $file_shr_loc)){

			echo"file shared!";
			echo'<meta http-equiv="refresh" content="2;URL=userFiles.php" />';
			echo"You will be automatically redirected...";
		}

	
	}

	?>












</body>
</html>


