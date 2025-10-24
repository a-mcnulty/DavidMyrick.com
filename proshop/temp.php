<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';
	include 'classes/resize-class.php';
	
	$save_path = getOption("imagePathSave");
	
	$sql = "SELECT * FROM pics LIMIT 0,10";
	
	$result = mysql_query($query);

	if (!$result) {
			die('Could not query:' . mysql_error());
			exit;
	}
	
	for ($count = 1; $item = mysql_fetch_array($result); ++$count) {
		
		$filename = $item['img'];
		
		// GET IMAGE SIZE
		$imgSize = getimagesize($save_path . $filename);
		$imgWidth = $imgSize[0];
		$imgHeight = $imgSize[1];
		
		if ($imgWidth > $imgHeight) {
			$orientation = "landscape";
			} elseif ($imgWidth < $imgHeight) {
				$orientation = "portrait";
				} else {
					$orientation = "square";
		}
		
		echo $imgWidth . "<br />";
		
		/*
		
		if (!file_exists($save_path . "square/".$filename)) {
			$resizeObj = new resize($save_path.$filename);

			$resizeObj -> resizeImage(200, 200, 'crop');

			$resizeObj -> saveImage($save_path."square/" . $filename, 80);
		}
		
		if (!file_exists($save_path . "500/".$filename)) {
			
			if ($imgWidth > 500) {
			
				$resizeObj = new resize($save_path.$filename);
				$resizeObj -> resizeImage(500, 0, 'landscape');
				$resizeObj -> saveImage($save_path."500/" . $filename, 80);
				
			} else {
				copy($save_path.$filename, $save_path."500/" . $filename);
			}
		}
		
		if (!file_exists($save_path . "750/".$filename)) {
			
			if ($imgWidth > 750) {
				$resizeObj = new resize($save_path.$filename);
				$resizeObj -> resizeImage(750, 0, 'landscape');
				$resizeObj -> saveImage($save_path."750/" . $filename, 80);
			} else {
				copy($save_path.$filename, $save_path."750/" . $filename);
			}
		}
		
		if (!file_exists($save_path . "1000/".$filename)) {
			
			if ($imgWidth > 1000) {
				$resizeObj = new resize($save_path.$filename);
				$resizeObj -> resizeImage(1000, 0, 'landscape');
				$resizeObj -> saveImage($save_path."1000/" . $filename, 80);
			
			} else {
				copy($save_path.$filename, $save_path."1000/" . $filename);
			}
			
		}
		
		if (!file_exists($save_path . "1536/".$filename)) {
			
			if ($imgWidth > 1536) {
				$resizeObj = new resize($save_path.$filename);
				$resizeObj -> resizeImage(1536, 0, 'landscape');
				$resizeObj -> saveImage($save_path."1536/" . $filename, 80);
			} else {
				copy($save_path.$filename, $save_path."1536/" . $filename);
			}
			
		}
		
		if (!file_exists($save_path . "1920/".$filename)) {
			
			if ($imgWidth > 1920) {
				$resizeObj = new resize($save_path.$filename);
				$resizeObj -> resizeImage(1920, 0, 'landscape');
				$resizeObj -> saveImage($save_path."1920/" . $filename, 80);
			} else {
				copy($save_path.$filename, $save_path."1920/" . $filename);
			}
			
		}
		
		// CREATE GRAYSCALE PNG FOR SCOLL PLACEHOLDER
		$strPicName = $filename;
		$strPicName = str_replace(".jpg", "", $strPicName);
		$strPicName = str_replace(".jpeg", "", $strPicName);
		$strPicName = str_replace(".gif", "", $strPicName);
		$strPicName = str_replace(".png", "", $strPicName);

		$im = imagecreatetruecolor($imgWidth, $imgHeight);
		$gray = imagecolorallocate($im, 0, 0, 0);

		imagecolortransparent($im, $gray);

		imagepng($im, "../images/pics/siteGray/" . $strPicName . ".png");

		imagedestroy($im);
		
		*/
		
	}
	
	echo "done";
	
?>