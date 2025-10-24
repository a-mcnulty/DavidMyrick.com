<?php

	session_start();

	include('../includes/connect.php');
	include('../includes/functions.php');

	$sql = "DELETE FROM lightbox WHERE sessionID = '$sid'";

	$result = mysqli_query(Database::$conn, $sql);

	$count = "0";
	
	echo $count;
	
	// we should delete lightboxes after a couple days
	//deleteAbandonedCart();

?>
