<?php

	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	$id = $_REQUEST['id'];
	$confirm = $_REQUEST['confirm'];
	$codeName = $_REQUEST['codeName'];

	if ($confirm === "yes") {

		$query = "DELETE FROM ec_coupons WHERE id = '$id'";

		$result=mysql_query($query);

?>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
	<script>

		parent.$("body").animate({scrollTop : 0},'slow');

		parent.loadPage("manage_coupons.php");
		
		parent.$.ajax({
		  url: "includes/leftCol.php?showMenu=yes&showSub=<?=$id?>",
		  success: function(data) {
			parent.$('#leftCol').html(data);
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

<span class="head">You Are About to Permanently Delete a Discount Code from the Website</span><br>
Please confirm that you would like to delete the code <b><?=$codeName?></b> below<br><br>

<a href="delete_coupon.php?confirm=yes&id=<?=$id?>&codeName=<?=$codeName?>">Click Here</a> to <b>delete this coupon code.</b><br><br>

<a href="#" onclick="parent.$.fn.colorbox.close();">Click Here</a> to <b>go back</b> without making any changes.<br><br>

</body>
</html>

<?php

	}

?>