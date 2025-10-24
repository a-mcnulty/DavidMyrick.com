<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';
	
	if(isset($_POST["myContent"])){
		$data_json = stripslashes($_POST["myContent"]);

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$picID = $data_array["picID"];
			$colorID = $data_array["colorID"];
    	}

	} else {
		echo "Error!";
	}

	if (!isset($picID)) {
		echo "error updating page!";
		exit;
	}
	
	$sql2 = "UPDATE pics SET colorid = '$colorID' WHERE id = '$picID'";
	
	$result2 = mysql_query($sql2);

?>