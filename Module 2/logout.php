<?php
session_start();
echo "You log out!";
session_destroy(); //destroys session (log out user)
//redirects user back to index
echo'<meta http-equiv="refresh" content="2;URL=index.html" />';

?>