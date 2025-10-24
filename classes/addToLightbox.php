<?php

	session_start();
	include('../includes/connect.php');
	include('../includes/functions.php');

	$picID = $_POST['picID'];

	// check the database to see if product and session exists in database

	$sql = "SELECT id FROM lightbox WHERE sessionID = '$sid' AND picID = '$picID'";

	$result = mysqli_query(Database::$conn, $sql);
	
	if (!$result) {
			die('Could not query:' . mysqli_error($result));
			exit;
	}

	$num = mysqli_num_rows($result);

	if ($num == 0) {
	
		// update lbCount col in pics table for ranking
		
		$sql1a = "SELECT lbCount FROM pics WHERE id = '$picID'";
		
		$result1a = mysqli_query(Database::$conn, $sql1a);
		
		if (!$result1a) {
				die('Could not query 1a:' . mysqli_error($result1a));
				exit;
		}
		
		$row1 = mysqli_fetch_array($result1a);
		$currentCount = $row1['lbCount'];
		$newCount = $currentCount + 1;
		
		$sql1b = "UPDATE pics SET lbCount = '$newCount' WHERE id = '$picID'";
		
		$result1b = mysqli_query(Database::$conn, $sql1b);
		
		if (!$result1b) {
				die('Could not query 1b:' . mysqli_error($result1b));
				exit;
		}

		// new selects should always go to end of the list.  need to find max sortby value in current lightbox
		
		$sql3 = "SELECT MAX(sortBy) AS lastSort FROM lightbox WHERE sessionID = '$sid' AND picID <> '$picID'";
		
		$result3 = mysqli_query(Database::$conn, $sql3);
		
		if (!$result3) {
				die('Could not query 3:' . mysqli_error($result3));
				exit;
		}
		
		$row3 = mysqli_fetch_array($result3);
		$lastSort = $row3['lastSort'];
		$newSort = $lastSort + 1;
		
		$sql2 = "INSERT INTO lightbox (picID, sessionID, sortBy) VALUES ('$picID', '$sid', '$newSort')";

		$result2 = mysqli_query(Database::$conn, $sql2);
		
		if (!$result2) {
				die('Could not query 2a:' . mysqli_error($result2));
				exit;
		}

	} else {



		// already in lightbox, ignore



	}


	$sql3 = "SELECT id FROM lightbox WHERE sessionID = '$sid'";

	$result3 = mysqli_query(Database::$conn, $sql3);

	$count = mysqli_num_rows($result3);

	

	echo $count;

	

	// we should delete lightboxes after a couple days

	//deleteAbandonedCart();



?>