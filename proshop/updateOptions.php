<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	if(isset($_POST["myContent"])){
		$data_json = stripslashes($_POST["myContent"]);

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$category = $data_array["category"];
        	$optionIDArray = $data_array["optionID"];
        	$picID = $data_array["picID"];
        	$sortByArray = $data_array["sortBy"];
        	$nameArray = $data_array["name"];
        	$optionArray = $data_array["option"];

    	}

	} else {
		echo "Error!";
	}

	if (!isset($picID)) {
		echo "error updating page!";
		exit;
	}

	// STEP 1 - DELETE ANY EXISTING OPTIONS FOR THIS PRODUCT

	foreach($optionIDArray as $optionID) {

		$sql1 = "DELETE FROM ec_options WHERE optionid = '$optionID' AND picid = '$picID'";
		$result1 = mysql_query($sql1);
		//echo $sql1 . "\n";

	}

	//echo "\n\n";

	$arrayCount = 0;
	foreach($optionIDArray as $optionID) {

		$thisNameArray = $nameArray[$arrayCount];
		$thisSortArray = $sortByArray[$arrayCount];
		$thisOptionArray = $optionArray[$arrayCount];

		$innerCount = 0;
		foreach($thisNameArray as $name) {

			if ($name != "") {

				$thisOptions = $thisOptionArray[$innerCount];
				$thisOptionStr = implode(",", $thisOptions);
				$thisSort = $thisSortArray[$innerCount];

				$sql = "INSERT INTO ec_options (optionid, picid, name, options, sortBy) VALUES ('$optionID', '$picID', '$name', '$thisOptionStr', '$arrayCount')";

				//echo $sql . "\n";

				$result = mysql_query($sql);

				if (!$result) {
						die('Could not query:' . mysql_error());
						exit;
				}

			}

			++$innerCount;

		}

		++$arrayCount;

	}

	echo "Options have been updated.";

	//header("Location: detail.php?picID=".$picID."&category=".$category."&status=options_have_been_updated");

?>