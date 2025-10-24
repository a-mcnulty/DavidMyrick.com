<?php

	session_start();
	include 'includes/functions.php';
	include 'includes/connect.php';
	
	// 500, 502
	
	$catID = 502;
	
	$sql = "SELECT * FROM pics WHERE catid = '$catID'";
	
	$result = mysql_query($sql);
	
	for ($count = 1; $pic = mysql_fetch_array($result); ++$count) {
	
		$thisTitle = $pic['title'];
		
		$sql2 = "SELECT * FROM pics WHERE title = '$thisTitle' AND catID <> '$catID'";
		
		$result2 = mysql_query($sql2);
		
		$row2 = mysql_fetch_array($result2);
		
		$mainID = $row2['id'];
		
		// insert mainID into cat_pics table
		
		$sql3 = "INSERT INTO cat_pics (catid, picid) VALUES ('$catID', '$mainID')";
		
		$result3 = mysql_query($sql3);	
	
	}
	
?>