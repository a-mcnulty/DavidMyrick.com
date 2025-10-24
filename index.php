<?php

session_start();
include('includes/connect.php');
include('includes/functions.php');

$catID = 717;
$catDetails = catDetails($catID);
$images = getRandomImageResult($catID, $catDetails['sorter']);
$firstImage = getRandomImage($catID, $catDetails['sorter']);

$slug = $catDetails['slug'];

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
	<meta property="og:image" name="og:image" content="<?= getOption("url") ?><?= getOption("imagePathFront") ?><?= $firstImage['img'] ?>" />
	<meta property="og:image:width" content="<?= $firstImage['width'] ?>" />
	<meta property="og:image:height" content="<?= $firstImage['height'] ?>" />
	<meta property="og:url" name="og:url" content="<?= getOption("url") ?>" />
	<meta property="og:site_name" name="og:site_name" content="<?= getOption("company") ?>" />

	<?php include('includes/topScripts.php'); ?>

</head>

<body class="preload introOn homepage no_footer dark_bg">

	<?php include('includes/header.php'); ?>

	<div id="intro">
		<div class="text">
			<h2>DIRECTOR OF PHOTOGRAPHY</h2>
			<h1>DAVID J. MYRICK</h1>
		</div>
	</div>

	<main>

		<section id="content">

			<article class="inner home">

				<div class="divider_line"></div>

				<div class="video-panels">

					<?php for ($count = 1; $item = mysqli_fetch_array($images); ++$count) { ?>

						<?php
						$ext = pathinfo(getOption("physicalPath") . "images/pics/" . $item['img'], PATHINFO_EXTENSION);

						$itemVideo = getImageVideo($item['id']);
						$hasVideo = false;
						if ($itemVideo['url_loop_desktop'] != "" or $itemVideo['hoverFile'] != "") {
							$hasVideo = true;
							$videoStr = '<video playsinline muted loop preload="auto"><source src="/videos/' . $itemVideo['hoverFile'] . '" /></video>';
							if ($itemVideo['url_loop_desktop'] != "") {
								$videoStr = '<video playsinline muted loop preload="auto" class="sizeload" data-hd="' . $itemVideo['url_loop_desktop'] . '" data-sd="' . $itemVideo['url_loop_mobile'] . '"><source src="" /></video>';
							}
						}

						?>

						<figure class="cell <?php if ($count == 1) { ?>active<?php } ?> <?php if ($hasVideo) { ?>videoloop<?php } ?>" data-timer="<?= $itemVideo['video_time'] ?>" data-id="<?= $item['id'] ?>" <?php if ($itemVideo['hoverFile'] != "") { ?> data-video='<?= $videoStr ?>' <?php } ?>>
							<?php if ($hasVideo) { ?>
								<div class="vidhold">
									<?php if ($count <= 2) { ?>
										<?= $videoStr ?>
									<?php } ?>
								</div>
							<?php } else { ?>
								<img src="<?= $loaderImg ?>" data-img="<?= $item['img'] ?>" class="loadmeview photo <?= $ext ?>" alt="" />
							<?php } ?>

							<div class="info">
								<a href="#<?= $item['slug'] ?>" class="openOverlay" data-id="<?= $item['id'] ?>">
									<?php if ($item['title'] != "title:" and $item['title'] != "") { ?>
										<h2><?= $item['title'] ?></h2>
									<?php } ?>
									<?php if ($item['caption'] != "caption:") { ?>
										<h3><?= $item['caption'] ?></h3>
									<?php } ?>
								</a>
							</div>

						</figure>

					<?php } ?>

				</div>

			</article>

		</section>

	</main>

	<?php include('includes/footer.php'); ?>
	<?php include('includes/overlays.php'); ?>
	<?php include('includes/scripts.php'); ?>

</body>

</html>