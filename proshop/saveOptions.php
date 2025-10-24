<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	if(isset($_POST["myContent"])){
		$data_json = stripslashes($_POST["myContent"]);

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$productID = $data_array["productID"];
        	$setID = $data_array["setID"];
        	$optionArray = $data_array["optionArray"];

    	}

	} else {
		echo "Error!";
	}

	if (!isset($productID)) {
		echo "error updating page!";
		exit;
	}

	// STEP 1 - REMOVE EXISITING ROWS FROM EC_PRODUCT_OPTIONS TABLE
	
	$sql1 = "DELETE FROM ec_product_options WHERE productid = '$productID' AND optionsetid = '$setID'";
	
	$result1 = mysql_query($sql1);

	if (!$result1) {
			die('Could not query:' . mysql_error());
			exit;
	}
	
	// STEP 1A - GET MAX SORTBY VALUE AND INCREASE BY 1 FOR NEW ADDITIONS
	
	$sql1a = "SELECT sortBy FROM ec_product_options WHERE productid = '$productID' ORDER BY sortBy DESC LIMIT 0,1";
	
	$result1a = mysql_query($sql1a);

	if (!$result1a) {
			die('Could not query:' . mysql_error());
			exit;
	}
	
	echo "count - " . mysql_numrows($result1a);
	
	$row = mysql_fetch_array($result1a);
	
	echo $row['sortBy'] . "<br /><br />";
	
	if (mysql_numrows($result1a) > 0) {
	
		$nextSort = $row['sortBy'] + 100;
		
	} else {
	
		$nextSort = 100;
		
	}
	
	
	// STEP 2 - ADD NEW SELECTIONS TO EC_PRODUCT_OPTIONS TABLE

	$sortCount = $nextSort;
	foreach($optionArray as $optionID) {

		$sql = "INSERT INTO ec_product_options (productid, optionsetid, optionid, sortBy) VALUES ('$productID', '$setID', '$optionID', '$sortCount')";
		
		$result = mysql_query($sql);

		if (!$result) {
				die('Could not query:' . mysql_error());
				exit;
		}
		
		++$sortCount;

	}

	echo "Options have been saved.";

?>