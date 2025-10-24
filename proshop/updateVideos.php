<?php

	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	if(isset($_POST["myContent"])){
		$data_json = $_POST["myContent"];

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$catID = $data_array["category"];
        	$videoIDArray = $data_array["videoID"];
        	$titlesArray = $data_array["titles"];
			$captionsArray = $data_array["captions"];
        	$copyCatArray = $data_array["copyCat"];
        	$moveCatArray = $data_array["moveCat"];
			$deleteArray = $data_array["deleteArray"];
    	}

	} else {
		echo "Error!";
	}

	if (!isset($catID)) {
		echo "error updating page!";
		exit;
	}

	$myCount = 0;
	$listingCounter = 1;
	foreach($videoIDArray as $videoID) {

		$thisTitle = mysql_real_escape_string($titlesArray[$myCount]);
		$thisCaption = mysql_real_escape_string($captionsArray[$myCount]);
		$thisMoveCat = mysql_real_escape_string($moveCatArray[$myCount]);
		$thisCopyCat = mysql_real_escape_string($copyCatArray[$myCount]);
		$thisDelete = mysql_real_escape_string($deleteArray[$myCount]);
		
		$thisSlug = createSlug($titlesArray[$myCount] . " " . $captionsArray[$myCount]);

		$query = "UPDATE videos SET title = '$thisTitle', caption = '$thisCaption', slug = '$thisSlug', sortBy = '$listingCounter' WHERE id = '$videoID'";
		
		$result = mysql_query($query);
		
		// NEW LOGIC TO ALLOW FOR SINGLE VID TO LIVE IN MULTIPLE CATEGORIES
		
		$query1a = "SELECT * FROM cat_videos WHERE catid = '$thisMoveCat' and vidid = '$videoID'";
		
		$result1a = mysql_query($query1a);
		$num1a = mysql_numrows($result1a);
		
		//echo $num1a . "\n\n";
		
		if ($num1a == 0) {
		
			$query1b = "INSERT INTO cat_videos (vidid, catid) VALUES ('$videoID', '$thisMoveCat')";
			
			$result1b = mysql_query($query1b);
			
		}
		
		// if they are moving to a new category we need to delete from existing
		if ($thisMoveCat != $catID) {
		
			$query1c = "DELETE FROM cat_videos WHERE vidid = '$videoID' AND catid = '$catID'";
			
			$result1c = mysql_query($query1c);
		
		}

		if ($thisCopyCat != "") {
		
			// NEW COPY LOGIC JUST CREATES A REFERENCE IN THE cat_pics TABLE
			
			$query2a = "SELECT * FROM cat_videos WHERE catid = '$thisCopyCat' and vidid = '$videoID'";
			
			$result2a = mysql_query($query2a);
			$num2a = mysql_numrows($result2a);
			
			if ($num2a == 0) {
			
				$query2b = "INSERT INTO cat_videos (catid, vidid) VALUES ('$thisCopyCat', '$videoID')";
				
				$result2b = mysql_query($query2b);
				
			}

		}
		
		// check for any "yes" values in deleteArray and remove from this cat
		if ($thisDelete === "yes") {
		
			$query3 = "DELETE FROM cat_videos WHERE catid = '$catID' AND vidid = '$videoID'";
			
			$result3 = mysql_query($query3);

			if (!$result3) {
					die('Could not query 3:' . mysql_error());
					exit;
			}
			
		}

		$myCount++;
		$listingCounter++;

	}

	echo "manage_video.php?category=" . $catID;

	//header("Location: manage_video.php?category=".$catID."&status=videos_have_been_updated");

?>