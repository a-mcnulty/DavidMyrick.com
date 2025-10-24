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

    	}

	} else {
		echo "Error!";
	}

	if (!isset($productID)) {
		echo "error updating page!";
		exit;
	}
	
	// STEP 1 - LOOP THROUGH EC_PRODUCT_OPTIONS TABLE TO GET ASSIGNED OPTIONS
	
	$sql1 = "SELECT DISTINCT(optionsetid) as setID FROM ec_product_options WHERE productid = '$productID' ORDER BY sortBy ASC LIMIT 0,1";
	
	$result1 = mysql_query($sql1);

	if (!$result1) {
		die('Could not query:' . mysql_error());
		exit;
	}
	
	$setsArray = array();
	$setStr = "";
	$optionStr = "";
	
	// top level loop for unique set ids
	for ($count = 1; $set = mysql_fetch_array($result1); ++$count) {
	
		$thisSetID = $set['setID'];
		
		$sql2 = "SELECT optionid FROM ec_product_options WHERE optionsetid = '$thisSetID' AND productid = '$productID' ORDER BY sortBy ASC";
		
		$result2 = mysql_query($sql2);

		if (!$result2) {
			die('Could not query:' . mysql_error());
			exit;
		}
		
		for ($count2 = 1; $option2 = mysql_fetch_array($result2); ++$count2) {
		
			$setStr .= $thisSetID;
			$optionStr .= $option2['optionid'];
			
			// now that we have the first set and option we need to find other variations
			
			$sql3 = "SELECT * FROM ec_product_options WHERE optionsetid <> '$thisSetID' AND productid = '$productID' ORDER BY sortBy ASC";
			
			//echo $sql3 . "\n\n";
			
			$result3 = mysql_query($sql3);

			if (!$result3) {
				die('Could not query:' . mysql_error());
				exit;
			}
			
			// if multiple option sets we need to execute another loop below
			if (mysql_numrows($result3) > 0) { 
			
				for ($count3 = 1; $option = mysql_fetch_array($result3); ++$count2) {
					
					$setStr .= "," . $option['optionsetid'];
					$optionStr .= "," . $option['optionid'];
					$sortBy = $option['sortBy'];
					
					// INSERT INTO INVENTORY TABLE
					$sql4 = "INSERT INTO ec_product_options_inventory (productid, setstr, optionstr, sortBy) VALUES ('$productID', '$setStr', '$optionStr', '$sortBy')";
					
					$result4 = mysql_query($sql4);

					if (!$result4) {
						die('Could not query:' . mysql_error());
						exit;
					}
					
					echo $setStr . " | " . $optionStr . "\n";
					
					$setStr = $thisSetID;
					$optionStr = $option2['optionid'];
				
				}
				
			// only 1 option set, update inventory table
			} else {
			
				// INSERT INTO INVENTORY TABLE
				$sql4 = "INSERT INTO ec_product_options_inventory (productid, setstr, optionstr, sortBy) VALUES ('$productID', '$setStr', '$optionStr', '$sortBy')";
				
				$result4 = mysql_query($sql4);

				if (!$result4) {
					die('Could not query:' . mysql_error());
					exit;
				}
			
			}
			
			$setStr = "";
			$optionStr = "";
			
		
		}
		
		
		/*
		
		for ($count2 = 1; $option = mysql_fetch_array($result2); ++$count2) {
		
			$setsArray[] = array("setid"=>$thisSetID,"optionid"=>$option['optionid']);
			
		}
		
		*/
	
	}
	
	//print_r($setsArray);
	echo "\n\ndone";
	
?>