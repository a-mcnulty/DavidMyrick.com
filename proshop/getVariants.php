<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';
	
	$id = $_GET['id'];
	
	$variants = getOptions($id, "result");

?>

<?php for ($count = 1; $variant = mysql_fetch_array($variants); ++$count) { ?>

	<input type="checkbox" name="variant" class="variant" value="<?=$variant['id']?>"> <?=strtolower($variant['title'])?>&nbsp;

<?php } ?>