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
            $moduleID = $data_array["moduleID"];
        	$videoURL = $data_array["videoURL"];
    	}

	} else {
		echo "Error!";
	}

    if (!$moduleID > 0) {
		$moduleID = 0;
    }
    
    if (!$catID > 0) {
		$catID = 0;
	}
    
    $videoInfo = embedURL($videoURL);

    $vidTitle = mysqli_real_escape_string(Database::$conn, $videoInfo->title);
    $videoImg = $videoInfo->thumbnail_url;
    $videoImg = str_replace("hqdefault", "maxresdefault", $videoImg);
    $vidFrame = $videoInfo->html;
    $vidWidth = $videoInfo->width;
    $vidHeight = $videoInfo->height;

    if ($vidWidth > $vidHeight) {
		$orientation = "landscape";
		} elseif ($vidWidth < $vidHeight) {
			$orientation = "portrait";
			} else {
				$orientation = "square";
	}

    // STEP 1 - add to pics table
    $sql1 = "INSERT INTO pics (catid, title, img, sortBy, filename, imgSrc, width, height, orientation, moduleid) VALUES ('$catID', '$vidTitle', '$videoImg', 0, '$videoImg', 'embed', '$vidWidth', '$vidHeight', '$orientation', '$moduleID')";

	$result1 = mysqli_query(Database::$conn, $sql1);

	if (!$result1) {
        die('Co1uld not query 1:' . mysqli_error(Database::$conn));
        exit;
    }
    
    $picID = mysqli_insert_id(Database::$conn);

    if ($catID > 0) {

        // STEP 2 - add to cat_pics table
        $sql2 = "INSERT INTO cat_pics (catid, picid) VALUES ('$catID', '$picID')";

        $result2 = mysqli_query(Database::$conn, $sql2);

        if (!$result2) {
            die('Co1uld not query 2:' . mysqli_error(Database::$conn));
            exit;
        }

    }

    // STEP 3 - add to videos table
    $sql3 = "INSERT INTO videos (picid, embed) VALUES ('$picID', '$vidFrame')";

    $result3 = mysqli_query(Database::$conn, $sql3);

	if (!$result3) {
        die('Co1uld not query 3:' . mysqli_error(Database::$conn));
        exit;
    }

    echo "success";

?>