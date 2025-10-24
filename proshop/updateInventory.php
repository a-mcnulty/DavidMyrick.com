<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	if(isset($_POST["myContent"])){
		$data_json = stripslashes($_POST["myContent"]);

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$invID = $data_array["invID"];
        	$qty = $data_array["qty"];
    	}

	} else {
		echo "Error!";
	}

	if (!isset($invID)) {
		echo "error updating inventory!";
		exit;
	}
	
	$sql = "UPDATE ec_product_options_inventory SET qty = '$qty' WHERE id = '$invID'";
	
	$result = mysql_query($sql);
	
?>