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

			$query = "UPDATE cat_list SET sortBy = " . $listingCounter . " WHERE id = " . $recordIDValue;

			$result = mysqlI_query(Database::$conn, $query);

			if (!$result) {
					die('Could not query:' . mysqli_error(Database::$conn));
					exit;
			}
			$listingCounter = $listingCounter + 1;
		}

		echo "Category Order Has Been Updated";

	}

?>