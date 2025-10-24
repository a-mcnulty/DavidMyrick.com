<?php

session_start();
include 'includes/secure.php';
include '../includes/connect.php';
include 'includes/functions.php';

if (isset($_POST["myContent"])) {
	$data_json = $_POST["myContent"];

	$data_array = json_decode($data_json, true);

	if ($data_array !== null) {
		$catID = $data_array["catID"];
		$tags = $data_array["tags"];
	}
} else {
	echo "Error!";
}

if (!isset($catID)) {
	echo "error updating page!";
	exit;
}

// FIRST STEP IS TO EMPTY OUT ANY VALUES IN THE TAG FIELDS

$sql = "UPDATE cat_list SET tags = '' WHERE id = '$catID'";

$result = mysqli_query(Database::$conn, $sql);

if (!$result) {
	die('Could not query1:' . mysqli_error(Database::$conn));
	exit;
}

foreach ($tags as $tag) {

	// NOW WE NEED TO QUERY THE TAGS TABLE AND GET THE SECTION, ONCE WE HAVE THAT WE CAN UPDATE THE RIGHT FIELDS IN THE PICS TABLE

	$tagDetail = getTag($tag);
	$theTag = $tagDetail['slug'] . ",";

	// get current field str and then append new tag to it

	$sql1 = "SELECT tags FROM cat_list WHERE id = '$catID'";

	$result1 = mysqli_query(Database::$conn, $sql1);

	if (!$result1) {
		die('Could not query1:' . mysqli_error(Database::$conn));
		exit;
	}

	$row1 = mysqli_fetch_array($result1);

	$currentSection = $row1['tags'];
	$newSection = $currentSection . $theTag;

	$sql2 = "UPDATE cat_list SET tags = '$newSection' WHERE id = '$catID'";

	$result2 = mysqli_query(Database::$conn, $sql2);

	if (!$result2) {
		die('Could not query2:' . mysqli_error(Database::$conn));
		exit;
	}
}
