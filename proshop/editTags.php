<?php

	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';

	if(isset($_POST["myContent"])){
		$data_json = $_POST["myContent"];

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$tagIDArray = $data_array["tagID"];
			$tagArray = $data_array["tag"];
			$oldtagArray = $data_array["oldTag"];
			$section = $data_array["section"];
    	}

	} else {
		echo "Error!";
	}

	if (!isset($tagIDArray)) {
		echo "error updating page!";
		exit;
	}

	$arrayCount = 0;
	foreach($tagArray AS $tag) {
	
		$tagSlug = createSlug($tag);
		$oldTag = $oldtagArray[$arrayCount];
		$cleanTag = str_replace("'", "&rsquo;", $tag);
		
		if ($oldTag != $tagSlug) {
		
			/* NEED TO RE-ASSIGN ANY PICS THAT HAVE OLD TAG WITH NEW TAG */
			
			$query1 = "SELECT * FROM pics WHERE $section LIKE '%" . $oldTag . "%'";
			
			//echo $query1 . "<br /><br />";

			$result1 = mysqli_query(Database::$conn, $query1);

			if (!$result1) {
					die('Could not query 1:' . mysqli_error());
					exit;
			}
			
			$row = mysqli_fetch_array($result1);
			$currentStr = $row[$section];
			$newStr = str_replace($oldTag, $tagSlug, $currentStr);
			
			/*
			echo $currentStr . "<br />";
			echo $oldTag . "<br />";
			echo $tagSlug . "<br />";
			echo $newStr . "<br />";
			*/
			
			$query2 = "UPDATE pics SET $section = '$newStr' WHERE $section = '$currentStr'";
			
			$result2 = mysqli_query(Database::$conn, $query2);

			if (!$result2) {
					die('Could not query 2:' . mysqli_error());
					exit;
			}

			
			/*
			echo $query2 . "\n";
			
			//echo $newStr . "\n";
			echo "*********************************\n";
			*/

			
		}
		
		$query = "UPDATE tags SET tag = '$cleanTag', slug = '$tagSlug' WHERE id = '$tagIDArray[$arrayCount]'";

		$result = mysqli_query(Database::$conn, $query);

		if (!$result) {
				die('Could not query:' . mysqli_error());
				exit;
		}
		
		$arrayCount++;

	}

	echo "manage_tags.php";
?>