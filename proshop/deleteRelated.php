<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';
	
	if(isset($_POST["myContent"])){
		$data_json = stripslashes($_POST["myContent"]);

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$relatedID = $data_array["relatedID"];
        	$picID = $data_array["parentID"];
    	}

	} else {
		echo "Error!";
	}

	if (!isset($picID)) {
		echo "error updating page!";
		exit;
	}
	
	// get current value of related field and add new items
	$sql1 = "SELECT related FROM pics WHERE id = '$picID'";
	
	$result1 = mysql_query($sql1);
	$row1 = mysql_fetch_array($result1);
	
	$currentRelated = $row1['related'];
	$currentRelatedArray = explode(",", $currentRelated);

	// remove the relatedID value from the current Array
	if(($key = array_search($relatedID, $currentRelatedArray)) !== false) {
		unset($currentRelatedArray[$key]);
	}
	
	// now we can break the updated Array into a string and add back to table
	$newRelated = implode(",", $currentRelatedArray);
	
	$sql2 = "UPDATE pics SET related = '$newRelated' WHERE id = '$picID'";
	
	$result2 = mysql_query($sql2);

?>