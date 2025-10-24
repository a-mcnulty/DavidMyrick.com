<?php

	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';
	require_once('getid3/getid3/getid3.php');
	
	$getID3 = new getID3;

	$picID = mysqli_real_escape_string(Database::$conn, $_POST['picID']);
	$catID = mysqli_real_escape_string(Database::$conn, $_POST['catID']);

	$myVar = $_FILES['video']['name'];

	 if ($myVar != "") {

		$uploaddir = "../videos/";
		$uploadfile = $uploaddir. $myVar;

		if (move_uploaded_file($_FILES['video']['tmp_name'], $uploadfile)) {

			//Rename file with ID
			rename($uploadfile, $uploaddir.$picID."_".$myVar);


		}

		//UPDATE DATABASE

		$fileName = $picID . "_" . $myVar;
		$filePath = "../videos/" . $fileName;
		
		$ThisFileInfo = $getID3->analyze($filePath);
		$width = $ThisFileInfo['video']['resolution_x'];
		$height = $ThisFileInfo['video']['resolution_y'];
		
		$query1 = "SELECT * FROM videos WHERE picid = '$picID'";
		
		$result1 = mysqli_query(Database::$conn, $query1);
		
		if (mysqli_num_rows($result1) > 0 ) {

			$query = "UPDATE videos SET file = '$fileName', width = '$width', height = '$height' WHERE picid = '$picID'";
			
		} else {
		
			$query = "INSERT INTO videos (file, picid, width, height) VALUES ('$fileName', '$picID', '$width', '$height')";
		
		}

		$result = mysqli_query(Database::$conn, $query);

		if (!$result) {
				die('Could not query:' . mysqli_error(Database::$conn));
				exit;
		}

	}

	echo "success";

	 //header("Location: detail.php?picID=" . $picID . "&category=" . $catID . "&status=main_image_has_been_updated");

?>