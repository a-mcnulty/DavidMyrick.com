<?php

	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';

	if(isset($_POST["myContent"])){
		$data_json = $_POST["myContent"];

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$picID = $data_array["picID"];
        	$metaTitle = mysqli_real_escape_string(Database::$conn, $data_array["metaTitle"]);
			$metaDesc = mysqli_real_escape_string(Database::$conn, $data_array["metaDesc"]);
			$metaKeywords = mysqli_real_escape_string(Database::$conn, $data_array["metaKeywords"]);
    	}

	} else {
		echo "Error!";
	}

	if (!isset($picID)) {
		echo "error updating page!";
		exit;
	}

	$query = "UPDATE pics SET metaTitle = '$metaTitle', metaDesc = '$metaDesc', metaKeywords = '$metaKeywords' WHERE id = '$picID'";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
			die('Could not query:' . mysqli_error(Database::$conn));
			exit;
	}

	echo "Meta Tags have been updated!<br /><br />";

?>