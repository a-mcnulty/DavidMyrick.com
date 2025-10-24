<?php

	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';

	$theCat = catDetails($catID);
	if (isset($_GET['parentID'])) {
		$parentID = $_GET['parentID'];
	}
	$theParent = catDetails($parentID);
	if (isset($_GET['parentParentID'])) {
		$parentParentID = $_GET['parentParentID'];
	}
	if ($theCat['name'] != "") {
		$pageName = $theCat['name'];
	} else {
		$pageName = $theCat['subName'];
	}

	$myLink = catDetails($catID);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML>
<HEAD>

<?php include 'includes/pageHead.php'; ?>

</HEAD>
<BODY>

<?php 

	if (isset($_REQUEST['status'])) {
		echo showStatus($_REQUEST['status']);
	}
	
?>

<div id="topMenu"></div>

<div id="leftCol">
	<div class="innerLoad">
	
		<?php include('includes/leftColNew.php'); ?>
	
	</div>
</div>

<div id="contentArea">

	<div id="pageContent">

		<div class="imagePage">

			<div id="alertDivPic" style="float: right; margin: 9px 5px 0 0;"></div>
			<p style="float: left;"><span class="head"><?=$pageName?></span></p>

			<div id="breadcrumb">
				<a href="main.php">DASHBOARD</a> > <span class="pageOn"><?=$pageName?></span>
			</div>

			<div id="line" style="width: 99%; margin-bottom: 2px; clear: both;"></div>
			<div id="line" style="width: 99%; margin-bottom: 25px;"></div><br />

			<form name="textEditor" action="updateLink.php" method="post">
			<input type="hidden" name="catID" id="catID" value="<?=$catID?>">

			<input type="text" name="linkURL" id="linkURL" style="width: 250px;" value="<?=$myLink['link']?>"><br /><br />
			
			<select name="linkTarget" id="linkTarget" style="width: 250px;">
				<option value="_blank" <?php if ($myLink['target'] === "_blank") { ?>selected<?php } ?>>Open in New Window</option>
				<option value="_parent" <?php if ($myLink['target'] === "_parent") { ?>selected<?php } ?>>Open in Same Window</option>
			</select><br /><br />

			<button id="btn" class="update">Update Link</button><br>

			<div class="theResponse" style="margin-top: 10px;"></div>

			</form>


		</div>
		
	</div>
	
</div>

</BODY>
</HTML>

<script type="text/javascript">
$(document).ready(function() {
	
	$("#contentTopMenu").css('display', 'block');
	$("#catList").css('display', 'block');

	// HANDLE FORM SUBMISSION
	$(".update").click(function() {

		var catID = $("input#catID").val();
		var linkURL = $("input#linkURL").val();
		var linkTarget = $("#linkTarget").val();

		var data = {"catID" : catID, "linkURL" : linkURL, "linkTarget" : linkTarget};
		var data_encoded = JSON.stringify(data);

		$.ajax({
		  type: "POST",
		  url: "updateLink.php",
		  data: {
		  	"myContent" : data_encoded
		  },
		  success: function(response) {
			$('.theResponse').html(response);
		  },
		  error: function(){
		   	alert('failure');
  		  }
		});

		return false;

	});

});
</script>