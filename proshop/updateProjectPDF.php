<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';
	include 'classes/resize-class.php';

	$picID = mysql_real_escape_string($_POST['picID']);
	$catID = mysql_real_escape_string($_POST['catID']);

	$myVar = $_FILES['pdfFile']['name'];
	
	//GET A CLEAN FILE NAME HERE
	//$cleanName = cleanFileName($myVar);

	 if ($myVar != "") {

		$uploaddir = "../downloads/";
		$save_path = "../downloads/";
		$uploadfile = $uploaddir. $myVar;

		if (move_uploaded_file($_FILES['pdfFile']['tmp_name'], $uploadfile)) {

			//Rename file with ID
			rename($uploadfile, $uploaddir.$catID."_".$myVar);


		}

	 }

	//UPDATE DATABASE

	$pdfName = $catID . "_" . $myVar;

	$query = "UPDATE pics SET pdf = '$pdfName' WHERE id = '$picID'";

	$result = mysql_query($query);

	if (!$result) {
			die('Could not query:' . mysql_error());
			exit;
	}

	echo "success";

	 //header("Location: detail.php?picID=" . $picID . "&category=" . $catID . "&status=main_image_has_been_updated");

?>