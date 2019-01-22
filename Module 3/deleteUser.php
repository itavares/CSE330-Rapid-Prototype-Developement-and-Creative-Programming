<?php
	//this feature deletes everything from the database 
	echo "Make sure you delete all your stories first! ";
	session_start();
	$mysqli = new mysqli('localhost','wustl_inst','wustl_pass','newsWebsiteDB');
	$sessionUsername = $_POST['username'];
	$sessionToken =  $_SESSION['token'];

	if($_SESSION['token'] !== $sessionToken){
	echo "Error in token validation!";
	session_destroy();
	echo'<meta http-equiv="refresh" content="2;URL=index.html" />';
}
	//deletes comments first from story
	$myquery = "DELETE FROM newsWebsiteDB.commentsTable WHERE commentsTable.usernameId='$sessionUsername'";
		$stmt = $mysqli->prepare($myquery);


		if(!$stmt){
			printf("query prep failed: %s", $mysqli->error);
		//redirects to index 
		}
		$stmt->execute();
		$stmt->close();

		//then deletes story associated with the user
		$myquery = "DELETE FROM newsWebsiteDB.storiesTable WHERE storiesTable.storyId='$sessionUsername'";
		$stmt = $mysqli->prepare($myquery);


		if(!$stmt){
			printf("query prep failed: %s", $mysqli->error);
		//redirects to index 
		}
		$stmt->execute();
		$stmt->close();

		//finally deletes user from database.
		$myquery = "DELETE FROM newsWebsiteDB.userTable WHERE userTable.usernameId='$sessionUsername'";
		$stmt = $mysqli->prepare($myquery);


		if(!$stmt){
			printf("query prep failed: %s", $mysqli->error);
			echo'<meta http-equiv="refresh" content="2;URL=homepage.php" />';
		}
		$stmt->execute();
		$stmt->close();



		echo "<br>";
		echo"data was removed.";
	
?>