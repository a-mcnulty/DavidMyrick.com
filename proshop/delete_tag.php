<?php
	
	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';

	$id = $_REQUEST['id'];
	$confirm = $_REQUEST['confirm'];
	$tag = $_REQUEST['tag'];

	if ($confirm === "yes") {

		$query = "DELETE FROM tags WHERE id = '$id'";

		$result=mysqli_query(Database::$conn, $query);

		$rd="manage_tags.php";
		
		header("Location: " . $rd);
		exit;

?>

<?php

	} else {

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML>
<HEAD>
<TITLE>East of Western</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<link type="text/css" rel="stylesheet" href="css/styles.css">
</head>

<body class="colorBox">

	<div class="lbHolder">

		<div class="lbPad">
			<span class="head" style="color: #3e4e69;">Delete Tag:</span><br /><br />

			Are you sure?<br /><br />

			<div style="margin-top: 20px;">
				<a href="#" onclick="parent.$.fn.colorbox.close();"><img src="images/cancelBtn.png"></a>
				<a href="delete_tag.php?confirm=yes&id=<?=$id?>" target="_parent"><img src="images/yesBtn.png" style="margin-left: 12px;"></a>			
			</div>
		</div>
		
	</div>

</body>
</html>

<?php

	}

?>