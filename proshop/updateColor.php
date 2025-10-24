<?php

	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	if(isset($_POST["myContent"])){
		$data_json = $_POST["myContent"];

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$catID = $data_array["catID"];
        	$picID = $data_array["picID"];
        	$color = $data_array["color"];
    	}

	} else {
		echo "Error!";
	}

	if (!isset($picID)) {
		echo "error updating page!";
		exit;
	}
	
	$sql1 = "UPDATE pics SET colorid = '$color' WHERE id = '$picID'";

	$result1 = mysql_query($sql1);

	echo "detail.php?picID=" . $picID . "&category=" . $catID . "&status=video_has_been_updated&type=product";

?>