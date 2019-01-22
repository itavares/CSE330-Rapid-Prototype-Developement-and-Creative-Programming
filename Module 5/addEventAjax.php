<?php
header("Content-Type: application/json");
ini_set("session.cookie_httponly",1);

$mysqli = new mysqli('localhost','wustl_inst','wustl_pass','calendarEvents');
session_start();
$usernameId = $_SESSION['userid'];
$username = $_SESSION['username'];

$eventTitle = $_POST['eventTitle'];
$pattern = '/^[\w_\.\-]+$/';
if(!preg_match($pattern, $eventTitle)){
	echo json_encode(array(
		"sucess" => false,
		"message" => "Invalid Username (check for special characters)"
	));
	exit;
}
$eventYear = $_POST['eventYear'];
$eventMonth = $_POST['eventMonth'];
$eventDay = $_POST['eventDay'];
$eventTime = $_POST['eventTime'];
$eventTimeMinute = $_POST['eventTimeMinute'];


//my query to insert new event into database
$myquery = "INSERT INTO eventsTable (eventTitle,eventTime,eventDay,eventMonth,eventYear,username,usernameId,eventMinute) VALUES ('$eventTitle','$eventTime','$eventDay','$eventMonth','$eventYear','$username','$usernameId','$eventTimeMinute')";

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
// echo "test?";

//return sucess json to ajax call 
echo json_encode(array(
"sucess"=> true,
"message"=> ""

));
exit;


?>