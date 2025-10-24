<?php

	include 'secure.php';
	include 'functions.php';
	include 'connect.php';

?>

<script>

$(document).ready(function() {

	// left menu functions

	$(".menuRow").hoverIntent(
	  function () {
		$(this).children().children(".sort").fadeTo(200, 1);
	  },
	  function () {
		$(this).children().children(".sort").fadeTo(200, 0);
	  }
	);
	
	$(function() {
		$("#catList ul").sortable({ opacity: 0.6, handle: '.sort', cursor: 'move', update: function() {
			var order = $(this).sortable("serialize") + '&action=updateRecordsListings';
			$.post("updateCatOrder.php", order, function(theResponse){
				$("#alertDiv").show();
				$("#alertDiv").html(theResponse);

				// hide the element
				$("#alertDiv").fadeOut(3000);

			});
		}
		});
	});

	$(function() {
		$("#subCatList ul").sortable({ opacity: 0.6, handle: '.sortme', cursor: 'move', update: function() {
			var order = $(this).sortable("serialize") + '&action=updateRecordsListings';
			$.post("updateCatOrder.php", order, function(theResponse){
				$("#alertDiv").show();
				$("#alertDiv").html(theResponse);

				// hide the element
				$("#alertDiv").fadeOut(3000);

			});
		}
		});
	});
	
	$(".delete").colorbox({
		width:"402px",
		height:"227px",
		iframe: true,
		opacity: ".95"
	});

});

</script>

<div class="holder">

	<div class="siteBranding">
		<?php if (getOption("adminLogo") != "") { ?>
			<img src="<?php echo getOption("adminLogo"); ?>" style="max-width: 165px; max-height: 75px;"><br />
		<?php } else { ?>
			<b style="font-size: 14px;"><?php echo getOption("siteName"); ?></b><br />
		<?php } ?>
		DASHBOARD <span class="xSmall">v. <span class="smallGray" style="color: #b8b5b5;">2.0</span></span><br />
		<div id="line" style="width: 126px; margin-bottom: 10px; margin-top: 10px;"></div>
	</div>

