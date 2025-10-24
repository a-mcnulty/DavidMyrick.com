<?php
	
	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';
	

	if(isset($_POST["myContent"])){
		$data_json = stripslashes($_POST["myContent"]);

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$parentID = $data_array["parentID"];
			$parentParentID = $data_array["parentParentID"];
        	$catName = mysqli_real_escape_string(Database::$conn, $data_array["catName"]);
        	$pageType = strtolower($data_array["pageType"]);
        	$link = $data_array["link"];
        	$status = strtolower($data_array["status"]);
			$sectionID = 0;
    	}

	} else {
		echo "Error!";
	}

	if (!isset($catName)) {
		echo "error updating page!";
		exit;
	}
	
	if ($parentID == 0) {
		$thisParentID = 0;
		$slugName = $catName;
		$name = $catName;
		$subName = "";
	} else {
		
		$name = "";
		$subName = $catName;
		$thisParentID = $parentID;
		
		if ($parentParentID > 0) {
			$parentParentName = catName($parentParentID);
			$parentName = subName($parentID);
			$slugName = $parentParentName . " " . $parentName . " " . $catName;
		} else {
			$parentName = catName($parentID);
			$slugName = $parentName . " " . $catName;
		}
	}
	
	$slug = createSlug($slugName);

	/*
	if ($parentID != 0) {

		//THIS IS A SUB-CATEGORY
		$name = "";
		$subName = $catName;
		$thisParentID = $parentID;
		$parentName = catName($parentID);
		$slugName = $parentName . " " . $subName;
		$slug = createSlug($slugName);

	} else {

		//THIS IS A MAIN CATEGORY
		$name = $catName;
		$subName = "";
		$slug = createSlug($name);
	}
	*/

	/*
	//ADD NAV IMAGES TO SERVER

	$menuOff = $_FILES['navOFF']['name'];

	if ($menuOff != "") {

		$uploaddir = '../images/';
		$uploadfile = $uploaddir. $menuOff;

		if (move_uploaded_file($_FILES['navOFF']['tmp_name'], $uploadfile)) {
			//echo "File is valid, and was successfully uploaded.\n";
		} else {
			echo "Error uploading the menu image\n";
			exit;
		}

	}

	$menuOn = $_FILES['navON']['name'];

	if ($menuOn != "") {

		$uploaddir = '../images/';
		$uploadfile = $uploaddir. $menuOn;

		if (move_uploaded_file($_FILES['navON']['tmp_name'], $uploadfile)) {
			//echo "File is valid, and was successfully uploaded.\n";
		} else {
			echo "Error uploading the menu image\n";
			exit;
		}

	}
	
	*/

	$query = "INSERT INTO cat_list (name, subName, parentID, pageType, sectionID, status, link, slug) VALUES ('$name', '$subName', '$thisParentID', '$pageType', '$sectionID', '$status', '$link', '$slug')";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
			die('Could not query:' . mysqli_error(Database::$conn));
			exit;
	}

	$catID = mysqli_insert_id(Database::$conn);

	$layout = getLayout($pageType);
	$rd = $layout['type'];

	if ($pageType === "artist") {
		
		echo $rd . "?category=".$parentID;
		
	} else {

		echo $rd . "?category=".$catID."&parentID=".$parentID."&parentParentID=" . $parentParentID . "&status=page_has_been_added";
		
	}

?>