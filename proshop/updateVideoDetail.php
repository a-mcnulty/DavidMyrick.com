<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	if(isset($_POST["myContent"])){
		$data_json = $_POST["myContent"];

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$category = $data_array["catID"];
        	$vidID = $data_array["vidID"];
        	$embedCode = $data_array["embedCode"];

			$cleanTitle = mysql_real_escape_string($data_array["title"]);
			$cleanCaption = mysql_real_escape_string($data_array["caption"]);
			
        	$content = mysql_real_escape_string($data_array["content"]);
    	}

	} else {
		echo "Error!";
	}

	if (!isset($vidID) OR !isset($content)) {
		echo "error updating page!";
		exit;
	}
	
	$thisSlug = createSlug($cleanTitle . " " . $cleanCaption);

	$sql = "UPDATE videos SET embed = '$embedCode', title = '$cleanTitle', caption = '$cleanCaption', slug = '$thisSlug' WHERE id = '$vidID'";

	$result = mysql_query($sql);

	//NEED TO CHECK IF CONTENT PAGE ALREADY EXISTS

	$query1 = "SELECT * FROM content WHERE vidID = '$vidID'";

	$result1 = mysql_query($query1);

	if (!$result1) {
			die('Could not query:' . mysql_error());
			exit;
	}

	if (mysql_numrows($result1) != 0) {

		$query = "UPDATE content SET content = '$content' WHERE vidid = '$vidID'";

	} else {

		$query = "INSERT INTO content (vidid, content) VALUES ('$vidID', '$content')";

	}

	$result = mysql_query($query);

	if (!$result) {
			die('Could not query:' . mysql_error());
			exit;
	}

	//echo $query;

	echo "Text has been updated";
?>