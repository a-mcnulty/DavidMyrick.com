<?php

	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	$id = $_REQUEST['id'];
	$confirm = $_REQUEST['confirm'];
	$category = $_REQUEST['category'];
	$caption = $_REQUEST['caption'];
	$vidFile = $_REQUEST['vidFile'];

	if ($confirm === "yes") {

		$query = "DELETE FROM videos WHERE id = '$id'";

		$result=mysql_query($query);

		if ($vidFile != "") {

			//SHOULD REMOVE THE VIDEO FROM THE SERVER AS WELL
			unlink(getOption("videoPath").$vidFile);

		}

		$rd = "manage_video.php?status=video_has_been_deleted&category=".$category;

		//header("Location: manage_video.php?status=video_has_been_deleted&category=".$category);

?>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
	<script>

		parent.$("body").animate({scrollTop : 0},'slow');

		parent.$('#pageContent').html('<div style="text-align: center; width: 100%; margin-top: 25%; color: #ccc;"><img src="images/ajax-loader.gif"><p>Loading...</p></div>');

		parent.$.ajax({
		  url: "<?=$rd?>",
		  success: function(data) {
			parent.$('#pageContent').html(data);
			parent.Cufon.refresh();
			parent.$.fn.colorbox.close();
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

<span class="head">You Are About to Permanently Delete a Video</span><br>
Please confirm that you would like to delete the video "<?=$caption?>" below<br><br>

<a href="delete_video.php?confirm=yes&id=<?=$id?>&category=<?=$category?>&vidFile=<?=$vidFile?>">Click Here</a> to <b>delete this video.</b><br><br>

<a href="#" onclick="parent.$.fn.colorbox.close();">Click Here</a> to <b>go back</b> without making any changes.<br><br>

</body>
</html>

<?php

	}

?>