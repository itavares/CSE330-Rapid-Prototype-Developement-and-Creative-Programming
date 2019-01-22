<?php
session_start();

$username = $_SESSION['username'];
$validFile = 0;

// $bool_var = TRUE;
if (isset($_POST['upload'])){



	$fileNameUser = basename($_FILES['upload_file']['name']);
	//preg_match only allows letters and numbers and one period(.) on the filename with the execption of dashes. e.j (this-is-my-file.txt).
	if( !preg_match('/^[a-z0-9.-]+$/i', $fileNameUser) || substr_count($fileNameUser, '.') > 1 ){
		echo"1";
		printf( "Invalid filename");
		echo '<br>';
		echo"Redirecting to homepage...";
		echo'<meta http-equiv="refresh" content="2;URL=userFiles.php" />';
		exit;
	}
	else{
		$validFile = 1;
	}
	echo($fileNameUser);
	


	$fileExtension = explode('.', $fileNameUser);
	$fileLowerCaseExtension=strtolower(end($fileExtension));

	echo"<br>";
	$uploadeNameForFile = $_FILES['upload_file']['tmp_name'];
	echo "<br>";


	// echo($fileLowerCaseExtension);

	//https://www.w3schools.com/php/php_file_upload.asp  **** I used the filter the types of file allowed from w3school ****
	$file_types = array('txt','jpg','jpeg','png','pdf','xlsx');
	$fileExtensionension = pathinfo($fileNameUser, PATHINFO_EXTENSION);
	if($validFile == 1){

		if(in_array($fileLowerCaseExtension, $file_types)) {
			$fileLocation =sprintf("/srv/users_upload/%s/%s",$username, $fileNameUser);
			if(move_uploaded_file($uploadeNameForFile, $fileLocation)) {
				echo " File Uploaded With Success!! ";
				echo  "<br>";
				echo " Auto Redirect... ";
		echo'<meta http-equiv="refresh" content="1;URL=userFiles.php" />';
			}
			else{
				echo "<br>";
				echo"something went wrong";
			}
		}
		else{
			echo"extension not permited";
			echo'<meta http-equiv="refresh" content="1;URL=userFiles.php" />';
		}

	}
	else {
		echo"File not uploaded. File should have only letters and numbers in the name!";
		echo'<meta http-equiv="refresh" content="1;URL=userFiles.php" />';
	}


}




?>
