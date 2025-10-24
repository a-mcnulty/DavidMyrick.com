<?php

	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	if(isset($_POST["myContent"])){
		$data_json = $_POST["myContent"];

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$catID = $data_array["category"];
        	$parentID = $data_array["parentID"];
        	$detailID = $data_array["detailID"];
        	$picIDArray = $data_array["picID"];
        	$imgArray = $data_array["img"];
        	$titleArray = str_replace('"', '&quot;', $data_array["title"]);
        	$captionsArray = $data_array["captions"];
        	$picURLArray = $data_array["picURL"];
        	$shortDescArray = $data_array["shortDesc"];
        	$priceArray = $data_array["price"];
        	$wPriceArray = $data_array["wprice"];
        	$copyCatArray = $data_array["copyCat"];
        	$moveCatArray = $data_array["moveCat"];
			$artistArray = $data_array["artists"];
			$product = $data_array["product"];
			$hoverArray = $data_array["hover"];
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
	foreach($picIDArray as $picID) {

		//echo $picID . " - " . $captions[$myCount] . "<br />";

		$thisImg = mysql_real_escape_string($imgArray[$myCount]);
		$thisCaption = mysql_real_escape_string($captionsArray[$myCount]);
		$thisTitle = str_replace('"', '&quot;', $titleArray[$myCount]);
		$thisTitle = mysql_real_escape_string($titleArray[$myCount]);
		$thisURL = mysql_real_escape_string($picURLArray[$myCount]);
		$thisDesc = mysql_real_escape_string($shortDescArray[$myCount]);
		$thisMoveCat = mysql_real_escape_string($moveCatArray[$myCount]);
		$thisCopyCat = mysql_real_escape_string($copyCatArray[$myCount]);
		$thisPrice = mysql_real_escape_string($priceArray[$myCount]);
		$thisWPrice = mysql_real_escape_string($wPriceArray[$myCount]);
		$thisSlug = createSlug($titleArray[$myCount]);
		$thisArtist = mysql_real_escape_string($artistArray[$myCount]);
		$thisHover = mysql_real_escape_string($hoverArray[$myCount]);

		$query = "UPDATE pics SET catid = '$thisMoveCat', picid = '$detailID', artistid = '$thisArtist', price = '$thisPrice', wPrice = '$thisWPrice', caption = '$thisCaption', title = '$thisTitle', picURL = '$thisURL', shortDesc = '$thisDesc', sortBy = '$listingCounter', slug = '$thisSlug', hover = '$thisHover' WHERE id = '$picID'";

		$result = mysql_query($query);

		if (!$result) {
				die('Could not query 1:' . mysql_error());
				exit;
		}

		if ($thisCopyCat != "") {

			// NEED TO RENAME IMAGE SO EACH CATEGORY HAS UNIQUE FILENAME
			
			$newImgName = $thisCopyCat . "_" . $thisImg;
			
			$current1 = getOption("imagePath") . $thisImg;
			$new1 = getOption("imagePath") . $thisCopyCat . "_" . $thisImg;
			copy($current1, $new1) or die("Unable to copy $current1 to $new1.");
			
			$current2 = getOption("imagePath") . "resized/" . $thisImg;
			$new2 = getOption("imagePath") . "resized/" . $thisCopyCat . "_" . $thisImg;
			copy($current2, $new2) or die("Unable to copy $current2 to $new2.");
			
			$current3 = getOption("imagePath") . "siteThumbs/" . $thisImg;
			$new3 = getOption("imagePath") . "siteThumbs/" . $thisCopyCat . "_" . $thisImg;
			copy($current3, $new3) or die("Unable to copy $current3 to $new3.");
			
			$current4 = getOption("imagePath") . "thumbs/" . $thisImg;
			$new4 = getOption("imagePath") . "thumbs/" . $thisCopyCat . "_" . $thisImg;
			copy($current4, $new4) or die("Unable to copy $current4 to $new.4");

			$query2 = "INSERT INTO pics (catid, caption, img, sortBy, title, price, wPrice, picURL, shortDesc, slug, artistid) VALUES ('$thisCopyCat', '$thisCaption', '$newImgName', '0', '$thisTitle', '$thisPrice', '$thisWPrice', '$thisURL', '$thisDesc', '$thisSlug', '$thisArtist')";

			$result2 = mysql_query($query2);

			if (!$result2) {
					die('Could not query2:' . mysql_error());
					exit;
			}

			$newPicID = mysql_insert_id();

			// WE NEED TO ALSO COPY OVER ANY DATA FROM THE CONTENT TABLE.

			$query33 = "SELECT * FROM content WHERE picid = '$picID'";

			$result33 = mysql_query($query33);

			if (!$result33) {
					die('Could not query33:' . mysql_error());
					exit;
			}

			$contentArray = mysql_fetch_array($result33);

			$thisContent = mysql_real_escape_string($contentArray['content']);
			$thisAddl = mysql_real_escape_string($contentArray['addl']);

			$query3 = "INSERT INTO content (picid, content) VALUES ('$newPicID', '$thisContent')";

			$result3 = mysql_query($query3);

			if (!$result3) {
					die('Could not query3:' . mysql_error());
					exit;
			}

			// WE NEED TO ALSO COPY OVER ANY STATIC PICTURES AND AUDIO.

			$query44 = "SELECT * FROM pics WHERE picid = '$picID'";

			$result44 = mysql_query($query44);

			if (!$result44) {
					die('Could not query44:' . mysql_error());
					exit;
			}

			for ($count = 1; $pic = mysql_fetch_array($result44); ++$count) {

				$oldPic = $pic['img'];
				$oldAudio = $pic['audio'];

				$query4 = "INSERT INTO pics (picid, img) VALUES ('$newPicID', '$oldPic')";

				$result4 = mysql_query($query4);

				if (!$result4) {
						die('Could not query4:' . mysql_error());
						exit;
				}

			}

			// WE NEED TO COPY OVER VIDEOS.

			$query55 = "SELECT * FROM videos WHERE picid = '$picID'";

			$result55 = mysql_query($query55);

			if (!$result55) {
					die('Could not query55:' . mysql_error());
					exit;
			}

			for ($count = 1; $video = mysql_fetch_array($result55); ++$count) {

				$oldVideo = $video['embed'];

				$query5 = "INSERT INTO videos (picid, embed) VALUES ('$newPicID', '$oldVideo')";

				$result5 = mysql_query($query5);

				if (!$result5) {
						die('Could not query5:' . mysql_error());
						exit;
				}

			}

		}

		$myCount++;
		$listingCounter++;

	}
	
	if ($product === "yes") {
	
		echo "manage_products.php?catID=" . $catID;
	
	} else {

		echo "manage_images.php?category=" . $catID;
		
	}

?>