<?php
session_start();
$username_login = $_SESSION['username'];


//checks if username is contains only valid characters
$nameFilter = '/^[\w_\-]+$/';
  if(!preg_match($nameFilter, $username))
  {
    print('Username not valid!');
    echo'<meta http-equiv="refresh" content="2;URL=logout.php" />';
    exit;
  }

$file = $_POST['file'];


//stackoverflow
// unlik is used to delete files at the file's location
 $fileLocation = sprintf("/srv/uploads/%s/%s", $username,$file);
    if(unlink($fileLocation	)){
       Printf('<br><p> File Deleted! </p><br>')
       printf("<a href=\"userFiles.php\">Return</a>");
    }
    else{
        printf("Something Went Wrong!");
        printf("<a href=\"userFiles.php\">Return</a>");
    }

?>