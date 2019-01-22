<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Homepage</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

	<div id="welcomeTitle">
		<form action="logout.php">
			<input type="submit" value="Logout">
		</form>

		<?php
		session_start();
		$sessionUsername = $_SESSION['username'];
		$sessionToken =  $_SESSION['token'];
		if($_SESSION['token'] !== $sessionToken){
	echo "Error in token validation!";
	session_destroy();
	echo'<meta http-equiv="refresh" content="2;URL=index.html" />';
}
		if(isset($sessionUsername) && !empty($sessionUsername)){
			echo"<h2> Welcome :   ";
			echo ($sessionUsername);
			echo "<h2/>";
		}

		printf( '<form action="deleteUser.php" method="POST" >
			<input type="hidden" name="sessionToken" value=%s>
			<input type="hidden" name="username" value=%s>
			<button class ="buttonDelete" type="submit" value="viewPost"> DELETE USER!! </button>
		</form>',
		$_SESSION['token'],$_SESSION['userid']);
	?>
		<div id="mainContainer">
			<?php

		//mysqli connection stuff

			$mysqli = new mysqli('localhost','wustl_inst','wustl_pass','newsWebsiteDB');


			$stmt = $mysqli->prepare("SELECT authorId, storiesTable.storyId, storyTitle, storyType, storyLink,storyContent ,userTable.username AS username FROM storiesTable JOIN userTable ON storiesTable.authorId = userTable.usernameId ");



			if(!$stmt){
				printf("query prep failed: %s", $mysqli->error);
			//redirects to index page
			//gives link to redirect to index page
			}

			$stmt -> execute();
			$result = $stmt->get_result();

			?>
			<div id="listAndButtons">
				<table>
					<th>
						<?php


						//display stories from DB
						while($row = $result -> fetch_assoc())
						{	
						// escape variables from database
							$rowStoryTitle = htmlentities($row['storyTitle']);
							$rowUsername =htmlentities($row['username']);
							$rowStoryType = htmlentities($row['storyType']);
							$rowAuthorId = $row['authorId'];
							$serssionId = $_SESSION['userid'];

					//list stories
							echo "<ul>";
							printf('<li> <h4>Title: %s </h4>',$rowStoryTitle);

							printf('<h4> Author: %s </h4>', $rowUsername);

							printf('<h4>  Type: %s</h4></li>', $rowStoryType );
							//add the link for each story here
							echo "</ul>";


							if(isset($sessionUsername) && !empty($sessionUsername)){
								//checks user validation ( if the uses owns the post)
								if ($rowAuthorId == $serssionId){
									printf( '<form action="storyHandler.php" method="POST" >

										<input type="hidden" name="sessionToken" value=%s>
										<input type="hidden" name="storyId" value=%s>
										<input type="hidden" name="storyTitle" value=%s>
										<input type="hidden" name="storyContent" value=%s>
										<input type="hidden" name="storyType" value=%s>
										<input type="hidden" name="storyLink" value=%s>

										<button class ="button" name="storyHandler" type="submit" value="deletePost"> Delete</button>
										<button class ="button" name="storyHandler" type="submit" value="editPost"> Edit </button>
										<button class ="button" name="storyHandler" type="submit" value="viewPost"> View </button>
										</form>',
										$_SESSION['token'],
										$row["storyId"],
										$row["storyTitle"],
										$row['storyContent'],
										$row["storyType"],
										$row['storyLink']);

								}
							//this allows other users to see the story posted by different users. 
								if ($rowAuthorId !== $serssionId){
									printf( '<form action="storyHandler.php" method="POST" >
										<input type="hidden" name="sessionToken" value=%s>
										<input type="hidden" name="storyId" value=%s>
										<input type="hidden" name="storyTitle" value=%s>
										<input type="hidden" name="storyContent" value=%s>
										<input type="hidden" name="storyType" value=%s>
										<input type="hidden" name="storyLink" value=%s>

										<button class ="button" name="storyHandler" type="submit" value="viewPost"> View </button>
										</form>',
										$_SESSION['token'],
										$row["storyId"],
										$row["storyTitle"],
										$row['storyContent'],
										$row["storyType"],
										$row['storyLink']);
								}
							}

						}
						?>
					</th></table>
					<br>
					<div id="newStoryButton">
						<!-- Create a new Story Button -->
						<?php
						echo"<br>";
						echo '<form action="addNewStory.php" method="POST" >
						<input type="hidden" name="token" value="$sessionToken">
						<button class ="button" type="submit" value="New Story"> New Story</button
						</form>';


						?>

					</div>
					<br>
					<br>
				</div>



			</body>
			</html>


