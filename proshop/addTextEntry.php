<?php

	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';

	if(isset($_POST["myContent"])){
		$data_json = $_POST["myContent"];

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$catID = $data_array["catID"];
        	$parentID = $data_array["parentID"];
        	$title = mysqli_real_escape_string(Database::$conn, $data_array["title"]);
			$title = str_replace('"', '&quot;', $title);
        	$caption = mysqli_real_escape_string(Database::$conn, $data_array["caption"]);
    	}

	} else {
		echo "Error!";
	}

	if (!isset($catID)) {
		echo "error updating page!";
		exit;
	}

	$slug = createSlug($title);

	$query = "INSERT INTO pics (catid, img, title, caption, slug) VALUES ('$catID', 'no-image.jpg', '$title', '$caption', '$slug')";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
			die('Could not query:' . mysqli_error(Database::$conn));
			exit;
	}
	
	$picID = mysqli_insert_id(Database::$conn);
	
	$query1b = "INSERT INTO cat_pics (picid, catid) VALUES ('$picID', '$catID')";
	
	$result1b = mysqli_query(Database::$conn, $query1b);

	echo "manage_images.php?category=".$catID."&parentID=".$parentID;
