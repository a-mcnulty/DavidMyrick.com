<?php
	
	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';
	

	if(isset($_POST["myContent"])){
		$data_json = stripslashes($_POST["myContent"]);

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$thisCatID = $data_array["thisCatID"];
        	$catID = $data_array["catID"];
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
		$slugName = $catName;
	} else {
		if ($parentParentID > 0 AND $parentParentID != $parentID) {
			$parentParentName = catName($parentParentID);
			$parentName = subName($parentID);
			$slugName = $parentParentName . " " . $parentName . " " . $catName;
		} else {
			$parentName = catName($parentID);
			$slugName = $parentName . " " . $catName;
		}
	}
	
	$slug = createSlug($slugName);

	if ($parentID != 0) {

		//THIS IS A SUB-CATEGORY
		$query = "UPDATE cat_list SET subName = '$catName', pageType = '$pageType', sectionID = '$sectionID', status = '$status', slug = '$slug' WHERE id = '$catID'";

	} else {

		$query = "UPDATE cat_list SET name = '$catName', pageType = '$pageType', sectionID = '$sectionID', status = '$status', slug = '$slug' WHERE id = '$catID'";

	}

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
			die('Could not query:' . mysqli_error(Database::$conn));
			exit;
	}
	
	$layout = getLayout($pageType);
	$rd = $layout['type'];
	
	echo $rd . "?category=".$catID."&parentID=".$parentID."&parentParentID=".$parentParentID;

?>