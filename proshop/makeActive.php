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

	$sql1 = "SELECT active FROM ec_customers WHERE id = '$customerID'";

	$result1 = mysql_query($sql1);
	$row = mysql_fetch_array($result1);

	$currentActive = $row['active'];
	if ($currentActive === "yes") {
		$newActive = "no";
	} else {
		$newActive = "yes";
	}

	$sql = "UPDATE ec_customers SET active = '$newActive' WHERE id = '$customerID'";

	$result = mysql_query($sql);

	echo "customer_detail.php?id=" . $customerID;


?>