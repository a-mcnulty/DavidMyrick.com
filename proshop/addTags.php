<?php

	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';

	if(isset($_POST["myContent"])){
		$data_json = $_POST["myContent"];

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$tagStr = mysqli_real_escape_string(Database::$conn, $data_array["tag"]);
			$section = mysqli_real_escape_string(Database::$conn, $data_array["section"]);
    	}

	} else {
		echo "Error!";
	}

	if (!isset($tagStr)) {
		echo "error updating page!";
		exit;
	}

	$tagStr = str_replace(", ", ",", $tagStr);
	$tagArray = explode(",", $tagStr);

	foreach($tagArray AS $tag) {
	
		$tagSlug = createSlug($tag);

		$query = "INSERT INTO tags (tag, section, slug) VALUES ('$tag', '$section', '$tagSlug')";

		$result = mysqli_query(Database::$conn, $query);

		if (!$result) {
				die('Could not query:' . mysqli_error());
				exit;
		}

	}

	echo "manage_tags.php";
?>