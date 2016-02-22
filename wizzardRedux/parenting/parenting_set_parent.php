<?php

	#TODO: Move the connection away from here, use whatever the main system uses globally
	$link = mysqli_connect('localhost', 'root', '', 'wod');
	if (!$link)
	{
		die('Error: Database link could not be established: ' . mysqli_error($link));
	}
	
	if (!isset($_GET['set_id']) || !isset($_GET['dad_id']))
		die ("Fatal Error: You must specify both the set's and the parent's IDs!");
	
	$set_id = $_GET['set_id'];
	$dad_id = $_GET['dad_id'];
	$sql = "UPDATE games SET parent=".$dad_id." WHERE id=".$set_id."";
	
	if (mysqli_query($link, $sql)) {
		echo "Record updated!";
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($link);
	}

	mysqli_close($link);

?>