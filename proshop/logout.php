<?php

	session_start();
	//$_SESSION['admin'] = "";
	setcookie("admin", "");
	session_destroy();
	header("Location: index.php");

?>