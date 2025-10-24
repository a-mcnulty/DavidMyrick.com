<?php

	session_start();
	include '../includes/connect.php';
	Database::initialize();

	$username = mysqli_real_escape_string(Database::$conn, $_POST['username']);
	$password = mysqli_real_escape_string(Database::$conn, $_POST['password']);

	$query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";

	$result=mysqli_query(Database::$conn,$query);
	
	if (!$result) {
			die('Could not query:' . mysqli_error(Database::$conn));
			exit;
	}
	
	$row = mysqli_fetch_array($result);
	$num = mysqli_num_rows($result);

	if ($num != 0) {

		setcookie("admin", "yes");
		setcookie("adminlevel", $row['level']);
		header("Location: main.php?admin=valid");

	} else {

		header("Location: index.php?status=login_failed_please_try_again");

	}

?>