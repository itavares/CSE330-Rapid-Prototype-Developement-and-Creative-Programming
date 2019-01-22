<?php
session_start();
$mysqli = new mysqli('localhost','wustl_inst','wustl_pass','newsWebsiteDB');


$sessionToken = $_POST['sessionToken'];
$usernameId = mysqli_real_escape_string($mysqli, $_POST['authorId']);
$storyId = mysqli_real_escape_string($mysqli, $_POST['storyId']);
$storyComment = mysqli_real_escape_string($mysqli, $_POST['storyComment']);


if($_SESSION['token'] !== $sessionToken){
	echo "Error in token validation!";
	session_destroy();
	echo'<meta http-equiv="refresh" content="2;URL=index.html" />';
}

$myquery = "INSERT INTO commentsTable (commentContent, storyId, usernameId) VALUES ('$storyComment','$storyId','$usernameId') ";
$stmt = $mysqli->prepare($myquery);


if(!$stmt){
	printf("query prep failed: %s", $mysqli->error);
	echo'<meta http-equiv="refresh" content="2;URL=index.html" />';

}

$stmt->execute();
$stmt->close();

echo"Comment added";
echo'<meta http-equiv="refresh" content="2;URL=homepage.php" />';


?>