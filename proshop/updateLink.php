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
        	$linkURL = mysqli_real_escape_string(Database::$conn, $data_array["linkURL"]);
			$linkTarget = mysqli_real_escape_string(Database::$conn, $data_array["linkTarget"]);
    	}

	} else {
		echo "Error!";
	}

	if (!isset($catID) OR !isset($linkURL)) {
		echo "error updating page!";
		exit;
	}

	$query = "UPDATE cat_list SET link = '$linkURL', target = '$linkTarget' WHERE id = '$catID'";

	$result = mysqli_query(Database::$conn, $query);

	echo "Link has been updated";

?>