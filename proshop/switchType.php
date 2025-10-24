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
    	}

	} else {
		echo "Error!";
	}

	if (!isset($customerID)) {
		echo "error updating page!";
		exit;
	}

	$sql1 = "SELECT type FROM ec_customers WHERE id = '$customerID'";

	$result1 = mysql_query($sql1);
	$row = mysql_fetch_array($result1);

	$currentType = $row['type'];
	if ($currentType === "retail") {
		$newType = "wholesale";
	} else {
		$newType = "retail";
	}

	$sql = "UPDATE ec_customers SET type = '$newType' WHERE id = '$customerID'";

	$result = mysql_query($sql);

	echo "customer_detail.php?id=" . $customerID;


?>