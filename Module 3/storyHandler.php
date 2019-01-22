<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Homepage</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body class="view">


	<?php
	session_start();

//connection with database
	$mysqli = new mysqli('localhost','wustl_inst','wustl_pass','newsWebsiteDB');

	$storyId = $_POST["storyId"];
	$storyTitle = $_POST["storyTitle"];
	$storyContent = $_POST["storyContent"];
	$storyType = $_POST["storyType"];
	$sessionToken = $_POST["sessionToken"];
	$storyHandler = $_POST['storyHandler'];
//first check token to validation
if($_SESSION['token'] !== $sessionToken){
	echo "Error in token validation!";
	session_destroy();
	echo'<meta http-equiv="refresh" content="2;URL=index.html" />';
}

//first delete storyId (which removes the data from table)
	if($storyHandler == "deletePost"){

		//first deletest the comments in the story . I could've done all with one query , but I was getting too many errors and couldn't figure out why.
		$myquery = "DELETE FROM newsWebsiteDB.commentsTable WHERE commentsTable.storyId='$storyId'";
		$stmt = $mysqli->prepare($myquery);


		if(!$stmt){
			printf("query prep failed: %s", $mysqli->error);
			echo'<meta http-equiv="refresh" content="2;URL=index.html" />';

		}
		$stmt->execute();
		$stmt->close();

		//then deletes story
		$myquery = "DELETE FROM newsWebsiteDB.storiesTable WHERE storiesTable.storyId='$storyId'";
		$stmt = $mysqli->prepare($myquery);


		if(!$stmt){
			printf("query prep failed: %s", $mysqli->error);
		//redirects to index 
		}
		$stmt->execute();
		$stmt->close();
		echo "<br>";
		echo "The story was deleted with sucess!";
		echo'<meta http-equiv="refresh" content="2;URL=homepage.php" />';
	}

	if($storyHandler == "editPost"){
		printf( '<form action="storyHandlerEdit.php" method="POST">

			<input type="hidden" name="storyId" value=%s>

			<label for="title">New Title: </label>
			<input type="text" class="textbox" id="title" name="newStoryTitle" required=True value="%s">
			<br>

			<label for="story">New Story: </label>
			<br>
			<textarea rows="15" id="story" cols="40" name="newStoryContent" required=True value="%s" > </textarea> 
			<br>

			<label for="type">Type: </label>
			<input type="text" id="type" name="newStoryType" value="%s">
			<br>



			<input type="hidden" name="sessionToken" value=%s>
			<button class ="button" type="submit" value="addStory"> Edit Story </button>
			</form>',$storyId,$storyTitle,$storyContent,$storyType,$_SESSION['token']);

	}

//Allows user to post/edit/delete comments and see the content of the story.
	if($storyHandler == "viewPost"){

		echo '<a href="homepage.php"> Homepage</a>';
	//shwos story contents
		echo"<table>";
		$stmt = $mysqli->prepare("SELECT storiesTable.authorId, storiesTable.storyId,storiesTable.storyTitle, storiesTable.storyType,storiesTable.storyContent ,userTable.username AS username FROM storiesTable JOIN userTable ON storiesTable.authorId = userTable.usernameId ");

		if(!$stmt){
			printf("query prep failed: %s", $mysqli->error);
			echo'<meta http-equiv="refresh" content="2;URL=homepage.php" />';
		}

		$stmt->execute();
		$result=$stmt->get_result();
	//populate table with story content
		echo"<th>";
		while($row=$result->fetch_assoc()){
			if($row['storyId'] == $storyId){
				//gets variables from database
				$rowStoryTite = htmlentities($row['storyTitle']);
				$rowUsername = htmlentities($row['username']);
				$rowStoryContent = htmlentities($row['storyContent']);
				$rowUsername = htmlentities($row['username']);

			//first title of story
				echo"<h1 class='storyTitle'></h1>";
				printf("<h1 class='storyTitle'>  %s </h2>", $rowStoryTite, $rowUsername);
				echo "</h1><br>";
				printf("<p>%s</p> ",$rowStoryContent);
				printf("Author: %s", $rowUsername);
			}
		}
		echo"</th>";
		echo"</table>";
		$stmt->close();



		echo"<table>";
		echo"<th>";
	// comments goes here(under the story content)
		echo"Comments: <br>";
		$stmt = $mysqli->prepare("SELECT commentsTable.commentId, commentsTable.commentContent, commentsTable.usernameId, commentsTable.storyId, userTable.username FROM commentsTable JOIN userTable ON commentsTable.usernameId=userTable.usernameId");
		if(!$stmt){
			printf("query prep failed: %s", $mysqli->error);
			//redirects to index 
		}

		$stmt->execute();
		$result=$stmt->get_result();
		echo"<ul>";
		while($row=$result->fetch_assoc()){
			if($row['storyId'] == $storyId){
				//
				echo"<li>";
				// printf("<p>%s</p>",htmlentities($row['commentId']));
				printf("<strong> user: %s </strong> <br>",htmlentities($row['username']));
				printf("<p>%s</p>",htmlentities($row['commentContent']));
				// echo "<br>";

				//add buttons here
				if($row['usernameId'] == $_SESSION['userid']){
					//get variables from database
					$rowCommentId = $row['commentId'];
					$rowUsernameId =$row['usernameId'];
					$rowCommentContent = $row['commentContent'];

					//delete/edit buttons for each comment, only allowed for the owners
					printf( '<form action="commentHandler.php" method="POST" >
					<input type="hidden" name="sessionToken" value=%s>
					<input type="hidden" name="commentId" value=%s>
					<input type="hidden" name="usernameId" value=%s>
					<button class ="button3" name="commentHandler" type="submit" value="deleteComment"> Delete </button>
					</form>',$_SESSION['token'],$rowCommentId,$rowUsernameId);

					
					printf( '<form action="commentHandler.php" method="POST" >
					<input type="hidden" name="sessionToken" value=%s>
					<input type="hidden" name="commentId" value=%s>
					<input type="hidden" name="usernameId" value=%s>
					<input type="hidden" name="commentContent" value=%s>

					<button class ="button3" name="commentHandler" type="submit" value="editComment"> Edit Comment </button>
					</form>',$_SESSION['token'],$rowCommentId,$rowUsernameId,$rowCommentContent);
				}
				echo"</li>";
				echo("<br><br>");

			}
		}
		
		echo"</ul>";
		echo"</th>";
		printf( '<form action="commentHandler.php" method="POST" >
			<input type="hidden" name="sessionToken" value=%s>
			<input type="hidden" name="storyId" value=%s>
			<button class ="button" name="commentHandler" type="submit" value="newComment"> New Comment </button>
			<br>
			</form>',$_SESSION['token'],$storyId);
		echo"<br>";
		echo"</table>";



	}

	?>



</body>
</html>


