<?php

	#TODO: Move the connection away from here, use whatever the main system uses globally
	$link = mysqli_connect('localhost', 'root', '', 'wod');
	if (!$link)
	{
		die('Error: Database link could not be established: ' . mysqli_error($link));
	}
	
	if (!isset($_GET['new']))
		die ("Fatal Error: There is no point to continue unless you specify the name for the new parent to add!");
	
	$new_parent_name = $_GET['new'];
	$sql = "INSERT INTO parent (name) VALUES ('".$new_parent_name."')";
	
	if (mysqli_query($link, $sql)) {
		echo "New parent added!";
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($link);
	}

	mysqli_close($link);

?>