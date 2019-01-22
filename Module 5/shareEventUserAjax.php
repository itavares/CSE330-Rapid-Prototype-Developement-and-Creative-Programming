<?php
header("Content-Type: application/json");
ini_set("session.cookie_httponly",1);

$mysqli = new mysqli('localhost','wustl_inst','wustl_pass','calendarEvents');
session_start();

$sUserId = $_POST['shareUserId'];
$myUser = $_SESSION['userid'];
$sEventId= $_POST['sharedEventId'];


//get token to validate the session
$sessionToken = $_SESSION['token'];


$myquery1 = "SELECT  eventTitle, eventTime,eventDay,eventMonth,eventYear, username, eventMinute FROM eventsTable WHERE eventId ='$sEventId' ";

$stmt=$mysqli->prepare($myquery1);

if(!$stmt){
	echo json_encode(array(
		"sucess" =>false,
		"message"=>"query1 prep failed: %s",$mysqli->error

	));
	exit;
}

$stmt->execute();

//GET EVENT CONTENTS TO INSERT BACK TO DB WITH THE USER'S ID TO BE SHARED WITH

$result = $stmt->get_result();
while($getDbRow = $result->fetch_assoc()){
	//save event's info
	$eventTitle_s =($getDbRow['eventTitle']);
	$eventTime_s =($getDbRow['eventTime']);
	$eventDay_s =($getDbRow['eventDay']);
	$eventMonth_s =($getDbRow['eventMonth']);
	$eventYear_s=($getDbRow['eventYear']);
	$eventMinute_s=($getDbRow['eventMinute']);
	$username_s = ($getDbRow['username']);
}	
$stmt->close();

$myquery2 = "INSERT INTO eventsTable (eventTitle,eventTime,eventDay,eventMonth,eventYear,username,usernameId,eventMinute) VALUES ('$eventTitle_s','$eventTime_s','$eventDay_s','$eventMonth_s','$eventYear_s','$username_s','$sUserId','$eventMinute_s')";


$stmt=$mysqli->prepare($myquery2);

if(!$stmt){
	echo json_encode(array(
		"sucess" =>false,
		"message"=>"query2 prep failed: %s",$mysqli->error

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