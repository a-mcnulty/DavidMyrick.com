<?php

	session_start();

	include('../includes/connect.php');
	include('../includes/functions.php');

	$picID = $_POST['picID'];

	$sql = "DELETE FROM lightbox WHERE sessionID = '$sid' AND picID = '$picID'";

	$result = mysqli_query(Database::$conn, $sql);
	
	$sql3 = "SELECT id FROM lightbox WHERE sessionID = '$sid'";
	
	$result3 = mysqli_query(Database::$conn, $sql3);
	$count = mysqli_num_rows($result3);
	
	echo $count;
	
	// we should delete lightboxes after a couple days
	//deleteAbandonedCart();

?>
