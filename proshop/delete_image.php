<?php

session_start();
include 'includes/secure.php';
include '../includes/connect.php';
include 'includes/functions.php';

$type = "";
$modID = "";
$confirm = "";

$id = $_REQUEST['id'];
if (isset($_REQUEST['modid'])) {
	$parentID = $_REQUEST['parentID'];
}
if (isset($_REQUEST['parentParentID'])) {
	$parentParentID = $_REQUEST['parentParentID'];
}
if (isset($_REQUEST['confirm'])) {
	$confirm = $_REQUEST['confirm'];
}
if (isset($_REQUEST['category'])) {
	$category = $_REQUEST['category'];
}
if (isset($_REQUEST['imgName'])) {
	$imgName = $_REQUEST['imgName'];
}
if (isset($_REQUEST['type'])) {
	$type = $_REQUEST['type'];
}
if (isset($_REQUEST['modID'])) {
	$modID = $_REQUEST['modID'];
}
if (isset($_REQUEST['modType'])) {
	$modType = $_REQUEST['modType'];
}

if ($confirm === "yes") {

	if ($modID != "") {

		// DELETING AN ALT IMAGE

		$query = "DELETE FROM module_pics WHERE modid = '$modID' AND picid = '$id'";

		$result = mysqli_query(Database::$conn, $query);
	} else {

		// NEW LOGIC IS NOT TO DELETE IMAGE, JUST REMOVE FROM THE CAT_PICS TABLE FOR SELECTED CATEGORY

		$query = "DELETE FROM cat_pics WHERE picid = '$id' AND catid = '$category'";

		$result = mysqli_query(Database::$conn, $query);
	}



	/*
		
		$query = "DELETE FROM pics WHERE id = '$id'";

		$result=mysql_query($query);

		//SHOULD REMOVE THE IMAGE FROM THE SERVER AS WELL
		unlink(getOption("imagePath").$imgName);

		//header("Location: manage_images.php?status=image_has_been_deleted&category=".$category);
		
		*/

	if ($modID > 0) {

		if ($modType === "category") {

			$rd = "manage_modules.php?category=" . $category . "&parentID=" . $parentID . "&parentParentID=" . $parentParentID . "&imgName=" . $imgName . "&status=image_has_been_deleted&modedit=recordsArray_" . $modID;
		} else {

			$rd = "detail.php?picID=" . $parentID . "&type=" . $type . "&category=" . $category . "&imgName=" . $imgName . "&status=image_has_been_deleted&modedit=recordsArray_" . $modID;
		}

		header("Location: " . $rd);
		exit;
	} else {

		if ($type === "product") {

			header("Location: manage_products.php?status=image_has_been_deleted&catID=" . $category);
		} else {

			header("Location: manage_images.php?status=image_has_been_deleted&category=" . $category . "&parentID=" . $parentID . "&parentParentID=" . $parentParentID);
		}

		exit;
	}

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
				<span class="head" style="color: #3e4e69;">Delete Item(s):</span><br /><br />

				Are you sure?<br /><br />

				<div style="margin-top: 20px;">
					<a href="#" onclick="parent.$.fn.colorbox.close();"><img src="images/cancelBtn.png"></a>
					<a href="delete_image.php?confirm=yes&id=<?= $id ?>&category=<?= $category ?>&imgName=<?= $imgName ?>&type=<?= $type ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>&modID=<?= $modID ?>&modType=<?= $modType ?>" target="_parent"><img src="images/yesBtn.png" style="margin-left: 12px;"></a>
				</div>
			</div>

		</div>

	</body>

	</html>

<?php

}

?>