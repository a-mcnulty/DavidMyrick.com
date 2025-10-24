<?php

	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	// TEMPORARY SCRIPT TO ADD SHIP DATES
	
	$sql = "SELECT orderID, dateAdded FROM ec_order_history WHERE status = '6' ORDER BY id DESC";
	
	$result = mysql_query($sql);
	
	for ($count = 1; $row = mysql_fetch_array($result); ++$count) {
	
		echo $row['orderID'] . " - " . $row['dateAdded'] . "<br />";
		
		$shipDate = $row['dateAdded'];
		$orderID = $row['orderID'];
		
		$sql2 = "UPDATE ec_orders SET shipDate = '$shipDate' WHERE id = '$orderID'";
		
		$result2 = mysql_query($sql2);
		
		echo $sql2 . "<br /><br />";
	
	}
	
?>	
