<?php mysqli_data_seek($pageSubs, 0); ?>
<?php for ($pagecount = 1; $page = mysqli_fetch_array($pageSubs); ++$pagecount) { ?>

	<?php

	if ($page['status'] != "live") {
		continue;
	}

	?>


	<?php if ($page['pageType'] === "text") { ?>

		<?php

		if ($isMods) {
			$theText = $page['content'];
		} else {
			$pageText = getPageTextID($page['id']);
			$theText = $pageText['content'];
		}

		?>

		<div data-id="<?= $page['id'] ?>" class="text-block module <?php if ($page['showTitle'] === "yes") { ?>anchor<?php } ?>" id="module_<?= $page['id'] ?>">
			<div class="inner">
				<?= $theText ?>
			</div>
		</div>

	<?php } ?>

	<?php

	/*

			NOTE - more specific page types will be used in admin.  i.e. grid - 3 column, grid - 4 column, etc.
			Need to change the page type check below to match client site.

		*/

	?>

	<?php if ($page['pageType'] === "grid - justified") { ?>

		<?php

		if ($isMods) {
			$images = getModuleImages($page['id'], $page['sorter']);
		} else {
			$images = getImages($page['id'], $page['sorter']);
		}

		?>

		<div data-id="<?= $page['id'] ?>" class="grid_wrap module <?php if ($page['showTitle'] === "yes") { ?>anchor<?php } ?>" id="module_<?= $page['id'] ?>">

			<?php if ($page['showTitle'] === "yes") { ?>
				<h2 class="sectionTitle"><?= $page['title'] ?></h2>
			<?php } ?>

			<div class="ar-rows" data-margin="4">

				<figure class="row">

					<?php for ($count = 1; $item = mysqli_fetch_array($images); ++$count) { ?>

						<?php

						$ext = pathinfo(getOption("physicalPath") . "images/pics/" . $item['img'], PATHINFO_EXTENSION);

						$theLink = "#" . $item['slug'];

						$itemVideo = getImageVideo($item['id']);
						$itemText = getImageText($item['id']);

						$cellClass = "openOverlay";
						$hasAutoVideo = false;
						$videoStr = "";
						if ($itemVideo['hoverFile'] != "" or $itemVideo['url_loop_desktop'] != "") {
							$hasAutoVideo = true;
							if ($itemVideo['bgVideoType'] === "view") {
								$cellClass .= " autovideo";
							} else {
								$cellClass .= " hovervideo";
							}
							if ($itemVideo['url_loop_desktop'] != "") {
								$videoStr = '<video class="sizeload" playsinline loop muted data-hd="' . $itemVideo['url_loop_desktop'] . '" data-sd="' . $itemVideo['url_loop_mobile'] . '"><source src="" /></video>';
							} else {
								$videoStr = '<video muted playsinline loop><source src="/videos/' . $itemVideo['hoverFile'] . '" /></video>';
								if ($itemVideo['hoverFileMobile'] != "") {
									$videoStr = '<video class="sizeload" playsinline loop muted data-hd="/videos/' . $itemVideo['hoverFile'] . '" data-sd="/videos/' . $itemVideo['hoverFileMobile'] . '"><source src="" /></video>';
								}
							}
						}

						$hasTitle = false;
						$hasCaption = false;

						if ($item['title'] != "title:" and $item['title'] != "") {
							$hasTitle = true;
						}

						if ($item['caption'] != "caption:" and $item['caption'] != "") {
							$hasCaption = true;
						}

						$hasVideo = false;
						if ($itemVideo['file'] != "" or $itemVideo['embed'] != "" or $itemVideo['url_desktop'] != "") {
							$hasVideo = true;
							$cellClass .= " openOverlay";
						}

						?>

						<a href="<?= $theLink ?>" class="cell <?= $cellClass ?>" data-id="<?= $item['id'] ?>" id=" <?= $count ?>" <?php if ($hasAutoVideo) { ?>data-autovideo='<?= $videoStr ?>' <?php } ?>>

							<div class="mediawrap" data-ratio=".41">
								<img class="photo loadme loadmeview <?= $ext ?>" src="<?= $loaderImg ?>" data-img="<?= $item['img'] ?>" data-width="<?= $item['width'] ?>" data-height="<?= $item['height'] ?>">

								<?php if ($hasAutoVideo) { ?>
									<div class="vidhold"></div>
								<?php } ?>

								<?php if ($hasTitle or $hasCaption) { ?>
									<figcaption class="info">
										<?php if ($hasCaption) { ?>
											<h3><?= $item['caption'] ?></h3>
										<?php } ?>
										<?php if ($hasTitle) { ?>
											<h2><?= $item['title'] ?></h2>
										<?php } ?>
										<div class="credits">
											<?= $itemText['content'] ?>
										</div>
									</figcaption>
								<?php } ?>

								<?php if ($hasVideo) { ?>
									<svg class="video_icon" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
										<g id="Group_93" data-name="Group 93" transform="translate(-912 -2962)">
											<rect id="Rectangle_91" data-name="Rectangle 91" width="48" height="48" transform="translate(912 2962)" fill="#fff" />
											<path id="Polygon_10" data-name="Polygon 10" d="M9.9,0l9.9,16.762H0Z" transform="translate(946.286 2976.857) rotate(90)" />
										</g>
									</svg>

								<?php } ?>

							</div>

						</a>

						<?php

						if ($item['rowBreak'] === "yes") {

							echo '</figure>';
							echo '<figure class="row">';
						}

						?>

					<?php } ?>

				</figure>

			</div>

		</div>

	<?php } ?>

	<?php if ($page['pageType'] === "fullwidth") { ?>

		<?php

		if ($isMods) {
			$images = getModuleImages($page['id'], $page['sorter']);
		} else {
			$images = getImages($page['id'], $page['sorter']);
		}

		?>

		<div data-id="<?= $page['id'] ?>" class="fullwidth module <?php if ($page['showTitle'] === "yes") { ?>anchor<?php } ?>" id="module_<?= $page['id'] ?>">

			<?php for ($count = 1; $item = mysqli_fetch_array($images); ++$count) { ?>

				<?php

				$ext = pathinfo(getOption("physicalPath") . "images/pics/" . $item['img'], PATHINFO_EXTENSION);
				$itemRatio = $item['height'] / $item['width'];
				$itemPad = $itemRatio * 100;

				$itemVideo = getImageVideo($item['id']);
				$itemText = getImageText($item['id']);
				$hasText = false;
				if ($itemText['content'] != "") {
					$hasText = true;
					$cellClass .= " with_overlay";
				}

				$hasAutoVideo = false;
				if ($itemVideo['hoverFile'] != "") {
					$hasAutoVideo = true;
					if ($itemVideo['bgVideoType'] === "view") {
						$cellClass .= " autovideo";
					} else {
						$cellClass .= " hovervideo";
					}
				}

				$hasTitle = false;
				$hasCaption = false;
				$hasVideo = false;
				$addClass = "openOverlay";

				if ($item['title'] != "title:" and $item['title'] != "") {
					$hasTitle = true;
				}
				if ($item['caption'] != "caption:" and $item['caption'] != "") {
					$hasCaption = true;
				}
				$hasVideo = false;
				if ($itemVideo['file'] != "" or $itemVideo['embed'] != "" or $itemVideo['url_desktop'] != "") {
					$hasVideo = true;
					$cellClass .= " openOverlay";
				}

				?>

				<figure class="cell <?= $cellClass ?>" data-id="<?= $item['id'] ?>" <?php if ($hasAutoVideo) { ?>data-autovideo='<video muted playsinline loop><source src="/videos/<?= $itemVideo['hoverFile'] ?>" /></video>' <?php } ?>>
					<div class="mediawrap" style="padding-top: <?= $itemPad ?>%;">
						<img class="photo loadme loadmeview <?= $ext ?>" src="<?= $loadImg ?>" data-img="<?= $item['img'] ?>" alt="" />

						<?php if ($hasAutoVideo) { ?>
							<div class="vidhold"></div>
						<?php } ?>
					</div>

					<?php if ($hasVideo) { ?>
						<svg class="video_icon" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
							<g id="Group_93" data-name="Group 93" transform="translate(-912 -2962)">
								<rect id="Rectangle_91" data-name="Rectangle 91" width="48" height="48" transform="translate(912 2962)" fill="#fff" />
								<path id="Polygon_10" data-name="Polygon 10" d="M9.9,0l9.9,16.762H0Z" transform="translate(946.286 2976.857) rotate(90)" />
							</g>
						</svg>

					<?php } ?>

					<?php if ($itemText['content'] != "") { ?>
						<div class="text_wrap">
							<?= $itemText['content'] ?>
						</div>
					<?php } else { ?>

						<div class="info">
							<?php if ($hasTitle) { ?>
								<h2><?= $item['title'] ?></h2>
							<?php } ?>

							<?php if ($hasCaption) { ?>
								<p><?= $item['caption'] ?></p>
							<?php } ?>
						</div>

					<?php } ?>
				</figure>

			<?php } ?>

		</div>

	<?php } ?>

	<?php if ($page['pageType'] === "centered") { ?>

		<?php

		if ($isMods) {
			$images = getModuleImages($page['id'], $page['sorter']);
		} else {
			$images = getImages($page['id'], $page['sorter']);
		}

		?>

		<div data-id="<?= $page['id'] ?>" class="centered module <?php if ($page['showTitle'] === "yes") { ?>anchor<?php } ?>" id="module_<?= $page['id'] ?>">

			<?php for ($count = 1; $item = mysqli_fetch_array($images); ++$count) { ?>

				<?php

				$ext = pathinfo(getOption("physicalPath") . "images/pics/" . $item['img'], PATHINFO_EXTENSION);
				$loadImg = preg_replace('/\\.[^.\\s]{3,4}$/', '', $item['img']);
				$loadImg = getOption("imagePathFront") . "siteGray/" . $loadImg . ".png";

				$itemVideo = getImageVideo($item['id']);
				$itemRatio = $item['height'] / $item['width'];

				$theLink = "/slideshow/" . $itemID . "/" . $slug . "#" . $count;

				$hasTitle = false;
				$hasCaption = false;
				$hasVideo = false;
				$isUpload = false;
				$addClass = "";

				if ($item['title'] != "title:") {
					$hasTitle = true;
				}
				if ($item['caption'] != "caption:") {
					$hasCaption = true;
				}
				if ($itemVideo['file'] != "" or $itemVideo['embed'] != "") {
					$hasVideo = true;
					$addClass = "video";
				}
				if ($itemVideo['file'] != "") {
					$isUpload = true;
				}

				?>

				<figure class="cell">
					<img class="photo loadme loadmeview" src="<?= $loadImg ?>" data-src="<?= $item['img'] ?>" alt="" />

					<div class="info">
						<?php if ($hasTitle) { ?>
							<h2><?= $item['title'] ?></h2>
						<?php } ?>

						<?php if ($hasCaption) { ?>
							<p><?= $item['caption'] ?></p>
						<?php } ?>
					</div>
				</figure>

			<?php } ?>

		</div>

	<?php } ?>

	<?php if ($page['pageType'] === "cropped grid") { ?>

		<?php

		if ($isMods) {
			$images = getModuleImages($page['id'], $page['sorter']);
		} else {
			$images = getImages($page['id'], $page['sorter']);
		}

		?>

		<div data-id="<?= $page['id'] ?>" class="croppedgrid module <?php if ($page['showTitle'] === "yes") { ?>anchor<?php } ?>" id="module_<?= $page['id'] ?>">

			<?php for ($count = 1; $item = mysqli_fetch_array($images); ++$count) { ?>

				<?php

				$ext = pathinfo(getOption("physicalPath") . "images/pics/" . $item['img'], PATHINFO_EXTENSION);
				$loadImg = preg_replace('/\\.[^.\\s]{3,4}$/', '', $item['img']);
				$loadImg = getOption("imagePathFront") . "siteGray/" . $loadImg . ".png";

				$itemVideo = getImageVideo($item['id']);
				$itemRatio = $item['height'] / $item['width'];

				$theLink = "/slideshow/" . $itemID . "/" . $slug . "#" . $count;

				$hasTitle = false;
				$hasCaption = false;
				$hasVideo = false;
				$isUpload = false;
				$addClass = "";

				if ($item['title'] != "title:") {
					$hasTitle = true;
				}
				if ($item['caption'] != "caption:") {
					$hasCaption = true;
				}
				if ($itemVideo['file'] != "" or $itemVideo['embed'] != "") {
					$hasVideo = true;
					$addClass = "video";
				}
				if ($itemVideo['file'] != "") {
					$isUpload = true;
				}

				?>

				<figure class="cell resize-image ratioSize" data-ratio=".6625">

					<a href="<?= $theLink ?>">

						<img class="photo loadme loadmeview" src="<?= $loadImg ?>" data-src="<?= $item['img'] ?>" data-aspect-ratio="<?= $itemRatio ?>" alt="" />

						<div class="info">
							<?php if ($hasTitle) { ?>
								<h2><?= $item['title'] ?></h2>
							<?php } ?>

							<?php if ($hasCaption) { ?>
								<p><?= $item['caption'] ?></p>
							<?php } ?>
						</div>

					</a>

				</figure>

			<?php } ?>

		</div>

	<?php } ?>

	<?php if ($page['pageType'] === "grid - masonry") { ?>

		<?php

		if ($isMods) {
			$images = getModuleImages($page['id'], $page['sorter']);
		} else {
			$images = getImages($page['id'], $page['sorter']);
		}

		?>

		<div data-id="<?= $page['id'] ?>" class="masongrid module <?php if ($page['showTitle'] === "yes") { ?>anchor<?php } ?>" id="module_<?= $page['id'] ?>">

			<div class="grid-sizer"></div>
			<div class="gutter-sizer"></div>

			<?php $itemCount = 1; ?>
			<?php for ($count = 1; $item = mysqli_fetch_array($images); ++$count) { ?>

				<?php

				$ext = pathinfo(getOption("physicalPath") . "images/pics/" . $item['img'], PATHINFO_EXTENSION);
				$itemRatio = $item['height'] / $item['width'];
				$itemPad = $itemRatio * 100;

				$itemVideo = getImageVideo($item['id']);

				$cellClass = "";

				$hasAutoVideo = false;
				if ($itemVideo['hoverFile'] != "") {
					$hasAutoVideo = true;
					$cellClass .= " autovideo";
				}

				if ($item['catSize'] === "large") {
					$cellClass .= " large";
					$itemCount = 0;
				}

				$hasTitle = false;
				$hasCaption = false;

				if ($item['title'] != "title:" and $item['title'] != "") {
					$hasTitle = true;
				}

				if ($item['caption'] != "caption:" and $item['caption'] != "") {
					$hasCaption = true;
				}

				?>

				<figure class="cell openOverlay <?php if ($hasTitle or $hasCaption) { ?>hoverTitle<?php } ?> <?= $cellClass ?>" <?php if ($hasAutoVideo) { ?>data-autovideo='<video muted playsinline loop><source src="/videos/<?= $itemVideo['hoverFile'] ?>" /></video>' <?php } ?> data-id="<?= $item['id'] ?>">
					<div class="mediawrap" style="padding-top: <?= $itemPad ?>%;">
						<img src="<?= $loaderImg ?>" data-img="<?= $item['img'] ?>" class="photo loadmeview" alt="<?= $company ?>" />
						<?php if ($hasAutoVideo) { ?>
							<div class="vidhold"></div>
						<?php } ?>
					</div>

					<figcaption class="info">
						<?php if ($hasTitle) { ?>
							<h2><?= $item['title'] ?></h2>
						<?php } ?>
						<?php if ($hasCaption) { ?>
							<h3><?= $item['caption'] ?></h3>
						<?php } ?>
					</figcaption>

				</figure>

				<?php
				++$itemCount;
				if ($itemCount == 3) {
					$itemCount == 1;
				}
				?>

			<?php } ?>

		</div>

	<?php } ?>

	<?php

	/*

			NOTE - more specific page types will be used in admin.  i.e. grid - 3 column, grid - 4 column, etc.
			Need to change the page type check below to match client site.

		*/

	?>

	<?php if ($page['pageType'] === "justified-rows") { ?>

		<?php

		if ($isMods) {
			$images = getModuleImages($page['id'], $page['sorter']);
		} else {
			$images = getImages($page['id'], $page['sorter']);
		}

		?>

		<div data-id="<?= $page['id'] ?>" class="justified module <?php if ($page['showTitle'] === "yes") { ?>anchor<?php } ?>" id="module_<?= $page['id'] ?>">

			<?php for ($count = 1; $item = mysqli_fetch_array($images); ++$count) { ?>

				<?php

				$ext = pathinfo(getOption("physicalPath") . "images/pics/" . $item['img'], PATHINFO_EXTENSION);
				$loadImg = preg_replace('/\\.[^.\\s]{3,4}$/', '', $item['img']);
				$loadImg = getOption("imagePathFront") . "siteGray/" . $loadImg . ".png";

				$itemVideo = getImageVideo($item['id']);

				$theLink = "/slideshow/" . $slug . "#" . $count;

				$hasTitle = false;
				$hasCaption = false;
				$hasVideo = false;
				$isUpload = false;
				$addClass = "";

				if ($item['title'] != "title:") {
					$hasTitle = true;
				}
				if ($item['caption'] != "caption:") {
					$hasCaption = true;
				}
				if ($itemVideo['file'] != "" or $itemVideo['embed'] != "") {
					$hasVideo = true;
					$addClass = "video";
				}
				if ($itemVideo['file'] != "") {
					$isUpload = true;
				}

				?>

				<a href="<?= $theLink ?>" class="cell <?= $addClass ?>">

					<img class="loadme loadmeview" src="<?= $loadImg ?>" data-src="<?= $item['img'] ?>" alt="" />

					<?php if ($hasTitle) { ?>
						<span class="title"><?= $item['title'] ?></span>
					<?php } ?>

					<?php if ($hasCaption) { ?>
						<span class="sub-title"><?= $item['caption'] ?></span>
					<?php } ?>

				</a>

			<?php } ?>

		</div>

	<?php } ?>

	<?php if ($page['pageType'] === "slideshow") { ?>

		<?php

		if ($isMods) {
			$images = getModuleImages($page['id'], $page['sorter']);
		} else {
			$images = getImages($page['id'], $page['sorter']);
		}

		?>

		<div data-id="<?= $page['id'] ?>" class="slideshow slickslideshow autoplay fullbleed fade module <?php if ($page['showTitle'] === "yes") { ?>anchor<?php } ?>" id="module_<?= $page['id'] ?>">

			<?php for ($count = 1; $item = mysqli_fetch_array($images); ++$count) { ?>

				<?php

				$ext = pathinfo(getOption("physicalPath") . "images/pics/" . $item['img'], PATHINFO_EXTENSION);
				$loadImg = preg_replace('/\\.[^.\\s]{3,4}$/', '', $item['img']);
				$loadImg = getOption("imagePathFront") . "siteGray/" . $loadImg . ".png";

				$itemRatio = $item['height'] / $item['width'];

				?>

				<figure class="resize-image">
					<img src="<?= $loadImg ?>" data-src="<?= $item['img'] ?>" data-aspect-ratio="<?= $itemRatio ?>" class="<?= $ext ?> loadmeview photo" />
				</figure>

			<?php } ?>

			<h1><?= getOption("company") ?></h1>

		</div>

	<?php } ?>

<?php } ?>