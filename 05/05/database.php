<?php 
	$db_host = 'localhost';
	$db_name = 'regdb';
	$db_user = 'root';
	$db_pass = '';
	
	$con = mysqli_connect ($db_host, $db_user, $db_pass, $db_name);
	
	if (mysqli_errno($con))
	{
		die ('Database error: ' . mysqli_error($con));
	}
?>
