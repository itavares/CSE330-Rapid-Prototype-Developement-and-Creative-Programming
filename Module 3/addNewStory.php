<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Homepage</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>


	<?php
	session_start();


	printf( '<form action="insertStory.php" method="POST">
		<label for="title">Title: </label>
		<input type="text" class="textbox" id="title" name="storyTitle" required=True>
		<br>

		<label for="story">Story: </label>
		<br>
		<textarea rows="15" id="story"  cols="40" name="storyContent" required=True > </textarea> 
		<br>	
		<label for="type">Type: </label>
		 <input type="text" id="type" name="storyType" value=>
		<br>
		<input type="hidden" name="sessionToken" value=%s>
		<button class ="button1" type="submit" value="addStory"> Add </button>
		</form>', $_SESSION['token']);




?>
</body>
</html>


