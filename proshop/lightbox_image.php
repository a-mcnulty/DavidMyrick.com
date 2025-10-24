<?php

	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	$img = $_REQUEST['img'];
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML>
<HEAD>
<TITLE>East of Western</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<link type="text/css" rel="stylesheet" href="css/styles.css">
<script type="text/javascript" src="js/cufon-yui.js"></script>
<script type="text/javascript" src="js/Gotham_500-Gotham_700.font.js"></script>

</head>

<body class="colorBox">

<div class="lbHolder">

	<div class="lbPad">
		<span class="head" style="color: #3e4e69;">Image Preview:</span><br /><br />
		
		<div style="display: inline-block; width: 87%;">
			<img src="<?=getOption("imagePathFront")?>siteThumbs/<?=$img?>" style="height: 400px;">
		</div>

		<div style="display: inline-block; margin-top: 20px; width: 9%;">
			<a href="#" onclick="parent.$.fn.colorbox.close();"><img src="images/closeBtn.png"></a>	
		</div>
	</div>
	
</div>

<?php include 'includes/cufon.php' ?>

</body>
</html>