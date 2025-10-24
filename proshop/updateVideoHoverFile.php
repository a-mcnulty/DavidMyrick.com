<?php

session_start();
include 'includes/secure.php';
include '../includes/connect.php';
include 'includes/functions.php';
require_once('getid3/getid3/getid3.php');

$getID3 = new getID3;

$currentVideo = mysqli_real_escape_string(Database::$conn, $_POST['currentVideo']);
$currentVideoMobile = mysqli_real_escape_string(Database::$conn, $_POST['currentVideoMobile']);
$currentWidth = mysqli_real_escape_string(Database::$conn, $_POST['currentWidth']);
$currentHeight = mysqli_real_escape_string(Database::$conn, $_POST['currentHeight']);

$picID = mysqli_real_escape_string(Database::$conn, $_POST['picID']);
$catID = mysqli_real_escape_string(Database::$conn, $_POST['catID']);
$bgVideoType = mysqli_real_escape_string(Database::$conn, $_POST['bgVideoType']);

$myVar = $_FILES['videoHover']['name'];
$myVar2 = $_FILES['videoHoverMobile']['name'];

$uploaddir = "../videos/";

$fileName = $currentVideo;
$fileName2 = $currentVideoMobile;
$width = $currentWidth;
$height = $currentHeight;

// handle file uploads
if ($myVar != "") {

	$uploadfile = $uploaddir . $myVar;

	if (move_uploaded_file($_FILES['videoHover']['tmp_name'], $uploadfile)) {

		//Rename file with ID
		rename($uploadfile, $uploaddir . $picID . "_" . $myVar);
	}

	$fileName = $picID . "_" . $myVar;
}

if ($myVar2 != "") {

	$uploadfile2 = $uploaddir . $myVar2;

	if (move_uploaded_file($_FILES['videoHoverMobile']['tmp_name'], $uploadfile2)) {

		//Rename file with ID
		rename($uploadfile2, $uploaddir . $picID . "_" . $myVar2);
	}

	$fileName2 = $picID . "_" . $myVar2;
}

//UPDATE DATABASE

$filePath = "../videos/" . $fileName;

if ($myVar != "") {
	$ThisFileInfo = $getID3->analyze($filePath);

	$width = $ThisFileInfo['video']['resolution_x'];
	$height = $ThisFileInfo['video']['resolution_y'];
	$vidDuration = intval($ThisFileInfo['playtime_seconds']);
}

$query1 = "SELECT * FROM videos WHERE picid = '$picID'";

$result1 = mysqli_query(Database::$conn, $query1);

if (mysqli_num_rows($result1) > 0) {

	$query = "UPDATE videos SET hoverFile = '$fileName', hoverFileMobile = '$fileName2', bgVideoType = '$bgVideoType', video_time = '$vidDuration' WHERE picid = '$picID'";
} else {

	$query = "INSERT INTO videos (hoverFile, hoverFileMobile, bgVideoType, video_time, picid) VALUES ('$fileName', '$fileName2', '$bgVideoType', '$vidDuration', '$picID')";
}

$result = mysqli_query(Database::$conn, $query);

if (!$result) {
	die('Could not query:' . mysqli_error(Database::$conn));
	exit;
}


echo "success";
