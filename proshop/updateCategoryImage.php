<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';
	include 'classes/resize-class.php';

	$catID = mysql_real_escape_string($_POST['catID']);

	$myVar = $_FILES['mainImg']['name'];
	
	//GET A CLEAN FILE NAME HERE
	$cleanName = cleanFileName($myVar);

	 if ($myVar != "") {

		$uploaddir = getOption("imagePath");
		$save_path = getOption("imagePath");
		$uploadfile = $uploaddir. $myVar;

		if (move_uploaded_file($_FILES['mainImg']['tmp_name'], $uploadfile)) {

			//Rename file with ID
			rename($uploadfile, $uploaddir.$catID."_".$cleanName);


		}

	 }

	//UPDATE DATABASE

	$imgName = $catID . "_" . $cleanName;

	$query = "UPDATE cat_list SET imgON = '$imgName' WHERE id = '$catID'";

	$result = mysql_query($query);

	if (!$result) {
			die('Could not query:' . mysql_error());
			exit;
	}

	echo "success";

	 //header("Location: detail.php?picID=" . $picID . "&category=" . $catID . "&status=main_image_has_been_updated");

?>