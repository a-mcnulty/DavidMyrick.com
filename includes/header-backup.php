<?php

$cats = catList();
$logoMark = getFirstImage(719);

?>

<a href="/" class="logo">
	<?php if ($logoMark) { ?>
		<img src="<?= getOption("imagePathFront") ?><?= $logoMark['img'] ?>" class="photo gif" alt="<?= getOption("company") ?>" />
	<?php } else { ?>
		MYRICK
	<?php } ?>
</a>

<div class="menuBtn">
	<div class="ib v-middle">
		<span class="line"></span>
		<span class="line"></span>
		<span class="line"></span>
	</div>
</div>

<header>

	<nav class="takeover">

		<ul>

			<?php for ($count = 1; $cat = mysqli_fetch_array($cats); ++$count) { ?>

				<?php

				if ($cat['pageType'] === "link") {
					$theLink = $cat['link'];
					$theTarget = $cat['target'];
				} else {
					$theLink = "/" . $cat['slug'];
					$theTarget = "_parent";
				}

				$hasSubs = false;
				$parentClass = "";
				$linkClass = "ajax";

				if ($cat['pageType'] === "link") {
					$linkClass = "";
				}

				$catSubs = subcatList($cat['id']);
				if (mysqli_num_rows($catSubs) > 0) {
					$hasSubs = true;
					$firstSubId = getFirstSubID($cat['id']);
					$firstSub = catDetails($firstSubId);
					if ($firstSub['pageType'] === "link") {
						$theLink = $firstSub['link'];
						$theTarget = $firstSub['target'];
					} else {
						$theLink = "/" . $firstSub['slug'];
						$theTarget = "_parent";
					}

					if ($firstSub['pageType'] === "link") {
						$linkClass = "";
					} else {
						$linkClass = "ajax";
					}
				}

				if ($cat['id'] == 717) {
					$theLink = "/";
				}

				?>

				<li class="<?= $parentClass ?>">
					<a href="<?= $theLink ?>" data-id="<?= $cat['id'] ?>" target="<?= $theTarget ?>" class="<?= $linkClass ?> <?php if ($catID == $cat['id'] or $parentID == $cat['id']) { ?>pageOn<?php } ?>">
						<span><?= $cat['name'] ?></span>
					</a>
				</li>

			<?php } ?>

		</ul>

		<div class="background">
			<?php mysqli_data_seek($cats, 0); ?>
			<?php for ($count = 1; $cat = mysqli_fetch_array($cats); ++$count) { ?>
				<?php

				// check for subpages, if found, get first item in first sub
				$catSubs = subcatListAll($cat['id']);
				$theCatId = $cat['id'];
				$theSorter = $cat['sorter'];
				if (mysqli_num_rows($catSubs) > 0) {
					$firstSubId = getFirstSubIDAll($cat['id']);
					$firstSub = catDetails($firstSubId);
					$theCatId = $firstSubId;
					$theSorter = $firstSub['sorter'];
				}

				$firstItem = getFirstImage($theCatId, $theSorter);
				$hasVideo = false;
				$firstItemVideo = getImageVideo($firstItem['id']);
				if ($firstItemVideo['hoverFile'] != "") {
					$hasVideo = true;
					$videoStr = '<video muted playsinline loop><source src="/videos/' . $firstItemVideo['hoverFile'] . '" /></video>';
				}
				if ($firstItemVideo['url_loop_desktop'] != "") {
					$hasVideo = true;
					$videoStr = '<video muted playsinline loop class="sizeload" data-hd="' . $firstItemVideo['url_loop_desktop'] . '" data-sd="' . $firstItemVideo['url_loop_mobile'] . '"><source src="" /></video>';
				}

				?>
				<div class="cell" data-id="<?= $cat['id'] ?>">
					<?php if ($hasVideo) { ?>
						<?= $videoStr ?>
					<?php } else { ?>
						<img src="<?= $loaderImg ?>" data-img="<?= $firstItem['img'] ?>" class="photo loadmeview" alt="<?= $cat['name'] ?>" />
					<?php } ?>
				</div>
			<?php } ?>
		</div>

	</nav>

</header>