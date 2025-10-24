<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	$myText = getPageText($catID);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML>
<HEAD>

<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<link type="text/css" rel="stylesheet" href="css/styles.css">
<link type="text/css" media="screen" rel="stylesheet" href="css/colorbox.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
<!--<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>-->
<script type="text/javascript" src="js/jquery.colorbox.js"></script>

<script type="text/javascript" src="js/cufon-yui.js"></script>
<script type="text/javascript" src="js/Gotham_500-Gotham_700.font.js"></script>

<!--DRAG AND DROP CODE-->
<script type="text/javascript" src="js/jquery-ui-1.8.23.custom.min.js"></script>

<!--TINY MCE EDITOR-->
<script type="text/javascript" src="tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		plugins : "advimage,inupload,spellchecker,advhr,insertdatetime,preview,paste",
		content_css : "/css/styles_final.css",

		// Theme options - button# indicated the row# only
	theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,fontselect,fontsizeselect,formatselect,styleselect",
	theme_advanced_buttons2 : "cut,copy,paste,pasteword,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor,|,inupload,image,|,preview,|,forecolor,backcolor",
	theme_advanced_buttons3 : "insertdate,inserttime,|,spellchecker,advhr,,removeformat,|,sub,sup,|,charmap,code",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
  inupload_path: '/var/chroot/home/content/19/7065619/html/beta/images/pics/userimages/',
  inupload_sub_path:'/beta/images/pics/userimages/',
  inupload_min_width:'0',
  inupload_max_width:'0',
  document_base_url : '',
  relative_urls: true,
  remove_script_host : false
});
</script>

</HEAD>
<BODY>

<?php showStatus($_REQUEST['status']) ?>

<div id="contentArea">

	<span class="head">Text Editor</span><br />
	<b>Editor Tip</b> - To create a single line break hold down the SHIFT + ENTER Key.<br />
	<form name="textEditor" action="updateText.php" method="post">
	<input type="hidden" name="catID" value="<?=$catID?>">

	<textarea name="content" id="content" style="width: 800px; height: 400px;"><?=stripslashes($myText)?></textarea><br>

	<input type="submit" value="Update Page" id="btn"><br />

	</form>


</div>

<?php include 'includes/cufon.php' ?>

</BODY>
</HTML>