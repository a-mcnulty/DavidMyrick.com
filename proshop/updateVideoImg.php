<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';
	include 'classes/resize-class.php';

	$vidID = mysql_real_escape_string($_POST['vidID']);
	$catID = mysql_real_escape_string($_POST['catID']);

	$myVar = $_FILES['mainImg']['name'];
	$save_path = getOption("imagePathSave");

	 if ($myVar != "") {

		$uploaddir = getOption("imagePathSave");
		$uploadfile = $uploaddir. $myVar;

		if (move_uploaded_file($_FILES['mainImg']['tmp_name'], $uploadfile)) {

			//Rename file with ID
			rename($uploadfile, $uploaddir.$catID."_".$myVar);


		}
		
		// GET IMAGE SIZE
		$imgSize = getimagesize($save_path . $catID."_".$myVar);
		$imgWidth = $imgSize[0];
		$imgHeight = $imgSize[1];

		//UPDATE DATABASE

		$imgName = $catID . "_" . $myVar;

		$query = "UPDATE videos SET thumbnail = '$imgName', width = '$imgWidth', height = '$imgHeight' WHERE id = '$vidID'";

		$result = mysql_query($query);

		if (!$result) {
				die('Could not query:' . mysql_error());
				exit;
		}

	}

	echo "success";

	 //header("Location: detail.php?picID=" . $picID . "&category=" . $catID . "&status=main_image_has_been_updated");

?>