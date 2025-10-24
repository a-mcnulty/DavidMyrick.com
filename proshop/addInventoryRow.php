<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	if(isset($_POST["myContent"])){
		$data_json = stripslashes($_POST["myContent"]);

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$productID = $data_array["prodID"];
			$colWidth = $data_array["colWidth"];

    	}

	} else {
		echo "Error!";
	}

	if (!isset($productID)) {
		echo "error updating page!";
		exit;
	}
	
	/* STEPS INCLUDED BELOW
	1. query inventory table to determine sets
	2. once we have the correct sets create loop to create select options for each set
	3. save all html and inject into new row
	*/
	
	$sql1 = "SELECT * FROM ec_product_options_inventory WHERE productid = '$productID' LIMIT 0,1";
	
	$result1 = mysql_query($sql1);
	if (!$result1) {
			die('Could not query 1:' . mysql_error());
			exit;
	}
	
	$sql1a = "SELECT id FROM ec_product_options_inventory ORDER BY id DESC LIMIT 0,1";
	
	$result1a = mysql_query($sql1a);
	if (!$result1a) {
			die('Could not query 1a:' . mysql_error());
			exit;
	}
	
	$row1 = mysql_fetch_array($result1);
	$row1a = mysql_fetch_array($result1a);
	$invID = $row1a['id'];
	$nextID = $invID + 1;
	$setStr = $row1['setstr'];
	
	$setArray = explode(",", $setStr);
	$setCount = count($setArray);
	
	// we now have array of product sets, start to build the html
	
	$output = '<div class="invRow newInv" data-setcount="' . $setCount . '" style="background: #fff;" id="row_' . $nextID . '">';
	
	$output .= '<input type="hidden" name="setStr" id="setStr" value="' . $setStr . '">';
	
	foreach($setArray as $set) {
	
		if ($set != "") {
		
			$output .= '<div style="width: ' . $colWidth . '%;">
								<select name="newInv" data-set="' . $set . '">
							
									<option value="">--SELECT--</option>';
							
			$output .= getOptions($set, "select");
			
			$output .= 			'</select>
							</div>';
							
		}
		
	}
		
	$output .= '<div style="width: ' . $colWidth . '%;">
			<input type="hidden" name="invID" class="invID" value="' . $nextID . '">
			<input type="text" name="price" class="invPrice" value="0.00">
		</div>
		<div style="width: ' . $colWidth . '%;">
			<input type="text" name="qty" class="invQty" value="0">
		</div>
		<div style="width: 10%; text-align: right;">
			<img src="images/icons/delete.png" border="0" style="cursor: pointer;" data-id="858" class="deleteIcon deleteRow">
		</div>				
	</div>';

	
	// echo the html for the result page
	echo $output;
	
?>