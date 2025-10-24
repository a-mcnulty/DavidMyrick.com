<?php
	
	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';
	

	$action 				= $_POST['action'];
	$updateRecordsArray 	= $_POST['recordsArray'];
	if (isset($_POST['catID'])) {
		$catID					= $_POST['catID'];
	}
	if (isset($_POST['picID'])) {
		$picID					= $_POST['picID'];
	}
	if (isset($_POST['modID'])) {
		$modID					= $_POST['modID'];
	}

	if ($action == "updateRecordsListings"){

		$listingCounter = 1;
		foreach ($updateRecordsArray as $recordIDValue) {

			//echo $recordIDValue . "<br>";

			$query = "UPDATE pics SET sortBy = " . $listingCounter . " WHERE id = " . $recordIDValue;
			
			//echo $query . "<br />";
			
			mysqli_query(Database::$conn, $query) or die('Error, pics query failed');
			
			$query2 = "UPDATE cat_pics SET sortBy = " . $listingCounter . " WHERE picid = " . $recordIDValue . " AND catid = '$catID'";
			
			mysqli_query(Database::$conn, $query2) or die('Error, cat_pics query failed');
			
			$query3 = "UPDATE cat_list SET sorter = 'drag' WHERE id = '$catID'";
			
			mysqli_query(Database::$conn, $query3) or die('Error, sorter query failed');
			
			if ($picID > 0) {
			
				$query4 = "UPDATE pics SET sorter = 'drag' WHERE id = '$picID'";
				
				mysqli_query(Database::$conn, $query4) or die('Error, pics sorter query failed');
				
			}

			if ($modID > 0) {
			
				$query4 = "UPDATE modules SET sorter = 'drag' WHERE id = '$modID'";
				
				mysqli_query(Database::$conn, $query4) or die('Error, pics sorter query failed');

				$query5 = "UPDATE module_pics SET sortBy = " . $listingCounter . " WHERE picid = " . $recordIDValue . " AND modid = '$modID'";
				
				mysqli_query(Database::$conn, $query5) or die('Error, module_pics sorter query failed');
				
			}
			
			$listingCounter = $listingCounter + 1;
		}

		echo "Image Order Has Been Updated";

	}
