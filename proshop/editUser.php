<?php

	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';
	
	$userID = $_GET['id'];
	$user = getUser($userID);

?>

<script>

	// add new coupon
	$(".edit").click(function() {

		var theID = $("input#id").val();
		var username = $("input#username").val();
		var password = $("input#password").val();
		var email = $("input#email").val();
		var level = $("#level option:selected").text();

		var alertMsg = "The following REQUIRED fields\nhave been left empty:\n";
		if (username === "" || username === "Username") alertMsg += "\nUsername";
		if (password === "" || password === "Password") alertMsg += "\nPassword";
		if (alertMsg != "The following REQUIRED fields\nhave been left empty:\n") {
			alert(alertMsg);
			return false;
		} else {

			// handle ajax post here
			 var data = {"id" : theID, "username" : username, "password" : password, "email" : email, "level" : level};
			 var data_encoded = JSON.stringify(data);

			 $.ajax({
			   type: "POST",
			   url: "editUser2.php",
			   data: {
				 "myContent" : data_encoded
			   },
			   success: function(response) {

					window.location.href = response;

			   },
			   error: function(){
				 alert('failure');
			   }
			});
			return false;
		}

	});
	
	//Examples of how to assign the ColorBox event to elements
	$(".delete").colorbox({
		width:"60%",
		height:"60%",
		iframe:true});

</script>

<div class="infoPage">

	<div id="alertDivPic" style="float: right; margin: 9px 5px 0 0;"></div>
	<p style="float: left;"><span class="head">Update User:</span></p>

	<div id="breadcrumb">
		<a href="main.php">DASHBOARD</a> > <a href="#" id="manage_users.php" class="breadcrumb">MANAGE USERS</a> > <span class="pageOn">EDIT USER</span>
	</div>

	<div id="line" style="width: 90%; margin-bottom: 2px; clear: both;"></div>
	<div id="line" style="width: 90%;"></div><br />

	<form name="edit-user">
	<input type="hidden" name="id" id="id" value="<?=$userID?>">
	
	<input style="float: left; margin-right: 25px; height: 18px;" type="text" name="username" id="username" value="<?=$user['username']?>" onfocus="if (this.value == 'Username') this.value='';" onblur="if (this.value == '') this.value='Username';">
	<input style="float: left; margin-right: 25px; height: 18px;" type="password" name="password" id="password" value="<?=$user['password']?>" onfocus="if (this.value == 'Password') this.value='';" onblur="if (this.value == '') this.value='Password';">
	<input style="float: left; margin-right: 25px; height: 18px;" type="text" class="email" name="email" id="email" value="<?=$user['email']?>" onfocus="if (this.value == 'Email') this.value='';" onblur="if (this.value == '') this.value='Email';">
	<select name="level" id="level" style="float: left;">
		<option value="Admin" <?php if ($user['level'] === "Admin") { ?>selected<?php } ?>>Admin</option>
		<option value="Employee" <?php if ($user['level'] === "Employee") { ?>selected<?php } ?>>Employee</option>
	</select>
	<div style="clear: both; height: 15px;"></div>

	<button id="btn" class="edit" style="margin-bottom: 15px; float: left; margin-right: 15px;">Update User</button>
	<span style="float: left; position: relative; top: 3px; left: 5px;"><a href="delete_user.php?id=<?=$userID?>&username=<?=$user['username']?>" class="delete">Delete User</a></span>

	<div style="clear: both;"></div>

	</form>
	
</div>