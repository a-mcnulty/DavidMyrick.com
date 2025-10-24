<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	if(isset($_POST["myContent"])){
		$data_json = stripslashes($_POST["myContent"]);

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$customerID = $data_array["customerID"];
        	$paymentType = $data_array["paymentType"];
    	}

	} else {
		echo "Error!";
	}

	if (!isset($customerID)) {
		echo "error updating page!";
		exit;
	}

	$query = "UPDATE ec_customers SET paymentType = '$paymentType' WHERE id = '$customerID'";

	$result = mysql_query($query);

	if (!$result) {
			die('Could not query:' . mysql_error());
			exit;
	}

	//echo $query;

	echo "customer_detail.php?id=" . $customerID;
?>