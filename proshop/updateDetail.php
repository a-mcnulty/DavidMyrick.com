<?php

	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';

	if(isset($_POST["myContent"])){
		$data_json = $_POST["myContent"];

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$category = $data_array["catID"];
        	$picID = $data_array["picID"];
        	$content = mysqli_real_escape_string(Database::$conn, $data_array["content"]);
			$content2 = mysqli_real_escape_string(Database::$conn, $data_array["content2"]);			
			$cleanTitle = str_replace('"', '&quot;', $data_array["title"]);
			$cleanTitle = mysqli_real_escape_string(Database::$conn, $cleanTitle);
			$cleanCaption = str_replace('"', '&quot;', $data_array["caption"]);
			$cleanCaption = mysqli_real_escape_string(Database::$conn, $cleanCaption);
			$bgColor = mysqli_real_escape_string(Database::$conn, $data_array["bgColor"]);
			$customSlug = mysqli_real_escape_string(Database::$conn, $data_array["customSlug"]);
			
			/*
			$soldOut = mysqli_real_escape_string(Database::$conn, $data_array["soldOut"]);
			$weight = mysqli_real_escape_string(Database::$conn, $data_array["weight"]);
			$price = mysqli_real_escape_string(Database::$conn, $data_array["price"]);
			$wPrice = mysqli_real_escape_string(Database::$conn, $data_array["wPrice"]);
			$salesPrice = mysqli_real_escape_string(Database::$conn, $data_array["salesPrice"]);
			$featuredQty = mysqli_real_escape_string(Database::$conn, $data_array["featuredQty"]);
			*/
    	}

	} else {
		echo "Error!";
	}

	if (!isset($picID) OR !isset($content)) {
		echo "error updating page!";
		exit;
	}
	
	$slug = createSlug($cleanTitle);
	
	// check if custom slug matches default slug, if different update
	$defaultSlug = $picID . "/" . $slug;
	if ($customSlug != $defaultSlug) {
		
		// make sure custom slug doesn't have a forward slash, also check to make sure there is no duplicate customSlug in system.
		$customSlug = createSlug($customSlug);
		
		$customCheck = uniqueSlug($customSlug);
		if ($customCheck > 0) {
			$addCustomSlug = "";
		}
		$addCustomSlug = $customSlug;
	} else {
		$addCustomSlug = "";
	}
	
	$query2 = "UPDATE pics SET title = '$cleanTitle', caption = '$cleanCaption', slug = '$slug', customSlug = '$addCustomSlug', bgColor = '$bgColor' WHERE id = '$picID'";
	
	$result2 = mysqli_query(Database::$conn, $query2);
	
	if (!$result2) {
			die('Could not query:' . mysqli_error(Database::$conn));
			exit;
	}

	//NEED TO CHECK IF CONTENT PAGE ALREADY EXISTS

	$query1 = "SELECT * FROM content WHERE picID = '$picID'";

	$result1 = mysqli_query(Database::$conn, $query1);

	if (mysqli_num_rows($result1) != 0) {

		$query = "UPDATE content SET content = '$content', content2 = '$content2' WHERE picid = '$picID'";

	} else {

		$query = "INSERT INTO content (picid, content, content2) VALUES ('$picID', '$content', '$content2')";

	}

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
			die('Could not query:' . mysqli_error(Database::$conn));
			exit;
	}

	//echo $query;

	echo "Item has been Updated!";
?>