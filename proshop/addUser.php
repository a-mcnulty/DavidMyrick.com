<?php

	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';

	if(isset($_POST["myContent"])){
		$data_json = $_POST["myContent"];

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$username = mysqli_real_escape_string(Database::$conn, $data_array["username"]);
        	$password = mysqli_real_escape_string(Database::$conn, $data_array["password"]);
			$email = mysqli_real_escape_string(Database::$conn, $data_array["email"]);
			$level = mysqli_real_escape_string(Database::$conn, $data_array["level"]);
    	}

	} else {
		echo "Error!";
	}

	if (!isset($username)) {
		echo "error creating user!";
		exit;
	}

	$query = "INSERT INTO admin (username, password, email, level) VALUES ('$username', '$password', '$email', '$level')";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
			die('Could not query:' . mysqli_error(Database::$conn));
			exit;
	}

	echo "manage_users.php?status=new_user_has_been_added";

?>