<?php

session_start();
$mysqli = new mysqli('localhost','wustl_inst','wustl_pass','newsWebsiteDB');

$sessionToken = $_POST['sessionToken'];
if($_SESSION['token'] !== $sessionToken){
	echo "Error in token validation!";
	session_destroy();
	echo'<meta http-equiv="refresh" content="2;URL=index.html" />';
}

//get Post variables
$newStoryTitle = $_POST['newStoryTitle'];
$newStoryContent = $_POST['newStoryContent'];
$newStoryId = $_POST['storyId'];
$newStoryType = $_POST['newStoryType'];

$myquery = "UPDATE storiesTable SET storyTitle='$newStoryTitle',storyContent='$newStoryContent', storyType='$newStoryType' WHERE storyId='$newStoryId'  ";

$stmt=$mysqli->prepare($myquery);

if(!$stmt){
	printf("query prep failed: %s", $mysqli->error);
	echo'<meta http-equiv="refresh" content="2;URL=index.html" />';
}

$stmt->execute();
$stmt->close();
printf("%s, your story was edited! ",$_SESSION['username']);
echo"<br>";
echo'<meta http-equiv="refresh" content="2;URL=homepage.php" />';

?>