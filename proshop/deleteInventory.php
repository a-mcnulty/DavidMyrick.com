<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	if(isset($_POST["myContent"])){
		$data_json = stripslashes($_POST["myContent"]);

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$id = $data_array["invID"];

    	}

	} else {
		echo "Error!";
	}

	if (!isset($id)) {
		echo "error updating page!";
		exit;
	}
	
	$sql1 = "DELETE FROM ec_product_options_inventory WHERE id = '$id'";
	
	$result1 = mysql_query($sql1);

	if (!$result1) {
			die('Could not query:' . mysql_error());
			exit;
	}
	
?>