<?php

	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	if(isset($_POST["myContent"])){
		$data_json = stripslashes($_POST["myContent"]);

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$thisCatID = $data_array["thisCatID"];
        	$catID = $data_array["catID"];
        	$parentID = $data_array["parentID"];
        	$catName = mysql_real_escape_string($data_array["catName"]);
        	$pageType = strtolower($data_array["pageType"]);
        	$link = $data_array["link"];
        	$status = strtolower($data_array["status"]);
    	}

	} else {
		echo "Error!";
	}

	if (!isset($catName)) {
		echo "error updating page!";
		exit;
	}

	if ($parentID == 0) {
		$slugName = $catName;
	} else {
		$parentName = catName($parentID);
		$slugName = $parentName . " " . $catName;
	}
	$slug = createSlug($slugName);

	if ($parentID != 0) {

		//THIS IS A SUB-CATEGORY
		$query = "UPDATE cat_list SET subName = '$catName', pageType = '$pageType', sectionID = '$sectionID', status = '$status', imgOFF = '$navOFF', imgON = '$navON', slug = '$slug', parentID = '$parentID' WHERE id = '$catID'";

	} else {

		$query = "UPDATE cat_list SET name = '$catName', pageType = '$pageType', sectionID = '$sectionID', status = '$status', imgOFF = '$navOFF', imgON = '$navON', slug = '$slug' WHERE id = '$catID'";

	}

	$result = mysql_query($query);

	if (!$result) {
			die('Could not query:' . mysql_error());
			exit;
	}

	$rd = "manage_categories.php";

	//header("Location: ".$rd."?category=".$catID."&parentID=".$parentID."&status=category_has_been_updated");

	echo $rd . "?category=".$catID."&parentID=".$parentID;

?>