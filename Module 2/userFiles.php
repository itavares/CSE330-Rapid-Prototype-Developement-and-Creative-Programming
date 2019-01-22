<!DOCTYPE html>
<html lang="en">
<head>
	<title>Files</title>
	<link rel="stylesheet" href="style.css">
	<!-- Buttons design by w3schools. -->
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>

	<?php
	session_start();

	$usernameLogin = $_SESSION['username'];


//checks if username is contains only valid characters
	if(!preg_match('/^[\w_\-]+$/', $usernameLogin))
	{
		print('Username not valid!');
		header('Location: index.html');

	}

	printf("<h2> %s's Files </h2>",htmlentities( $usernameLogin));
	echo'<form class="userFiles" action="logout.php">
			<input type="submit" value="Logout">
		</form>';

	echo"<br>";



echo "<div class='userFilesLayout' >";


//Debug , check if files are in the array
// print_r($fileArrFilterred);
	echo"<br>";


//check if admin is logged in:
if($usernameLogin == "admin"){
	$usersNames = array('ighor', 'sanchez', 'jessie','file_shared' );
	$arrLength = count($usersNames);
	$filterArray = array('.','..');
	
	$userFileLoc1 = '/srv/users_upload/'.$usersNames[0];
	$userScan1 = scandir($userFileLoc1);
	$userFilter1 = array_diff($userScan1, $filterArray);

	$userFileLoc2 = '/srv/users_upload/'.$usersNames[1];
	$userScan2 = scandir($userFileLoc2);
	$userFilter2 = array_diff($userScan2, $filterArray);

	$userFileLoc3 = '/srv/users_upload/'.$usersNames[2];
	$userScan3 = scandir($userFileLoc3);
	$userFilter3 = array_diff($userScan3, $filterArray);

	$userFileLoc4 = '/srv/users_upload/'.$usersNames[3];
	$userScan4 = scandir($userFileLoc4);
	$userFilter4 = array_diff($userScan4, $filterArray);

	$generalFileIndex1 = 1;
	//iterate through each user:
	echo "<h4>".htmlentities(($usersNames[0]))."'s files </h4>";
	echo "<br>";
	foreach ($userFilter1 as $key => $filename) {
		//Echos a index number and the file's name 
		echo "<h3>";
		echo htmlentities(($generalFileIndex1));
		echo "- ";
	
		echo htmlentities(($filename))."</h3>";

		// https://stackoverflow.com/questions/28996879/splfileinfo-get-filename-without-extension 
		// SplFileInfo returns the file's name without extension
		$getExtenstion = new SplFileInfo($filename);
		//get's extension from file name
		$files = pathinfo($getExtenstion->getFilename(),PATHINFO_EXTENSION);
		echo"<form class='userFiles' action='adminHandler.php' method='POST' enctype='multipart/form-data' > 
		<input type='hidden' name='file_name' value= '$filename'/>
		<input type='hidden' name='file_ext' value= '$files'/>
		<input type='hidden' name='usersNames' value= '$usersNames[0]'/>
		<input type='submit' name='file_' value='Open'/>
		<input type='submit' name='file_' value='Delete'/>
		</form>";

	       //interate through all the files in the folder
		$generalFileIndex1++;
	}
	
	echo "<br>";
	$generalFileIndex2 = 1;
	echo "<h4>".htmlentities(($usersNames[1]))."'s files</h4>";;
	echo "<br>";
	foreach ($userFilter2 as $key => $filename) {
		echo "<h3>";
		echo htmlentities(($generalFileIndex2));
		echo "- ";

		//prints user's files.
		echo htmlentities(($filename))."</h3>";
				// https://stackoverflow.com/questions/28996879/splfileinfo-get-filename-without-extension 
		// SplFileInfo returns the file's name without extension
		$getExtenstion = new SplFileInfo($filename);
		//get's extension from file name
		$files = pathinfo($getExtenstion->getFilename(),PATHINFO_EXTENSION);
		echo"<form class='userFiles' action='adminHandler.php' method='POST' enctype='multipart/form-data' > 
		<input type='hidden' name='file_name' value= '$filename'/>
		<input type='hidden' name='file_ext' value= '$files'/>
		<input type='hidden' name='usersNames' value= '$usersNames[1]'/>
		<input type='submit' name='file_' value='Open'/>
		<input type='submit' name='file_' value='Delete'/>
		</form>";

	       //interate through all the files in the folder
		$generalFileIndex2++;
	}


	echo "<br>";
	$generalFileIndex3 = 1;
	echo "<h4>".htmlentities(($usersNames[2]))."'s files</h4>";
	echo "<br>";
	foreach ($userFilter3 as $key => $filename) {
		echo "<h3>";
		echo htmlentities(($generalFileIndex3));
		echo "- ";
		//prints user's files.
		echo htmlentities(($filename))."</h3>";
				// https://stackoverflow.com/questions/28996879/splfileinfo-get-filename-without-extension 
		// SplFileInfo returns the file's name without extension
		$getExtenstion = new SplFileInfo($filename);
		$files = pathinfo($getExtenstion->getFilename(),PATHINFO_EXTENSION);
		echo"<form class='userFiles' action='adminHandler.php' method='POST' enctype='multipart/form-data' > 
		<input type='hidden' name='file_name' value= '$filename'/>
		<input type='hidden' name='file_ext' value= '$files'/>
		<input type='hidden' name='usersNames' value= '$usersNames[2]'/>
		<input type='submit' name='file_' value='Open'/>
		<input type='submit' name='file_' value='Delete'/>
		</form>";

	       //interate through all the files in the folder
		$generalFileIndex3++;
	}
	echo "<br>";
	$generalFileIndex4 = 1;
	echo "<h4>".htmlentities(($usersNames[3]))."'s files</h4>";
	echo "<br>";
	foreach ($userFilter4 as $key => $filename) {
		echo "<h3>";
		echo htmlentities(($generalFileIndex4));
		echo "- ";

		//prints user's files.
		echo htmlentities(($filename))."</h3>";
				// https://stackoverflow.com/questions/28996879/splfileinfo-get-filename-without-extension 
		// SplFileInfo returns the file's name without extension
		$getExtenstion = new SplFileInfo($filename);
		//get's extension from file name
		$files = pathinfo($getExtenstion->getFilename(),PATHINFO_EXTENSION);
		echo"<form class='userFiles' action='adminHandler.php' method='POST' enctype='multipart/form-data' > 
		<input type='hidden' name='file_name' value= '$filename'/>
		<input type='hidden' name='file_ext' value= '$files'/>
		<input type='hidden' name='usersNames' value= '$usersNames[3]'/>
		<input type='submit' name='file_' value='Open'/>
		<input type='submit' name='file_' value='Delete'/>
		</form>";

	       //interate through all the files in the folder
		$generalFileIndex4++;
	}

}
else{
	//Upload new file button
	//only appears to regular users, not admin!
	echo "<div> ";
	echo "<br>";
		echo '<form class="userFiles" action="fileUpload.html" method="POST" enctype="multipart/form-data">
		<input type="submit" name="upload" value="Upload New File" />
		</form> </div>';

	$filterFiles = array('.','..');
	$fileLocation = '/srv/users_upload/'.$_SESSION['username']; 
	$fileScanDir = scandir($fileLocation);
	$fileArrFilterred = array_diff($fileScanDir,$filterFiles);


	$folderIndex = 1; //index to iterate through array

	foreach ($fileArrFilterred as $index => $file_name) {
		//print files here of user
		echo "<h3>";
		echo htmlentities(($folderIndex));
		echo "- ";

		//prints user's files.
		echo htmlentities(($file_name))."</h3>";
				// https://stackoverflow.com/questions/28996879/splfileinfo-get-filename-without-extension 
		// SplFileInfo returns the file's name without extension
		$getExtenstion = new SplFileInfo($file_name);
		//get's extension from file name
		$files = pathinfo($getExtenstion->getFilename(),PATHINFO_EXTENSION);
		echo"<br>";

		echo"<form class='userFiles' action='openFile.php' method='POST' enctype='multipart/form-data' > 
		<input type='hidden' name='file_name' value= '$file_name'/>
		<input type='hidden' name='file_ext' value= '$files'/>
		<input type='submit' name='file_' value='Open'/>
		<input type='submit' name='file_' value='Delete'/>
		<input type='submit' name='file_' value='Share'/>
		</form>";

	       //interate through all the files in the folder
		$folderIndex++;
	}


	echo "<br>";


	//SHARED FOLDER
	echo"<h3> Shared Files </h3>";

	$file_shared_loc = '/srv/users_upload/file_shared';
	$files_shr = scandir($file_shared_loc);
	$filtered_shr_arr = array_diff($files_shr, array('.','..'));
	$file_shr_index = 1;

	//Debug , check if files are in the array
	// print_r($filtered_shr_arr);


	foreach ($filtered_shr_arr as $index => $file_shr_name) {
		//print files here of user
		echo "<h3>";
		echo htmlentities(($file_shr_index));
		echo "- ";
		//prints user's files.
		echo htmlentities(($file_shr_name))."</h3>";
				// https://stackoverflow.com/questions/28996879/splfileinfo-get-filename-without-extension 
		// SplFileInfo returns the file's name without extension
		$getExtenstion = new SplFileInfo($file_shr_name);
		//get's extension from file name
		$files = pathinfo($getExtenstion->getFilename(),PATHINFO_EXTENSION);
		echo"<br>";
		echo"<form class='userFiles' action='fileShrFiles.php' method='POST' enctype='multipart/form-data' > 
		<input type='hidden' name='file_shr_name' value= '$file_shr_name'/>
		<input type='hidden' name='file_shr_ext' value= '$files'/>
		<input type='submit' name='file_' value='Open'/>
		</form>";

	       //iterate through the files in the shared folder
		$file_shr_index++;
		}
}

echo "</div>";

?>
</body>
</html>