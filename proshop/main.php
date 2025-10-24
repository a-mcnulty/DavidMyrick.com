<?php

session_start();
include 'includes/secure.php';
include '../includes/connect.php';
include 'includes/functions.php';

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

	<div id="topMenu">
		<?php include('includes/topMenu.php'); ?>
	</div>

	<div id="leftCol">
		<div class="innerLoad">

			<?php include('includes/leftColNew.php'); ?>

		</div>
	</div>

	<div id="contentArea">

		<div id="pageContent">

			<span class="head">Admin Dashboard:</span><br><br>

			Welcome! Please click <b>"SITE CONTENT"</b> from the left-hand menu to begin editing your website.<br /><br />

			<div id="line" style="width: 90%; margin-bottom: 2px;"></div>

			<div class="notes" style="line-height: 24px; margin-top: 15px;">
				<b>* IMPORTANT REMINDERS</b><br />
				- All images should be saved as .JPG and have a max dimension of no more than 3000 pixels<br />
				- It is best to use PhotoShop "Save for Web" option to optimize and compress images for web loading<br />
				- To create a single line break in the text editor hold down SHIFT+ENTER<br /><br />

				<b>QUESTIONS?</b> Email us at <a href="mailto:info@eastofwestern.com">info@eastofwestern.com</a> or call us at 323.741.0648<br /><br />
			</div>

		</div>

	</div>

</BODY>

</HTML>