<?php

	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	if(isset($_POST["myContent"])){
		$data_json = stripslashes($_POST["myContent"]);

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$id = mysql_real_escape_string($data_array["id"]);
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

	if (!isset($id)) {
		echo "error editing discount code!";
		exit;
	}

	$query = "UPDATE ec_coupons SET startDate = '$startDate', endDate = '$endDate', discount = '$discount', discountType = '$discountType', uses = '$uses', code = '$code', custType = '$custType' WHERE id = '$id'";

	//echo $query;

	$result = mysql_query($query);

	if (!$result) {
			die('Could not query:' . mysql_error());
			exit;
	}
	
	echo "manage_coupons.php?status=coupon_has_been_updated";

?>