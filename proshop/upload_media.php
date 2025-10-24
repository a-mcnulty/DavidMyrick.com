<?php

	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';
	include 'classes/resize-class.php';
	
	// setup global variables for upload
	$save_path = "../downloads/";

	// retrieve file and headers
	$file = file_get_contents('php://input');
	$headers = apache_request_headers();
	$imgName = $headers['X-File-Name'];
    
    $filename = $imgName;

	$fh = fopen($save_path . $filename, 'w') or die("can't open file");
	fwrite($fh, $file);
	fclose($fh);
	
	// save to database - need to check for module id
	$query = "INSERT INTO media (filename) VALUES ('$filename')";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
			die('Could not query:' . mysqli_error(Database::$conn));
			exit;
	}

?>