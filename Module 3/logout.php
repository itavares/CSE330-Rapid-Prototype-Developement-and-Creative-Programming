<?php
session_start();
echo "You log out!";
session_destroy();
echo'<meta http-equiv="refresh" content="2;URL=index.html" />';
?>