<?php

	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';

	if(isset($_POST["myContent"])){
		$data_json = $_POST["myContent"];

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$catID = $data_array["catID"];
        	$picID = $data_array["picID"];
        	$tags = $data_array["tags"];
			$table = $data_array["table"];
			$type = $data_array["type"];
    	}

	} else {
		echo "Error!";
	}

	if (!isset($picID)) {
		echo "error updating page!";
		exit;
	}
	
	// FIRST STEP IS TO EMPTY OUT ANY VALUES IN THE TAG FIELDS
	$tagSectionArray = array("tags");
	foreach($tagSectionArray as $field) {
	
		$sql = "UPDATE pics SET $field = '' WHERE id = '$picID'";
		
		$result = mysqli_query(Database::$conn, $sql);
		
		if (!$result) {
				die('Could not query1:' . mysqli_error());
				exit;
		}
	
	}
	
	echo "\n\n";
	
	foreach($tags as $tag) {
	
		// NOW WE NEED TO QUERY THE TAGS TABLE AND GET THE SECTION, ONCE WE HAVE THAT WE CAN UPDATE THE RIGHT FIELDS IN THE PICS TABLE
	
		$tagDetail = getTag($tag);
		$section = $tagDetail['section'];
		$theTag = $tagDetail['slug'] . ",";
		
		// get current field str and then append new tag to it
		
		$sql1 = "SELECT $section FROM pics WHERE id = '$picID'";
		
		$result1 = mysqli_query(Database::$conn, $sql1);
		
		if (!$result1) {
				die('Could not query1:' . mysqli_error());
				exit;
		}
		
		$row1 = mysqli_fetch_array($result1);
		
		$currentSection = $row1[$section];		
		$newSection = $currentSection . $theTag;
		
		$sql2 = "UPDATE pics SET $section = '$newSection' WHERE id = '$picID'";
		
		$result2 = mysqli_query(Database::$conn, $sql2);
		
		if (!$result2) {
				die('Could not query2:' . mysqli_error());
				exit;
		}	
	}
	
	/*

	$tagStr = implode(",", $tags);

	if ($table === "videos") {
	
		$sql1 = "UPDATE videos SET tags = '$tagStr' WHERE id = '$picID'";
	
	} else {
	
		$sql1 = "UPDATE pics SET tags = '$tagStr' WHERE id = '$picID'";
		
	}

	$result1 = mysql_query($sql1);
	
	*/

	if ($table === "videos") {
		echo "videoDetail.php?vidID=" . $picID . "&category=" . $catID . "&status=video_has_been_updated";
	} else {
		echo "detail.php?picID=" . $picID . "&category=" . $catID . "&type=";
	}

?>