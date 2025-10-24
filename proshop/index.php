<?php

	session_start();
	include('../includes/connect.php');
	include('includes/functions.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML>
<HEAD>

<?php include 'includes/pageHead.php'; ?>

<script>
$(document).ready(function() {

	$('input[type=text]:.login_password').focus(function() {
		$(this).replaceWith('<input type="password" class="login_password" name="password" value="" />');
		$("input[name=password]").focus();
	});

});
</script>

</head>
<body>

<div id="wrapper" style="background-color: #1c1c1c; min-height: 575px;">
	
	<div id="verticalCentered">
	
		<div id="login">
			
			<?php if (getOption("adminLogo") != "") { ?>
				<img src="<?php echo getOption("adminLogo"); ?>" class="logo" style="max-height: 175px;"><br />
			<?php } else { ?>
				<span class="head" style="color: #fff; font-size: 24px;"><?php echo getOption("siteName"); ?></span><br />
			<?php } ?>
			
			<form name="login" action="verify.php" method="post">
				
				<input type="text" value="Username" name="username" onfocus="if (this.value == 'Username') this.value='';" onblur="if (this.value == '') this.value='Username';" />
				
				<input type="text" class="login_password" name="password" value="Password" onfocus="if (this.value == 'Password') this.value='';" onblur="if (this.value == '') this.value='Password';" />
				
				<input class="submit" type="submit" value="LOG IN" />
				
				<?php /*
				<span id="forgotPass">Aww, forgot your password?</span>
				*/ ?>
				
				<div style="clear: both;"></div>
				
			</form>
			
		</div>
	
	</div>
	
	<div id="footer">
		<p>Thoughtfully powered by <a href="http://www.eastofwestern.com" target="_blank">EAST OF WESTERN</a></p>
	</div>  
	
</div>

</BODY>
</HTML>