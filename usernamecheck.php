<?php
	include 'func.php';
	connect_db();
	$username = mysqli_real_escape_string ($connection, $_POST['username']);	
	$query="SELECT username FROM rain_users WHERE username='$username'";
	$resUser=$connection->query($query);
 	if($resUser === false) {
		trigger_error('Error: ' . $connection->error, E_USER_ERROR);
	} else {
		echo $rows_returned = $resUser->num_rows;
	}	 
?>