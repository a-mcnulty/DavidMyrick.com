<?php

	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';

	$action 				= $_POST['action'];
	$updateRecordsArray 	= $_POST['recordsArray'];

	if ($action == "updateRecordsListings"){

		$listingCounter = 1;
		foreach ($updateRecordsArray as $recordIDValue) {

			//echo $recordIDValue . "<br>";

			$query = "UPDATE modules SET sortBy = " . $listingCounter . " WHERE id = " . $recordIDValue;
			mysqli_query(Database::$conn, $query) or die('Error, insert query failed');
			$listingCounter = $listingCounter + 1;
		}

		echo "Module Order Has Been Updated";

	}

?>