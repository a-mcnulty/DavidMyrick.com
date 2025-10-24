<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';
	
	if(isset($_POST["myContent"])){
		$data_json = stripslashes($_POST["myContent"]);

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$relatedArray = $data_array["related"];
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
	$sql1 = "SELECT relatedColors FROM pics WHERE id = '$picID'";
	
	$result1 = mysql_query($sql1);
	$row1 = mysql_fetch_array($result1);
	
	$currentRelated = $row1['colors'];	
	$newRelated = implode(",", $relatedArray);
	$fullRelated = $currentRelated . "," . $newRelated;
	
	$sql2 = "UPDATE pics SET relatedColors = '$fullRelated' WHERE id = '$picID'";
	
	$result2 = mysql_query($sql2);

?>