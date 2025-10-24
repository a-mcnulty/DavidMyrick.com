<?php

	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	$action 				= $_POST['action'];
	$updateRecordsArray 	= $_POST['recordsArray'];

	if ($action == "updateRecordsListings"){

		$listingCounter = 1;
		foreach ($updateRecordsArray as $recordIDValue) {

			//echo $recordIDValue . "<br>";

			$query = "UPDATE ec_options SET sortBy = " . $listingCounter . " WHERE id = " . $recordIDValue;
			mysql_query($query) or die('Error, insert query failed');
			$listingCounter = $listingCounter + 1;
		}

		echo "Option Order Has Been Updated";

		//echo $query;

	}

?>