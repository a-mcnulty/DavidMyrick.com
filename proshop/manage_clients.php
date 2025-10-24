<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';
	
	$sql = "SELECT * FROM cat_list WHERE id = '$catID'";

	$result = mysql_query($sql);

	if (!$result) {
			die('Could not query:' . mysql_error());
			exit;
	}

	$row = mysql_fetch_array($result);

?>

<script type="text/javascript">

// HANDLE FORM SUBMISSION
$(".update").click(function() {

	var catID = $("input#catID").val();
	var parentID = $("input#parentID").val();
	var username = $("input#username").val();
	var password = $("input#password").val();
	var subName = $("input#subName").val();
	
	var data = {"catID" : catID, "parentID" : parentID, "username" : username, "password" : password, "subName" : subName};
	var data_encoded = JSON.stringify(data);
	 
	 $.ajax({
	   type: "POST",
	   url: "updateClient.php",
	   data: {
		 "myContent" : data_encoded
	   },
	   success: function(response) {
		 loadPage(response);
	   },
	   error: function(){
		 alert('failure');
	   }
	});
	return false;
	
});

$(document).ready(function() {

	$("A.ajax").click(function() {

		var thePage = $(this).attr("id");

		loadPage(thePage);

	});

	//Examples of how to assign the ColorBox event to elements
	$(".delete").colorbox({
		width:"60%",
		height:"60%",
		iframe:true});

});
</script>

<p><span class="head uploaderLink">Manage Client</span></p>

<div id="line" style="width: width: 100%; margin-bottom: 2px;"></div>
<div id="line" style="width: width: 100%;"></div><br />

<form name="client" action="updateClient.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="catID" id="catID" value="<?=$catID?>">
<input type="hidden" name="parentID" id="parentID" value="<?=$parentID?>">
<input type="hidden" name="subName" id="subName" value="<?=stripslashes($row['subName'])?>">

<br />Client Name: <b><?=stripslashes($row['subName'])?></b><br /><br />

Username:<br />
<input type="text" name="username" id="username" style="width: 250px;" value="<?=$row['username']?>"><br /><br />

Password:<br />
<input type="text" name="password" id="password" style="width: 250px;" value="<?=$row['password']?>"><br /><br />

Add/Update Client Logo:<br />
<input type="file" name="navOFF">

<br /><br /><input type="submit" value="Update Client Account" id="btn" class="update"><br />

</form>