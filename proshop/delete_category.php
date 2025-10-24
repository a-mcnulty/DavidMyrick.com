<?php

	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	$id = $_REQUEST['id'];
	$confirm = $_REQUEST['confirm'];
	$category = $_REQUEST['category'];
	$pageName = $_REQUEST['pageName'];

	if ($confirm === "yes") {

		$query = "DELETE FROM cat_list WHERE id = '$id'";

		$result=mysql_query($query);

		//header("Location: manage_images.php?status=image_has_been_deleted&category=".$category);

?>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
	<script>

		parent.$("body").animate({scrollTop : 0},'slow');

		parent.$.ajax({
		  url: "manage_categories.php",
		  success: function(data) {
			parent.$('#pageContent').html(data);
			parent.$('#topMenu').load("includes/topMenu.php?showTop=store&showSub=products", function() {
  				parent.Cufon.refresh();
			});
			parent.$.fn.colorbox.close();
			parent.Cufon.refresh();
		  }
		});

	</script>

<?php

	} else {

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML>
<HEAD>
<TITLE>East of Western</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<link type="text/css" rel="stylesheet" href="css/styles.css">
</head>

<body class="colorBox">

<span class="head">You Are About to Permanently Delete a Category from the Website</span><br>
Please confirm that you would like to delete the category <b><?=$pageName?></b> below<br><br>

<a href="delete_category.php?confirm=yes&id=<?=$id?>&category=<?=$category?>&pageName=<?=$pageName?>">Click Here</a> to <b>delete this category.</b><br><br>

<a href="#" onclick="parent.$.fn.colorbox.close();">Click Here</a> to <b>go back</b> without making any changes.<br><br>

</body>
</html>

<?php

	}

?>