<?php

$catID = getCatID($slug);
$catDetails = catDetails($catID);
$pageText = getPageTextID($catID);

$bgId = getFirstSubID($catID, "preview");
$bg = getFirstImage($bgId);
$hasBgVideo = false;
$bgVideo = getImageVideo($bg['id']);
if ($bgVideo['hoverFile'] != "") {
	$hasBgVideo = true;
}

$metaTitle = getOption("company") . " | " . ucwords(str_replace("-", " ", $slug));

?>

<!DOCTYPE html>

<html lang="en">

<head>

	<?php if ($catDetails['metaTitle'] != "") { ?>
		<title><?= $catDetails['metaTitle'] ?></title>
		<meta property="og:title" name="og:title" content="<?= $catDetails['metaTitle'] ?>" />
	<?php } else { ?>
		<title><?= $metaTitle ?></title>
		<meta property="og:title" name="og:title" content="<?= $metaTitle ?>" />
	<?php } ?>
	<meta name="description" content="<?= $catDetails['metaDesc'] ?>" />
	<meta name="keywords" content="<?= $catDetails['metaKeywords'] ?>" />

	<meta property="og:description" name="og:description" content="<?= $catDetails['metaDesc'] ?>" />
	<meta property="og:url" name="og:url" content="<?= getOption("url") ?><?= $slug ?>" />
	<meta property="og:site_name" name="og:site_name" content="<?= getOption("company") ?>" />

	<?php include('includes/topScripts.php'); ?>

</head>

<body class="no_footer fullbleed light_header">

	<?php include('includes/header.php'); ?>

	<main>

		<section id="content">

			<?php include("ajax/textpage.php"); ?>

		</section>

	</main>

	<?php include('includes/footer.php'); ?>
	<?php include('includes/overlays.php'); ?>
	<?php include('includes/scripts.php'); ?>

</body>

</html>