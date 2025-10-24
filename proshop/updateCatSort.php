<?php

	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';
	
	$catID = mysqli_real_escape_string(Database::$conn, $_POST['currentcat']);
	$parentID = mysqli_real_escape_string(Database::$conn, $_POST['parentID']);
	$parentParentID = mysqli_real_escape_string(Database::$conn, $_POST['parentParentID']);
	$thesort = mysqli_real_escape_string(Database::$conn, $_POST['thesort']);
	
	$sql = "UPDATE cat_list SET sorter = '$thesort' WHERE id = '$catID'";
	
	$result = mysqli_query(Database::$conn, $sql);
	
	header("Location: manage_images.php?category=" . $catID . "&parentID=" . $parentID . "&parentParentID=" . $parentParentID);

?>