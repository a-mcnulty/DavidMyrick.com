<?php

	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';

	if(isset($_POST["myContent"])){
		$data_json = $_POST["myContent"];

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$id = mysqli_real_escape_string(Database::$conn, $data_array["id"]);
			$username = mysqli_real_escape_string(Database::$conn, $data_array["username"]);
        	$password = mysqli_real_escape_string(Database::$conn, $data_array["password"]);
			$email = mysqli_real_escape_string(Database::$conn, $data_array["email"]);
			$level = mysqli_real_escape_string(Database::$conn, $data_array["level"]);
    	}

	} else {
		echo "Error!";
	}

	if (!isset($id)) {
		echo "error editing user!";
		exit;
	}

	$query = "UPDATE admin SET username = '$username', password = '$password', email = '$email', level = '$level' WHERE id = '$id'";

	//echo $query;

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
			die('Could not query:' . mysqli_error(Database::$conn));
			exit;
	}
	
	echo "manage_users.php?status=user_has_been_updated";

?>