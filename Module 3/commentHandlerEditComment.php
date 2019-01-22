<?php
session_start();
$mysqli = new mysqli('localhost','wustl_inst','wustl_pass','newsWebsiteDB');


$sessionToken = $_POST['sessionToken'];
$commentId = mysqli_real_escape_string($mysqli, $_POST['commentId']);
$commentContent = mysqli_real_escape_string($mysqli, $_POST['editedComment']);

if($_SESSION['token'] !== $sessionToken){
	echo "Error in token validation!";
	session_destroy();
	echo'<meta http-equiv="refresh" content="2;URL=index.html" />';
}

$myquery = "UPDATE commentsTable SET commentContent='$commentContent' WHERE commentId='$commentId' ";
$stmt=$mysqli->prepare($myquery);

if(!$stmt){
	printf("query prep failed: %s", $mysqli->error);
}

$stmt->execute();
$stmt->close();

echo"Comment edited!";
echo'<meta http-equiv="refresh" content="2;URL=homepage.php" />';


?>