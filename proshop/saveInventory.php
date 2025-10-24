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
        	$idArray = $data_array["idArray"];
        	$priceArray = $data_array["priceArray"];
			$qtyArray = $data_array["qtyArray"];			
			$setArray = $data_array["setArray"];
			$optionArray = $data_array["optionArray"];			
			$ignoreQty = $data_array["ignoreQty"];
			$setStr = $data_array["setStr"];

    	}

	} else {
		echo "Error!";
	}

	if (!isset($productID)) {
		echo "error updating page!";
		exit;
	}
	
	$sql1 = "UPDATE pics SET ignoreQty = '$ignoreQty' WHERE id = '$productID'";
	
	$result1 = mysql_query($sql1);

	if (!$result1) {
			die('Could not query:' . mysql_error());
			exit;
	}
	
	$newcounter = 0;
	$counter = 0;
	foreach($idArray as $optionID) {
	
		// NEED TO CHECK IF WE ARE ADDING OR UPDATING INVENTORY
		
		$sql1 = "SELECT id FROM ec_product_options_inventory WHERE id = '$optionID' AND productid = '$productID'";
		$result1 = mysql_query($sql1);

		if (!$result1) {
				die('Could not query:' . mysql_error());
				exit;
		}
		
		if (mysql_numrows($result1) > 0) {
		
			$sql = "UPDATE ec_product_options_inventory SET price = '$priceArray[$counter]', qty = '$qtyArray[$counter]' WHERE id = '$optionID' AND productid = '$productID'";
			
			$result = mysql_query($sql);

			if (!$result) {
					die('Could not query:' . mysql_error());
					exit;
			}
			
		} else {
		
			$sql2a = "SELECT id FROM ec_product_options_inventory WHERE productid = '$productID' AND setstr = '$setArray[$newcounter]' AND optionstr = '$optionArray[$newcounter]'";
			
			$result2a = mysql_query($sql2a);
			
			echo $sql2a . "\n";
			echo mysql_numrows($result2a) . "\n";
			
			if (mysql_numrows($result2a) > 0) {
			
				// skip this, already in inventory
				
			} else {
		
				$sql = "INSERT INTO ec_product_options_inventory (setstr, optionstr, price, qty, productid) VALUES ('$setStr', '$optionArray[$newcounter]', '$priceArray[$counter]', '$qtyArray[$counter]', '$productID')";
				
				$result = mysql_query($sql);

				if (!$result) {
						die('Could not query:' . mysql_error());
						exit;
				}
				
			}
			
			++$newcounter;
		
		}
		
		echo $sql . "\n";
		echo $newcounter . "\n\n";
		echo $counter . "\n\n";
		++$counter;
		
	}
	
	// IF THERE IS ANYTHING IN OPTION ARRAY AND SET ARRAY IT IS A NEW ROW THAT NEEDS TO BE ADDED TO PRODUCT OPTIONS TABLE
	
	$newSetArray = explode(",", $setStr);
	
	foreach($optionArray as $options) {	
	
		$optionSetArray = explode(",", $options);
		
		$sql2a = "SELECT * FROM ec_product_options WHERE productid = '$productID' AND optionsetid = '$newSetArray[0]' AND optionid = '$optionSetArray[0]'";
		
		$result2a = mysql_query($sql2a);

		if (!$result2a) {
				die('Could not query:' . mysql_error());
				exit;
		}
		
		echo $sql2a . "\n";
		
		if (mysql_numrows($result2a) == 0) {
	
			$sql2 = "INSERT INTO ec_product_options (productid, optionsetid, optionid) VALUES ('$productID', '$newSetArray[0]', '$optionSetArray[0]')";
			
			$result2 = mysql_query($sql2);

			if (!$result2) {
					die('Could not query:' . mysql_error());
					exit;
			}
			
			echo $sql2 . "\n\n";
			
		}
		
		$sql3a = "SELECT * FROM ec_product_options WHERE productid = '$productID' AND optionsetid = '$newSetArray[1]' AND optionid = '$optionSetArray[1]'";
		
		$result3a = mysql_query($sql3a);

		if (!$result3a) {
				die('Could not query:' . mysql_error());
				exit;
		}
		
		echo $sql3a . "\n";
		
		if (mysql_numrows($result3a) == 0) {
		
			$sql3 = "INSERT INTO ec_product_options (productid, optionsetid, optionid) VALUES ('$productID', '$newSetArray[1]', '$optionSetArray[1]')";
			
			$result3 = mysql_query($sql3);

			if (!$result3) {
					die('Could not query:' . mysql_error());
					exit;
			}
			
			echo $sql3 . "\n\n";
			
		}
	
	}
	
	// IF ALL OPTIONS ARE DELETED WE NEED TO CLEAR OUT EC PRODUCT OPTIONS TABLE
	if (empty($idArray)) {
		
		$sql4 = "DELETE FROM ec_product_options WHERE productid = '$productID'";
		
		$result4 = mysql_query($sql4);

		if (!$result4) {
				die('Could not query:' . mysql_error());
				exit;
		}
	}
	
?>