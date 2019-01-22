<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Comment Handler!</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php

session_start();
$mysqli = new mysqli('localhost','wustl_inst','wustl_pass','newsWebsiteDB');

$sessionToken = $_POST['sessionToken'];
$commentHandler = $_POST['commentHandler'];

//token validation


if($_SESSION['token'] !== $sessionToken){
	echo "Error in token validation!";
	session_destroy();
	echo'<meta http-equiv="refresh" content="2;URL=index.html" />';
}


//form to add new comment
if($commentHandler == "newComment"){
	$storyId = $_POST['storyId'];
	$authorId = $_SESSION['userid'];
	echo '<a href="homepage.php"> Homepage</a>';
	printf( '<form action="commentHandlerAddComment.php" method="POST" >
			<label for="newComment"> New Comment: </label>
			<input type="text" id="newComment" name="storyComment">
			<input type="hidden" name="sessionToken" value=%s>
			<input type="hidden" name="storyId" value=%s>
			<input type="hidden" name="authorId" value=%s>
			<button class ="button3" name="addComment" type="submit" value="newComment"> Add Comment </button>
			</form>',$_SESSION['token'],$storyId,$authorId);

}


//deletes comment
if($commentHandler == "deleteComment"){
$commentId = $_POST['commentId'];
$usernameId = $_POST['usernameId'];
$myquery = "DELETE FROM commentsTable WHERE commentId='$commentId' ";
$stmt = $mysqli->prepare($myquery);
if(!$stmt){
			printf("query prep failed: %s", $mysqli->error);
			//redirects to index 
		}
$stmt->execute();
$stmt->close();

echo"comment was deleted!";
echo'<meta http-equiv="refresh" content="2;URL=homepage.php" />';

}
if($commentHandler == "editComment"){
	$commentId = $_POST['commentId'];
	$commentContent = $_POST['commentContent'];



	printf( '<form action="commentHandlerEditComment.php" method="POST" >
			<label for="newComment"> Edit Comment: </label>
			<input type="text" id="newComment" name="editedComment">
			<input type="hidden" name="sessionToken" value=%s>
			<input type="hidden" name="commentContent" value=%s>
			<input type="hidden" name="commentId" value=%s>
			<button class ="button3" name="editComment" type="submit" value="newComment"> Edit Comment </button>
			</form>',$_SESSION['token'],$commentContent,$commentId);

}





?>
</body>
</html>