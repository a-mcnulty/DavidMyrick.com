<?php

	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';

	$catID = mysqli_real_escape_string(Database::$conn, $_POST["catID"]);
	$picID = mysqli_real_escape_string(Database::$conn, $_POST["picID"]);
	$title = mysqli_real_escape_string(Database::$conn, $_POST["title"]);
	$pageType = mysqli_real_escape_string(Database::$conn, $_POST["pageType"]);

	if (!isset($picID) OR !isset($catID)) {
		echo "error";
		exit;
	}

	$sql1 = "INSERT INTO modules (picid, catid, title, pageType, status, sortBy) VALUES ('$picID', '$catID', '$title', '$pageType', 'preview', '0')";

	$result1 = mysqli_query(Database::$conn, $sql1);

	$modID = mysqli_insert_id(Database::$conn);
	
	echo $modID;

?>