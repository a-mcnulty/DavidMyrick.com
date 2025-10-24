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
        	$content = mysqli_real_escape_string(Database::$conn, $data_array["content"]);
			$content2 = mysqli_real_escape_string(Database::$conn, $data_array["content2"]);
    	}

	} else {
		echo "Error!";
	}

	if (!isset($catID) OR !isset($content)) {
		echo "error updating page!";
		exit;
	}

	//CHECK IF CONTENT PAGE EXISTS
	$query1 = "SELECT * FROM content WHERE catid = '$catID'";

	$result1 = mysqli_query(Database::$conn, $query1);

	if (!$result1) {
			die('Could not query:' . mysql_error(Database::$conn));
			exit;
	}

	if (mysqli_num_rows($result1) == 0) {

		$query = "INSERT INTO content (catid, content, content2) VALUES ('$catID', '$content', '$content2')";

		$result = mysqli_query(Database::$conn, $query);

		if (!$result) {
				die('Could not query:' . mysql_error(Database::$conn));
				exit;
		}

	} else {

		$query = "UPDATE content SET content = '$content', content2 = '$content2' WHERE catid = '$catID'";

		$result = mysqli_query(Database::$conn, $query);

		if (!$result) {
				die('Could not query:' . mysql_error());
				exit;
		}

	}

	echo "Page has been updated!";

?>