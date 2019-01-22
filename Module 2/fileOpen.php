<!DOCTYPE html>
<html>
<head>
	<title>Open File</title>
</head>
<body>


<?php
session_start();
$username = $_SESSION['username'];


//checks if username is contains only valid characters
if(!preg_match('/^[\w_\-]+$/', $username))
{
	print('Username not valid!');
	// printf('<meta http-equiv="refresh" content="3; URL='userFiles.php'"/>');
	exit;
}
// $file_open = $_POST['open_file'];
$file_ext = $_POST['file_ext'];
$file = $_POST['file_name'];
echo($file);
echo "<br>";


//stackoverflow
// $file_loc = sprintf("/srv/users_upload/%s/%s",$username,$file);
// $file_loc = sprintf("/srv/users_upload/%s/",$username);
$file_loc = "/srv/users_upload/".$username."/";
// echo ($file_ext);
// echo ($file_trim);
echo ($file_loc);
if($file_ext == 'txt'){
	header('Content-type: text/plain');
	@readfile($file_loc);
}
elseif($file_ext == 'png'){
	header('Content-type: image/png');
	@readfile($file_loc);
}
elseif($file_ext == 'jpg'){
	header('Content-type: image/jpeg');
	@readfile($file_loc);
}
elseif($file_ext == 'pdf'){
	header('Content-type: application/pdf');
	@readfile($file_loc);
} 
elseif($file_ext == 'xlsx'){
	header('Content-type: application/xlsx');
	@readfile($file_loc);
}

?>
</body>
</html>