<?php

	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';

	$catID = $_GET['catID'];
	$users = getUsers();
	
	function get_starred($str){
		$len = strlen($str);
		return substr($str, 0,1). str_repeat('*',$len - 2) . substr($str, $len - 1 ,1);
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML>
<HEAD>

<?php include 'includes/pageHead.php'; ?>

</HEAD>
<BODY>

<?php showStatus($_REQUEST['status']) ?>

<div id="topMenu"></div>

<div id="leftCol">
	<div class="innerLoad">
	
		<?php include('includes/leftColNew.php'); ?>
	
	</div>
</div>

<div id="contentArea">

	<div id="pageContent">

		<div class="infoPage">

				<div id="alertDivPic" style="float: right; margin: 9px 5px 0 0;"></div>
				<p style="float: left;"><span class="head">Manage Users:</span></p>
				<?php if ($_COOKIE['adminlevel'] === "Admin") { ?>
					<div style="float: left; padding-left: 10px; padding-top: 10px;">
						<a href="#" class="addLink">Add New</a>
					</div>
				<?php } ?>

				<div id="breadcrumb">
					<a href="main.php">DASHBOARD</a> > <span class="pageOn">MANAGE USERS</span>
				</div>

				<div id="line" style="width: 90%; margin-bottom: 2px; clear: both;"></div>
				<div id="line" style="width: 90%;"></div><br />

				<div id="editCoupon" style="display: none;">



				</div>
				
				<div id="addUser" style="display: none;">

					<form name="user">

						<input style="float: left; margin-right: 25px; height: 18px;" type="text" name="username" id="username" value="Username" onfocus="if (this.value == 'Username') this.value='';" onblur="if (this.value == '') this.value='Username';">
						<input style="float: left; margin-right: 25px; height: 18px;" type="text" class="password" name="password" id="password" value="Password" onfocus="if (this.value == 'Password') this.value='';" onblur="if (this.value == '') this.value='Password';">
						<input style="float: left; margin-right: 25px; height: 18px;" type="text" class="email" name="email" id="email" value="Email" onfocus="if (this.value == 'Email') this.value='';" onblur="if (this.value == '') this.value='Email';">
						<select name="level" id="level" style="float: left;">
							<option value="Admin">Admin</option>
							<option value="Employee">Employee</option>
						</select>
						<div style="clear: both; height: 15px;"></div>

						<button id="btn" class="add" style="margin-bottom: 15px; float: left; margin-right: 15px;">Add User</button>

						<div style="clear: both;"></div>

					</form>

				</div>

				<div id="manageOrders" style="margin-top: 20px; margin-bottom: 20px;">

					<table border="0" cellpadding="0" cellspacing="0" style="width: 90%;">
						<tr>
							<td align="left" valign="top" class="dataHeader cufon">
								Username
							</td>
							<td align="left" valign="top" class="dataHeader cufon">
								Password
							</td>
							<td align="left" valign="top" class="dataHeader cufon">
								Level
							</td>
						</tr>

						<?php for ($count = 1; $user = mysqli_fetch_array($users); ++$count) { ?>

							<?php

								if ($count % 2) {
									$class = "oddRow";
								} else {
									$class = "evenRow";
								}

							?>

							<tr>
								<td align="left" valign="top" class="<?=$class?> cufon">
									<?php if ($_COOKIE['adminlevel'] === "Admin") { ?>
										<a href="#" id="editUser.php?id=<?=$user['id']?>" class="breadcrumb"><?php echo $user['username']; ?></a>
									<?php } else { ?>
										<?php echo $user['username']; ?>
									<?php } ?>
								</td>
								<td align="left" valign="top" class="<?=$class?> cufon">
									<?php if ($_COOKIE['adminlevel'] === "Admin") { ?>
										<a href="#" id="editUser.php?id=<?=$user['id']?>" class="breadcrumb"><?php echo get_starred($user['password']); ?></a>
									<?php } else { ?>
										<?php echo get_starred($user['password']); ?>
									<?php  } ?>
								</td>
								<td align="left" valign="top" class="<?=$class?> cufon">
									<?php echo $user['level']; ?>
								</td>
							</tr>

						<?php } ?>

					</table>

				</div>

			</div>

		</div>
		
	</div>
		
</div>

</body>
</html>

<script type="text/javascript">

$(document).ready(function(){

	$('.password').focus(function() {
		$(this).replaceWith('<input type="password" id="password" class="password" name="Password" value="" style="float: left; margin-right: 25px; height: 18px;" />');
		$("input[name=Password]").focus();
	});

	// add new coupon
	$(".add").click(function() {

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
			 var data = {"username" : username, "password" : password, "email" : email, "level" : level};
			 var data_encoded = JSON.stringify(data);

			 $.ajax({
			   type: "POST",
			   url: "addUser.php",
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

	$(".addLink").click(function() {

		$("#addUser").slideToggle();

	});

});
</script>