<?php
header("Content-Type: application/json");
ini_set("session.cookie_httponly",1);

$mysqli = new mysqli('localhost','wustl_inst','wustl_pass','calendarEvents');
session_start();
$sessionToken = $_SESSION['token'];
$userToken   = $_POST['token'];
//check token validation
if($sessionToken !== $userToken){
	echo json_encode(array(
		"sucess" => false,
		"message" => "Token validatio failed!"
	));
	exit(0);
}

$eventTitle = $_POST['newEventTitle'];
//checks for valid input
$pattern = '/^[\w_\.\-]+$/';
if(!preg_match($pattern, $eventTitle)){
	echo json_encode(array(
		"sucess" => false,
		"message" => "Invalid Username (check for special characters)"
	));
	exit;
}
$eventTime = $_POST['newEventTime'];
$eventTimeMinute = $_POST['newEventTimeMinute'];
$eventIdTracker = $_POST['eventIdTracker'];

//get token to validate the session
$sessionToken = $_SESSION['token'];
$myquery = "UPDATE eventsTable SET eventTitle ='$eventTitle', eventTime='$eventTime', eventMinute='$eventTimeMinute' WHERE eventId='$eventIdTracker' ";
$stmt=$mysqli->prepare($myquery);

if(!$stmt){
	echo json_encode(array(
		"sucess" =>false,
		"message"=>"query1 prep failed: %s",$mysqli->error

	));
	exit;
}

$stmt->execute();
$stmt->close();
echo json_encode(array(
"sucess"=> true,

));
exit;


?>