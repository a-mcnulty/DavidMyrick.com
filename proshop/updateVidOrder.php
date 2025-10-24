<?php

	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	$action 				= $_POST['action'];
	$updateRecordsArray 	= $_POST['recordsArray'];
	$catID					= $_POST['catID'];

	if ($action == "updateRecordsListings"){

		$listingCounter = 1;
		foreach ($updateRecordsArray as $recordIDValue) {

			//echo $recordIDValue . "<br>";

			$query = "UPDATE videos SET sortBy = " . $listingCounter . " WHERE id = " . $recordIDValue;
			mysql_query($query) or die('Error, insert query failed');
			
			$query2 = "UPDATE cat_videos SET sortBy = " . $listingCounter . " WHERE vidid = " . $recordIDValue . " AND catid = '$catID'";
			
			mysql_query($query2) or die('Error, cat_videos query failed');
			
			$listingCounter = $listingCounter + 1;
		}

		echo "Video Order Has Been Updated";

	}

?>