<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	if(isset($_POST["myContent"])){
		$data_json = stripslashes($_POST["myContent"]);

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$catID = $data_array["catID"];
			$parentID = $data_array["parentID"];
        	$subName = mysql_real_escape_string($data_array["subName"]);
        	$username = mysql_real_escape_string($data_array["username"]);
			$password = mysql_real_escape_string($data_array["password"]);
    	}

	} else {
		echo "Error!";
	}

	if (!isset($catID)) {
		echo "error updating page!";
		exit;
	}

	$folderName = createSlug($subName);
	
	//ADD NAV IMAGES TO SERVER

	$menuOff = $_FILES['navOFF']['name'];

	if ($menuOff != "") {

		$uploaddir = '../clients/logos/';
		$uploadfile = $uploaddir. $menuOff;

		if (move_uploaded_file($_FILES['navOFF']['tmp_name'], $uploadfile)) {
			//echo "File is valid, and was successfully uploaded.\n";
		} else {
			echo "Error uploading the menu image\n";
			exit;
		}

	}
	
	$sql = "UPDATE cat_list SET username = '$username', password = '$password', slug = '$folderName', imgOFF = '$menuOff' WHERE id = '$catID'";

	$result = mysql_query($sql);

	if (!$result) {
			die('Could not query:' . mysql_error());
			exit;
	}
	
	echo "manage_clients.php?category=" . $catID . "&parentID=" . $parentID;

?>