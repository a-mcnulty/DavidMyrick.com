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
        	$picID = $data_array["picID"];
    	}

	} else {
		echo "Error!";
	}

	if (!isset($picID)) {
		echo "error updating page!";
		exit;
	}

	$sql1 = "UPDATE videos SET hoverFile = '' WHERE picid = '$picID'";

	$result1 = mysqli_query(Database::$conn, $sql1);
	
	echo "detail.php?picID=" . $picID . "&category=" . $catID . "&type=";

?>