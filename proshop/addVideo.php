<?php

	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	if(isset($_POST["myContent"])){
		$data_json = $_POST["myContent"];

		$data_array = json_decode($data_json, true);

		print_r($data_array);

		if($data_array !== null){
        	$catID = $data_array["catID"];
        	$embedCode = $data_array["embedCode"];
        	$title = mysql_real_escape_string($data_array["title"]);
    	}

	} else {
		echo "Error!";
	}

	//echo $embedCode;
	$slug = createSlug($title);

	if (!isset($catID) OR !isset($embedCode)) {
		echo "error updating page!";
		exit;
	}

	$query = "INSERT INTO videos (catid, embed, title, slug) VALUES ('$catID', '$embedCode', '$title', '$slug')";

	$result = mysql_query($query);
	
	$vidid = mysql_insert_id();
	
	$query2 = "INSERT INTO cat_videos (catid, vidid, sortby) VALUES ('$catID', '$vidid', '0')";

	$result2 = mysql_query($query2);

	//header("Location: manage_video.php?category=".$catID."&status=video_has_been_added");

?>