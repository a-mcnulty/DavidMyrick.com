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
    	}

	} else {
		echo "Error!";
	}

	if (!isset($picID)) {
		echo "error updating page!";
		exit;
	}
	
	for($i=0; $i<=5; $i++) {
		
		if (isset($data_array["embedCode" . $i])) {
			$theVideo = mysqli_real_escape_string(Database::$conn, $data_array["embedCode" . $i]);
		}
		
		if ($theVideo != "") {
		
			// we have a video, check if it exists in video table
			
			$sql1 = "SELECT id FROM videos WHERE picid = '$picID' AND sortBy = '$i'";
			
			//echo $sql1 . "\n\n";

			$result1 = mysqli_query(Database::$conn, $sql1);
			$num = mysqli_num_rows($result1);
			
			//echo $num . "\n";
			
			if ($num == 0) {

				$query = "INSERT INTO videos (embed, picid, sortBy) VALUES ('$theVideo', '$picID', $i)";
				
			} else {
			
				$query = "UPDATE videos SET embed = '$theVideo' WHERE picid = '$picID' AND sortBy = $i";
			
			}
			
		} else {
		
			$query = "UPDATE videos SET embed = '' WHERE picid = '$picID' AND sortBy = $i";
		
		}
		
		$result = mysqli_query(Database::$conn, $query);

		if (!$result) {
				die('Could not query:' . mysqli_error(Database::$conn));
				exit;
		}
	
	}

	echo "detail.php?picID=" . $picID . "&category=" . $catID . "&status=video_has_been_updated";

?>