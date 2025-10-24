<?php

	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';

	$id = $_REQUEST['id'];
	$picID = $_REQUEST['picID'];
	$parentID = $_REQUEST['parentID'];
	$parentParentID = $_REQUEST['parentParentID'];
	$confirm = $_REQUEST['confirm'];
	$category = $_REQUEST['category'];
	$moduleName = $_REQUEST['moduleName'];
	$modType = $_REQUEST['modType'];

	if (isset($confirm) AND $confirm === "yes") {

		$query = "DELETE FROM modules WHERE id = '$id'";

		$result=mysqli_query(Database::$conn, $query);

		if ($modType === "category") {
			$rd="manage_modules.php?category=" . $category . "&parentID=" . $parentID . "&status=module_has_been_deleted";
		} else {
			$rd="detail.php?picID=" . $picID . "&category=" . $category . "&type=" . $type . "&status=module_has_been_deleted";
		}

		header("Location: " . $rd);
		exit;

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
		
			<span class="head" style="color: #3e4e69;">Delete Module: <?=$moduleName?></span><br /><br />
			
			Are you sure?<br /><br />
			
			<div style="margin-top: 20px;">
				<a href="#" onclick="parent.$.fn.colorbox.close();"><img src="images/cancelBtn.png"></a>
				<a href="delete_module.php?confirm=yes&id=<?=$id?>&category=<?=$category?>&moduleName=<?=$moduleName?>&picID=<?=$picID?>&parentID=<?=$parentID?>&parentParentID=<?=$parentParentID?>&modType=<?=$modType?>" target="_parent"><img src="images/yesBtn.png" style="margin-left: 12px;"></a>
			</div>
			
		</div>
		
	</div>

</body>
</html>

<?php

	}

?>