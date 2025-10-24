<?php

	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';
	
	//1. LOOP THROUGH NEW ARRIVALS SECTION IN MENS AND WOMENS
	//2. FIND MATCH BASED ON PRODUCT TITLE
	//3. COPY OVER PRICE, COLOR, OPTIONS, RELATED
	
	$sql1 = "SELECT * FROM pics WHERE catid = '502'";
	
	$result1 = mysql_query($sql1);
	
	for ($count = 1; $pic = mysql_fetch_array($result1); ++$count) {
	
		$thisID = $pic['id'];
		
		// GRAB DETAILS FROM ORIGINAL CATEGORY
		
		$thisTitle = $pic['title'];
		
		$sql2 = "SELECT * FROM pics WHERE title = '$thisTitle' AND catid <> '500'";
		
		$result2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($result2);
		
		$orgPicID = $row2['id'];
		
		$newPrice = $row2['price'];
		$newColorID = $row2['colorid'];
		$newRelated = $row2['related'];
		$newRelatedColors = $row2['relatedColors'];
		$newIgnoreQty = $row2['ignoreQty'];
		$newHover = $row2['hover'];
	
		echo $pic['title'] . "<br />";
		echo $row2['price'] . "<br /><br />";
		
		// NOW UPDATE THE NEW ARRIVALS WITH CORRECT INFORMATION
		
		$sql3 = "UPDATE pics SET price = '$newPrice', colorid = '$newColorID', related = '$newRelated', relatedColors = '$newRelatedColors', ignoreQty = '$newIgnoreQty', hover = '$newHover' WHERE id = '$thisID'";
		
		$result3 = mysql_query($sql3);
		
		// NEXT WE UPDATE PRODUCT OPTIONS AND INVENTORY FOR THIS PRODUCT
		
		$sql4 = "SELECT * FROM ec_product_options WHERE productid = '$orgPicID'";
		
		$result4 = mysql_query($sql4);
		
		for ($count4 = 1; $row4 = mysql_fetch_array($result4); ++$count4) {
		
			$newoptionsetid = $row4['optionsetid'];
			$newoptionid = $row4['optionid'];
			$newsortby = $row4['sortBy'];
			
			// FOR EACH EXISTING ROW, ADD NEW ROW WITH NEW PRODUCT ID
			
			$sql5 = "INSERT INTO ec_product_options (productid, optionsetid, optionid, sortBy) VALUES ('$thisID', '$newoptionsetid', '$newoptionid', '$newsortby')";
			
			$result5 = mysql_query($sql5);
		
		}
		
		$sql6 = "SELECT * FROM ec_product_options_inventory WHERE productid = '$orgPicID'";
		
		$result6 = mysql_query($sql6);
		
		for ($count6 = 1; $row6 = mysql_fetch_array($result6); ++$count6) {
		
			$newsetstr = $row6['setstr'];
			$newoptionstr = $row6['optionstr'];
			$newqty = $row6['qty'];
			$newprice = $row6['price'];
			$newsortby = $row6['sortBy'];
			
			// FOR EACH EXISTING ROW, ADD NEW ROW WITH NEW PRODUCT ID
			
			$sql7 = "INSERT INTO ec_product_options_inventory (productid, setstr, optionstr, qty, price, sortBy) VALUES ('$thisID', '$newsetstr', '$newoptionstr', '$newqty', '$newprice', '$newsortby')";
			
			$result7 = mysql_query($sql7);
		
		}
	
	}
	
?>