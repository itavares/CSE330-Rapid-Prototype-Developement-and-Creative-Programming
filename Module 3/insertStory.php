<?php
// Connects to database
$mysqli = new mysqli('localhost','wustl_inst','wustl_pass','newsWebsiteDB');

// https://www.w3schools.com/php/func_mysqli_real_escape_string.asp ** This citation is used to espaces special character in a string for use in an SQL statement**
$storyTitle = mysqli_real_escape_string($mysqli, $_POST['storyTitle']);
$storyType = mysqli_real_escape_string($mysqli, $_POST['storyType']);
$storyContent = mysqli_real_escape_string($mysqli, $_POST['storyContent']);
// END OF CITATION

//starts Session

session_start();
$sessionUsernameId = $_SESSION['userid'];



//Title and Content fields were made required in the form, so there is no need to check if empty.

//check for token
$sessionToken = $_POST['sessionToken'];

if($_SESSION['token'] !== $sessionToken){
	echo "Error in token validation!";
	session_destroy();
	echo'<meta http-equiv="refresh" content="2;URL=index.html" />';
}

$myquery = "INSERT INTO storiesTable (storyTitle,authorId,storyContent,storyType) VALUES (?,?,?,?)";
$stmt = $mysqli->prepare($myquery);

if(!$stmt){
	printf("query prep failed: %s", $mysqli->error);
}

$stmt->bind_param('ssss',$storyTitle,$sessionUsernameId,$storyContent,$storyType);
$stmt->execute();
$stmt->close();

echo"<br>";
printf("%s, your story was created!",$_SESSION['username']);
echo"<br>";
echo'<meta http-equiv="refresh" content="2;URL=homepage.php" />';

?>