<?php

	if ($_SESSION['admin'] === "yes") {

		$categories = catList();

?>
		<div class="label" id="contentTopMenu">
			SITE CONTENT
		</div>

		<div class="leftMenu" id="catList" style="<?php if ($_GET['showMenu'] === "yes"){ ?>display: block;<?php } else { ?>display: none;<?php } ?> margin-top: 10px; margin-left: -16px;">
			<ul>
			<?php

				for ($count = 1; $category = mysql_fetch_array($categories); ++$count) {

					$thisID = $category['id'];
					$thisParentID = 0;
					$name = $category['name'];
					$pageType = $category['pageType'];
					$status = $category['status'];
					$imgON = $category['imgON'];
					$offsetTop = $category['imgOFF'];
					$linkURL = $category['linkURL'];
					$thisSectionID = $category['sectionID'];

					// NOT CURRENTLY USED, KEEP IN PLACE FOR OPEN / CLOSED STATE OF SECTION
					if ($catID == $category['id'] OR $parentID == $category['id']) {
						$pre = "[-]";
					} else {
						$pre = "[+]";
					}

					$layout = getLayout($pageType);
					$link = $layout['type'] . "?category=".$category['id'];
					$hideAdd = "no";
					$target = "_parent";
					$newPage = "yes";

			?>

					<li id="recordsArray_<?php echo $category['id']; ?>" class="menuRow">

						<div class="sortHolder" style="float: left; height: 16px; width: 16px;">
							<img src="images/icons/sort.png" class="sort" style="display: none; height: 100%; padding-top: 2px;">
						</div>
						<div class="sortLink" style="float: left; width: 162px; margin-left: 5px; border: 0px solid red;">
							<?php if ($newPage === "no") { ?>
								<a class="leftCol ajax" rel="<?=$link?>" onclick="return false"><?=$category['name']?></a> <?php if ($status === "preview") { ?><span class="smallRed">off</span><br /><?php } ?>
							<?php } else { ?>
								<a class="leftCol" href="<?=$link?>"><?=$category['name']?></a> <?php if ($status === "preview") { ?><span class="smallRed">off</span><br /><?php } ?>
							<?php } ?>
						</div>

						<div style="<?php if ($_GET['showSub'] == $category['id']){ ?>display: block;<?php } else { ?>display: none;<?php } ?> float: left;" class="menuSub">

							<div style="margin-left: 20px;">
								<div id="addRow">
									<img src="images/icons/edit.png" border="0" id="addIcon"><a class="Edit" id="editCat<?=$category['id']?>" onclick="return false;">edit</a>
								</div>

								<?php if ($hideAdd != "yes") { ?>

									<div id="addRow">
										<img src="images/icons/add.png" border="0" id="addIcon"><a class="Add" id="addCat<?=$category['id']?>" onclick="return false;">add</a>
									</div>

								<?php } ?>

								<?php if ($category['status'] === "preview") { ?>

									<div id="addRow">
										<img src="images/icons/delete.png" border="0" id="addIcon"><a href="delete_page.php?id=<?=$category['id']?>&pageName=<?=$category['name']?>" class="delete Add">delete</a>
									</div>

								<?php } ?>

								<div style="clear: both; height: 10px;"></div>

								<div id="catForm<?=$category['id']?>" class="catformadd" style="display: none">
									<?php include('catForm.php'); ?>
								</div>

								<div id="catFormEdit<?=$category['id']?>" class="catformedit" style="display: none">
									<?php include('catFormEdit.php'); ?>
								</div>

							</div>

							<!--SUB-CATEGORIES START BELOW-->

							<div style="margin-left: 40px;" id="subCatList">
								<ul>

									<?php

										$subcategories = subcatList($category['id']);

										for ($subcount = 1; $subcategory = mysql_fetch_array($subcategories); ++$subcount) {

											$thisID = $subcategory['id'];
											$thisParentID = $subcategory['parentID'];
											$name = $subcategory['subName'];
											$pageType = $subcategory['pageType'];
											$status = $subcategory['status'];
											$imgON = $subcategory['imgON'];
											$offsetTop = $subcategory['imgOFF'];
											$linkURL = $subcategory['linkURL'];

											$layout = getLayout($pageType);
											$link = $layout['type'] . "?category=".$subcategory['id']."&parentID=".$thisParentID;
											$target = "_parent";
											$newPage = "yes";

									?>

										<li id="recordsArray_<?php echo $subcategory['id']; ?>">
											<img src="images/icons/sortSmallest.png" class="sortme" style="position: relative; top: 3px;"> <a rel="<?=$link?>" onclick="return false;" class="subleftCol ajax"><?=$subcategory['subName'];?></a> <?php if ($status === "preview") { ?><span class="smallRed">off</span><?php } ?><br />

											<div class="subOptions" style="display: none;">

												<div id="addRow">
													<img src="images/icons/edit.png" border="0" id="addIcon"><a class="Edit" id="editCat<?=$subcategory['id']?>" onclick="return false;">edit</a>
												</div>

												<?php if ($subcategory['status'] === "preview") { ?>

													<div id="addRow">
														<img src="images/icons/delete.png" border="0" id="addIcon"><a href="delete_page.php?id=<?=$subcategory['id']?>&pageName=<?=$subcategory['subName']?>" class="delete Edit">delete</a>
													</div>

												<?php } ?>

												<div style="margin-left: 0px;">
													<div id="catFormEdit<?=$subcategory['id']?>" class="catformedit" style="display: none">
														<?php include('catFormEdit.php'); ?>
													</div>
												</div>
												<div style="clear: both;"></div>

											</div>
										</li>

									<?php

										}

									?>

								</ul>

							</div>

						</div>

						<div style="clear: both;"></div>

					</li>

				<?php

					}

				?>
			</ul>

		</div>
		
		<?php if (getOption("ecom") === "yes") { ?>

			<div class="label" id="storeTopMenu">
				MANAGE STORE
			</div>

			<div class="leftMenu" id="storeList" style="display: none; margin-top: 10px; margin-left: -16px;">
				<ul style="margin-left: 16px; list-style-type: none !important;">

				<?php

					$options = getProductOptions("resultSet");

					for ($count = 1; $category = mysql_fetch_array($options); ++$count) {

						$thisID = $category['id'];
						$thisParentID = 0;
						$name = $category['name'];
						$pageType = $category['pageType'];
						$status = $category['status'];
						$imgON = $category['imgON'];
						$imgOFF = $category['imgOFF'];
						$linkURL = $category['linkURL'];
						$thisSectionID = $category['sectionID'];

						// NOT CURRENTLY USED, KEEP IN PLACE FOR OPEN / CLOSED STATE OF SECTION
						if ($catID == $category['id'] OR $parentID == $category['id']) {
							$pre = "[-]";
						} else {
							$pre = "[+]";
						}

						if ($category['pageType'] === "images - grid" OR $category['pageType'] === "images - scroll") {
							$link = "manage_images.php?category=".$category['id'];
							$hideAdd = "no";
							$target = "_parent";
						}

						if ($category['pageType'] === "text") {
							$link = "manage_text.php?category=".$category['id'];
							$hideAdd = "no";
							$target = "_parent";
						}

						if ($category['pageType'] === "videos") {
							$link = "manage_video.php?category=".$category['id'];
							$hideAdd = "no";
							$target = "_parent";
						}

						if ($category['pageType'] === "media") {
							$link = "manage_media.php?category=".$category['id'];
							$hideAdd = "no";
							$target = "_parent";
						}

						if ($category['pageType'] === "link") {
							$link = "manage_link.php?category=".$category['id'];
							$hideAdd = "yes";
							$target = "_parent";
						}

				?>

						<li><a class="leftCol ajax" rel="<?=$link?>" onclick="return false"><?=$category['name']?></a></li>

							<div style="display: none; float: left;" class="menuSub">

								<div style="margin-left: 20px;">
									<div id="addRow">
										<img src="images/icons/edit.png" border="0" id="addIcon"><a class="Edit" id="editCat<?=$category['id']?>" onclick="return false;">edit</a>
									</div>

									<?php if ($hideAdd != "yes") { ?>

										<div id="addRow">
											<img src="images/icons/add.png" border="0" id="addIcon"><a class="Add" id="addCat<?=$category['id']?>" onclick="return false;">add</a>
										</div>

									<?php } ?>

									<?php if ($category['status'] === "preview") { ?>

										<div id="addRow">
											<img src="images/icons/delete.png" border="0" id="addIcon"><a href="delete_page.php?id=<?=$category['id']?>&pageName=<?=$category['name']?>" class="delete Add">delete</a>
										</div>

									<?php } ?>

									<div style="clear: both; height: 10px;"></div>

									<div id="catForm<?=$category['id']?>" style="display: none">
										<?php include('catForm.php'); ?>
									</div>

									<div id="catFormEdit<?=$category['id']?>" style="display: none">
										<?php include('catFormEdit.php'); ?>
									</div>
									<script>
									$("#editCat<?=$category['id']?>").click(function () {
										$("#catFormEdit<?=$category['id']?>").toggle();
									});
									</script>

									<script>
									$("#addCat<?=$category['id']?>").click(function () {
										$("#catForm<?=$category['id']?>").toggle();
									});
									</script>

								</div>

								<!--SUB-CATEGORIES START BELOW-->

								<div style="margin-left: 20px;" id="subCatList">
									<ul>

										<?php

											$subcategories = subcatList($category['id']);

											for ($subcount = 1; $subcategory = mysql_fetch_array($subcategories); ++$subcount) {

												$thisID = $subcategory['id'];
												$thisParentID = $subcategory['parentID'];
												$name = $subcategory['subName'];
												$pageType = $subcategory['pageType'];
												$status = $subcategory['status'];
												$imgON = $subcategory['imgON'];
												$imgOFF = $subcategory['imgOFF'];
												$linkURL = $subcategory['linkURL'];

												if ($subcategory['pageType'] === "images - scroll" OR $subcategory['pageType'] === "images - grid") {
													$link = "manage_images.php?category=".$subcategory['id']."&parentID=".$thisParentID;
													$target = "_parent";
												}

												if ($subcategory['pageType'] === "text") {
													$link = "manage_text.php?category=".$subcategory['id']."&parentID=".$thisParentID;
													$target = "_parent";
												}

												if ($subcategory['pageType'] === "videos") {
													$link = "manage_video.php?category=".$subcategory['id']."&parentID=".$thisParentID;
													$target = "_parent";
												}

												if ($subcategory['pageType'] === "media") {
													$link = "manage_media.php?category=".$subcategory['id']."&parentID=".$thisParentID;
													$target = "_parent";
												}

												if ($subcategory['pageType'] === "link") {
													$link = "manage_link.php?category=".$subcategory['id'] . "&parentID=".$thisParentID;
													$hideAdd = "yes";
													$target = "_parent";
												}

										?>

											<li id="recordsArray_<?php echo $subcategory['id']; ?>">
												<a rel="<?=$link?>" onclick="return false;" class="subleftCol ajax"><?=$subcategory['subName'];?></a><br />
												
												<div class="subOptions" style="display: none;">

													<div id="addRow">
														<img src="images/icons/edit.png" border="0" id="addIcon"><a class="Edit" id="editCat<?=$subcategory['id']?>" onclick="return false;">edit</a>
													</div>

													<?php if ($subcategory['status'] === "preview") { ?>

														<div id="addRow">
															<img src="images/icons/delete.png" border="0" id="addIcon"><a href="delete_page.php?id=<?=$subcategory['id']?>&pageName=<?=$subcategory['subName']?>" class="delete Edit">delete</a>
														</div>

													<?php } ?>

													<div style="margin-left: 0px;">
														<div id="catFormEdit<?=$subcategory['id']?>" style="display: none">
															<?php include('catFormEdit.php'); ?>
														</div>
														<script>
														$("#editCat<?=$subcategory['id']?>").click(function () {
															$("#catFormEdit<?=$subcategory['id']?>").toggle();
														});
														</script>

													</div>
													<div style="clear: both;"></div>

												</div>
												
											</li>

										<?php

											}

										?>

									</ul>

								</div>

							</div>

							<div style="clear: both;"></div>

						</li>


				<?php

					}

				?>
					<li><a class="leftCol" rel="manage_inventory.php" onclick="return false">MANAGE INVENTORY</a></li>
					<li><a class="leftCol" rel="manage_coupons.php" onclick="return false">DISCOUNT CODES</a></li>
					<li><a class="leftCol" rel="manage_reports.php" onclick="return false">STORE REPORTS</a></li>
					<!--
					<li><a href="#" class="leftCol ajax" id="manage_shipping.php" onclick="return false">SHIPPING OPTIONS</a></li>
					<li><a href="#" class="leftCol ajax" id="manage_payment.php" onclick="return false">PAYMENT OPTIONS</a></li>
					<li><a href="#" class="leftCol ajax" id="manage_settings.php" onclick="return false">GENERAL SETTINGS</a></li>
					-->
				</ul>
			</div>
			
		<?php } ?>

		<br /><div id="line" style="width: 126px; margin-bottom: 10px;"></div>
		<a href="manage_users.php" class="leftLink">MANAGE USERS</a><br />
		<a href="logout.php" target="_parent" class="leftLink">LOGOUT</a>


		<?php

			}

		?>

		<!--<br /><div id="line" style="width: 126px; margin-top: 8px;"></div>-->

		<div id="alertDiv">

		</div>

</div>