<?php

	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	if(isset($_POST["myContent"])){
		$data_json = stripslashes($_POST["myContent"]);

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$code = mysql_real_escape_string($data_array["code"]);
        	$uses = mysql_real_escape_string($data_array["numuses"]);
        	$startDate = strtotime($data_array["startDate"]);
        	$endDate = strtotime($data_array["endDate"]);
        	$discount = mysql_real_escape_string($data_array["discount"]);
        	$discountType = mysql_real_escape_string($data_array["discountType"]);
        	$custType = mysql_real_escape_string($data_array["custType"]);
    	}

	} else {
		echo "Error!";
	}

	if (!isset($code)) {
		echo "error creating discount code!";
		exit;
	}

	$query = "INSERT INTO ec_coupons (startDate, endDate, discount, discountType, uses, code, custType) VALUES ('$startDate', '$endDate', '$discount', '$discountType', '$uses', '$code', '$custType')";

	$result = mysql_query($query);

	if (!$result) {
			die('Could not query:' . mysql_error());
			exit;
	}

	echo "manage_coupons.php?status=new_coupon_has_been_added";

?>