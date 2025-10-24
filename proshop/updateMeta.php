<?php

	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';

	if(isset($_POST["myContent"])){
		$data_json = $_POST["myContent"];

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$catID = $data_array["catID"];
        	$metaTitle = mysqli_real_escape_string(Database::$conn, $data_array["metaTitle"]);
			$metaDesc = mysqli_real_escape_string(Database::$conn, $data_array["metaDesc"]);
			$metaKeywords = mysqli_real_escape_string(Database::$conn, $data_array["metaKeywords"]);
    	}

	} else {
		echo "Error!";
	}

	if (!isset($catID)) {
		echo "error updating page!";
		exit;
	}

	$query = "UPDATE cat_list SET metaTitle = '$metaTitle', metaDesc = '$metaDesc', metaKeywords = '$metaKeywords' WHERE id = '$catID'";

	$result = mysqlI_query(Database::$conn, $query);

	if (!$result) {
			die('Could not query:' . mysqli_error(Database::$conn));
			exit;
	}

	echo "Meta Tags have been updated!<br /><br />";

?>