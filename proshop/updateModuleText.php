<?php

session_start();
include 'includes/secure.php';
include '../includes/connect.php';
include 'includes/functions.php';

if (isset($_POST["myContent"])) {
	$data_json = $_POST["myContent"];

	$data_array = json_decode($data_json, true);

	if ($data_array !== null) {
		$moduleID = $data_array["moduleID"];
		$picID = $data_array["picID"];
		$catID = $data_array["catID"];
		$content = mysqli_real_escape_string(Database::$conn, $data_array["content"]);
		$moduleName = mysqli_real_escape_string(Database::$conn, $data_array["moduleName"]);
		$pageType = mysqli_real_escape_string(Database::$conn, $data_array["pageType"]);
		$moduleStatus = mysqli_real_escape_string(Database::$conn, $data_array["moduleStatus"]);
		$moduleShowTitle = mysqli_real_escape_string(Database::$conn, $data_array["moduleShowTitle"]);
	}
} else {
	echo "Error!";
}

if (!isset($moduleID)) {
	echo "error updating page!";
	exit;
}

$query2 = "UPDATE modules SET content = '$content', title = '$moduleName', showTitle = '$moduleShowTitle', pageType = '$pageType', status = '$moduleStatus' WHERE id = '$moduleID'";

$result2 = mysqli_query(Database::$conn, $query2);

if (!$result2) {
	die('Could not query:' . mysqli_error(Database::$conn));
	exit;
}

echo $moduleID;
