<?php

session_start();
include 'includes/secure.php';
include '../includes/connect.php';
include 'includes/functions.php';

$type = "";
$parentPic = "";
$modedit = "";

$pageStatus = checkStatus($catID);
$text = getPageDetailText($picID);
$parentID = getParentID($catID);
if (isset($_GET['parentParentID'])) {
	$parentParentID = $_GET['parentParentID'];
}
if (isset($_GET['type'])) {
	$type = $_GET['type'];
}
if (isset($_GET['modedit'])) {
	$modedit = $_GET['modedit'];
}
if (isset($_GET['showRelated'])) {
	$showRelated = $_GET['showRelated'];
}
if (isset($_GET['parentPic'])) {
	$parentPic = $_GET['parentPic'];
}
if (isset($_GET['pageModule'])) {
	$pageModule = $_GET['pageModule'];
}
if (isset($_GET['fromModule'])) {
	$fromModule = $_GET['fromModule'];
}
if (isset($_GET['moduleid'])) {
	$fromModuleId = $_GET['moduleid'];
}
$videos = getPicVideo($picID);
$modules = getModules($picID);
$allmods = getModLayouts();
$metaType = "product";

$theParent = catDetails($catID);
if ($theParent['name'] != "") {
	$crumbName = $theParent['name'];
} else {
	$crumbName = $theParent['subName'];
}

// multi tag system
$tagNameArray = array("Portfolio Categories");
$tagSectionArray = array("tags");

// override for showing related content
if ($catID == 482) {
	$showRelated = "yes";
}

// store videos in array for use
$videoArray = array();
for ($count = 0; $video = mysqli_fetch_array($videos); ++$count) {

	$videoArray[$count]['file'] = $video['file'];
	$videoArray[$count]['width'] = $video['width'];
	$videoArray[$count]['height'] = $video['height'];
	$videoArray[$count]['hoverFile'] = $video['hoverFile'];
	$videoArray[$count]['hoverFileMobile'] = $video['hoverFileMobile'];
	$videoArray[$count]['embed'] = $video['embed'];

	$videoArray[$count]['url_desktop'] = $video['url_desktop'];
	$videoArray[$count]['url_mobile'] = $video['url_mobile'];
	$videoArray[$count]['bgVideoType'] = $video['bgVideoType'];

	$videoArray[$count]['url_loop_desktop'] = $video['url_loop_desktop'];
	$videoArray[$count]['url_loop_mobile'] = $video['url_loop_mobile'];
}

$query1 = "SELECT * FROM pics WHERE id = '$picID' ORDER BY sortBy ASC";

$result1 = mysqli_query(Database::$conn, $query1);

if (!$result1) {
	die('Could not query:' . mysqli_error(Database::$conn));
	exit;
}

$mainPic = mysqli_fetch_array($result1);

// custom slug check			
if ($mainPic['customSlug'] != "") {
	$theSlug = $mainPic['customSlug'];
} else {
	$theSlug = $mainPic['id'] . "/" . $mainPic['slug'];
}

if ($theParent['pageType'] === "artist") {
	$backLink = "manage_text.php?category=" . $catID . "&parentID=" . $parentID;
} else {
	$backLink = "manage_images.php?category=" . $catID . "&parentID=" . $parentID;
}

$previewURL = "/pitch/" . $mainPic['slug'];

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

			<div class="imagePage">

				<div id="alertDivPic" style="float: right; margin: 9px 5px 0 0;"></div>
				<?php if (isset($type) and $type === "product") { ?>
					<p style="float: left;"><span class="head"><span style="color: #ababab;">Product Detail:</span> <?= strip_tags($mainPic['title']) ?></span></p>
				<?php } else { ?>
					<p style="float: left;"><span class="head"><span style="color: #ababab;">Image Detail:</span> <?= strip_tags($mainPic['title']) ?></span> (<a href="<?= $previewURL ?>" target="_blank">View Page</a>)</p>
				<?php } ?>

				<div id="breadcrumb">
					<?php if (isset($type) and $type === "product") { ?>
						<a href="#" id="manage_products.php?catID=<?= $catID ?>" class="breadcrumb" onclick="return false;">Back to Products</a>
					<?php } else { ?>
						<?php if ($pageModule === "yes") { ?>
							<a href="main.php">DASHBOARD</a> > <a href="manage_modules.php?category=<?= $catID ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>&modedit=recordsArray_<?= $fromModuleId ?>"><?= $crumbName ?></a> > <span class="pageOn">ITEM DETAIL</span>
						<?php } else { ?>
							<?php if (isset($parentPic) and $parentPic != "") { ?>
								<a href="main.php">DASHBOARD</a> > <a href="detail.php?picID=<?= $parentPic ?>&category=<?= $catID ?>">IMAGES</a> > <span class="pageOn">IMAGE DETAIL</span>
							<?php } else { ?>
								<a href="main.php">DASHBOARD</a> > <a href="manage_images.php?category=<?= $catID ?>">IMAGES</a> > <span class="pageOn">IMAGE DETAIL</span>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				</div>

				<div id="line" style="width: 99%; margin-bottom: 2px; clear: both;"></div>
				<div id="line" style="width: 99%;"></div><br />

				<?php include('metaEditor.php'); ?>

				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td align="left" valign="top" style="width: 40%; margin-right: 3%;">

							<div style="float: left; width: 100%;" id="img">

								<div style="float: left; margin-right: 40px; width: 150px;">

									<span class="head">Main Image</span><br />

									<?php

									if ($mainPic['imgSrc'] === "upload") {
										$theImg = getOption("imagePath") . "500/" . $mainPic['img'];
										$fullImg = getOption("imagePath") . $mainPic['img'];
									} else {
										$theImg = $mainPic['img'];
										$fullImg = $mainPic['img'];
									}

									?>

									<?php if ($mainPic['img'] != "no-image.jpg") { ?>
										<a href="<?= $fullImg ?>" target="_blank"><img src="<?= $theImg ?>" width="150"></a><br />
									<?php } ?>
									<form name="editMain" action="updateProjectImg.php" enctype="multipart/form-data" method="post" target="iframe-post-form">
										<input type="hidden" name="picID" value="<?= $picID ?>">
										<input type="hidden" name="catID" value="<?= $catID ?>">
										<input type="file" name="mainImg"><br />
										<input type="submit" value="Update Image" id="btn"><br /><br />
									</form>

								</div>

								<?php if (isset($type) and $type != "product") { ?>

									<div style="float: left; width: 230px; <?php if (getOption("itemPoster") === "yes") { ?>display: block;<?php } else { ?>display: none;<?php } ?>">

										<span class="head">Poster Image</span><br />

										<?php if ($mainPic['posterImg'] != "") { ?>
											<img src="<?php echo getOption("imagePathSave"); ?>square/<?= $mainPic['posterImg'] ?>?fit=crop&w=150&h=150" width="150" height="150"><br />
										<?php } else { ?>
											<img src="images/no-poster.png" style="border: 0px;"><br />
										<?php } ?>
										<form name="editPoster" action="updatePosterImg.php" enctype="multipart/form-data" method="post" target="iframe-post-form">
											<input type="hidden" name="picID" value="<?= $picID ?>">
											<input type="hidden" name="catID" value="<?= $catID ?>">
											<input type="file" name="posterImg"><br />
											<input type="submit" value="Update Image" id="btn"><br /><br />
										</form>

									</div>

									<?php if (getOption("itemPDF") === "yes") { ?>

										<div style="float: left; width: 150px; display: none;">

											<span class="head">PDF</span><br />
											Current File: <a href="/downloads/<?= $mainPic['pdf'] ?>#zoom=50" target="_blank"><?= $mainPic['pdf'] ?></a><br /><br />

											<form name="editDownload" action="updateProjectPDF.php" enctype="multipart/form-data" method="post" target="iframe-post-form">
												<input type="hidden" name="picID" value="<?= $picID ?>">
												<input type="hidden" name="catID" value="<?= $catID ?>">
												<input type="file" name="pdfFile"><br />
												<input type="submit" value="Update PDF" id="btn"><br /><br />
											</form>

										</div>

									<?php } ?>

								<?php } ?>

								<?php if (isset($type) and $type === "product") { ?>

									<div class="product-details">

										<span>Title:</span> <input type="text" name="title" id="title" value="<?= $mainPic['title'] ?>"><br /><br />
										<span>Price:</span> <input type="text" name="price" id="price" value="<?= $mainPic['price'] ?>"><br /><br />
										<span>Wholesale Price:</span> <input type="text" name="wPrice" id="wPrice" value="<?= $mainPic['wPrice'] ?>"><br /><br />
										<span>Sales Price:</span> <input type="text" name="salesPrice" id="salesPrice" value="<?= $mainPic['salesPrice'] ?>"><br /><br />
										<span>Featured Available:</span> <input type="text" name="featuredQty" id="featuredQty" value="<?= $mainPic['featuredQty'] ?>"><br /><br />
										<span>Sold Out:</span>

										<?php if ($mainPic['soldOut'] === "yes") { ?>
											<input type="radio" name="soldOut" id="soldOut" value="yes" checked> yes
											<input type="radio" name="soldOut" id="soldOut" value="no"> no
										<?php } else { ?>
											<input type="radio" name="soldOut" id="soldOut" value="yes"> yes
											<input type="radio" name="soldOut" id="soldOut" value="no" checked> no
										<?php } ?>

										<button id="btn" class="updateDetails" style="margin-left: 135px;">Update Details</button>
										<div class="theResponse" style="margin-top: 3px; display: inline-block;"></div>

									</div>

								<?php } ?>

								<div style="clear: both;"></div>

								<span class="head">TITLE</span><br />
								<input type="text" name="title" id="title" value="<?= $mainPic['title'] ?>" style="width: 315px;"><br /><br />
								<?php /*
							<div style="width: 100%; max-width: 500px;">
								<textarea name="title" id="title" class="mceEditor"><?=$mainPic['title']?></textarea><br /><br />
							</div>
							*/ ?>

								<span class="head">CAPTION</span><br />
								<input type="text" name="caption" id="caption" value="<?= $mainPic['caption'] ?>" style="width: 315px;"><br /><br />

								<?php /*
							<span class="head">PAGE URL</span><br />
							<?=getOption("url")?>/project/<input type="text" name="customSlug" id="customSlug" value="<?=$theSlug?>" style="width: 200px;"><br /><br />
							*/ ?>

								<div style="<?php if (getOption("itemColor") === "yes") { ?>display: block;<?php } else { ?>display: none;<?php } ?>">

									<span class="head">BACKGROUND COLOR</span><br />
									<input type="text" name="color" class="color" id="colorpicker" value="<?= $mainPic['bgColor'] ?>" /><br /><br />

								</div>

								<button id="btn" class="updateDetails" style="float: left; margin-right: 5px;">Update</button>

								<div class="theResponse" style="margin-top: 3px; float: left;"></div>

								<div style="clear: both; height: 25px;"></div>

								<?php if (getOption("itemVideo") === "yes") { ?>

									<?php if (getOption("uploadVideo") === "yes") { ?>

										<div id="videowrap">

											<span class="head">Autoplay Video</span><br /><br />
											<b>UPLOAD FILES</B><br />
											<p>Save video files using MP4 format</p>
											<form name="editVideoHover" action="updateVideoHoverFile.php" enctype="multipart/form-data" method="post" target="iframe-post-form">
												<input type="hidden" name="currentVideo" value="<?= $videoArray[0]['hoverFile'] ?>">
												<input type="hidden" name="currentVideoMobile" value="<?= $videoArray[0]['hoverFileMobile'] ?>">
												<input type="hidden" name="currentWidth" value="<?= $videoArray[0]['hoverWidth'] ?>">
												<input type="hidden" name="currentHeight" value="<?= $videoArray[0]['hoverHeight'] ?>">
												<input type="hidden" name="picID" value="<?= $picID ?>">
												<input type="hidden" name="catID" value="<?= $catID ?>">
												<b>DESKTOP</b><br />
												<?php if (isset($videoArray[0]['hoverFile'])) { ?>
													<em>Current File: <?= $videoArray[0]['hoverFile'] ?></em> (<a href="#" class="deleteImageHoverVideo" data-picid="<?= $picID ?>">delete video</a>)<br />
												<?php } ?>
												<input type="file" name="videoHover"><br /><br />

												<b>MOBILE</b><br />
												<?php if (isset($videoArray[0]['hoverFileMobile'])) { ?>
													<em>Current File: <?= $videoArray[0]['hoverFileMobile'] ?></em> (<a href="#" class="deleteImageHoverVideoMobile" data-picid="<?= $picID ?>">delete video</a>)<br />
												<?php } ?>
												<input type="file" name="videoHoverMobile"><br /><br />

												<b>Play Video On:</b><br /><br />
												<input type="radio" name="bgVideoType" value="hover" <?php if ($videoArray[0]['bgVideoType'] === "hover") { ?>checked<?php } ?>> Hover&nbsp;&nbsp;&nbsp;<input type="radio" name="bgVideoType" value="view" <?php if ($videoArray[0]['bgVideoType'] === "view") { ?>checked<?php } ?>> View<br /><br />

												<input type="submit" value="Update Video" id="btn"><br /><br />
											</form>

											- OR -<br /><br />

											<b>VIDEO URLS</b><br /><br />
											<form name="videoURL" action="updateLoopVideoURL.php" method="post">
												<input type="hidden" name="picID" value="<?= $picID ?>">
												<input type="hidden" name="catID" value="<?= $catID ?>">
												<b>Desktop HD Link:</b><br />
												<input type="text" name="url_loop_desktop" value="<?= $videoArray[0]['url_loop_desktop'] ?>" style="width: 300px;" /><br /><br />

												<b>Mobile SD Link:</b><br />
												<input type="text" name="url_loop_mobile" value="<?= $videoArray[0]['url_loop_mobile'] ?>" style="width: 300px;" /><br /><br />

												<b>Play Video On:</b><br /><br />
												<input type="radio" name="bgVideoType" value="hover" <?php if ($videoArray[0]['bgVideoType'] === "hover") { ?>checked<?php } ?>> Hover&nbsp;&nbsp;&nbsp;<input type="radio" name="bgVideoType" value="view" <?php if ($videoArray[0]['bgVideoType'] === "view") { ?>checked<?php } ?>> View<br /><br />

												<input type="submit" value="Update Autoplay Video Links" id="btn"><br /><br />


											</form>


											<span class="head">Full Video URLs</span><br /><br />
											<form name="videoURL" action="updateVideoURL.php" method="post">
												<input type="hidden" name="picID" value="<?= $picID ?>">
												<input type="hidden" name="catID" value="<?= $catID ?>">
												<b>Desktop HD Link:</b><br />
												<input type="text" name="url_desktop" value="<?= $videoArray[0]['url_desktop'] ?>" style="width: 300px;" /><br /><br />

												<b>Mobile SD Link:</b><br />
												<input type="text" name="url_mobile" value="<?= $videoArray[0]['url_mobile'] ?>" style="width: 300px;" /><br /><br />

												<input type="submit" value="Update Video Links" id="btn"><br /><br />
											</form>

											<?php /*

										<span class="head">Link Full Video</span><br />
										<p>This requires using FTP to add the video file to your server</p>

										<form name="linkVideo" action="linkVideoFile.php" method="post">
										<input type="hidden" name="picID" value="<?=$picID?>">
										<input type="hidden" name="catID" value="<?=$catID?>">
										<input type="text" name="fileurl" style="width: 300px;" placeholder="Video Filename" value="<?=$videoArray[0]['file']?>"><br /><br />
										Width:<br />
										<input type="text" name="filewidth" placeholder="Video Width" value="<?=$videoArray[0]['width']?>"><br /><br />
										Height: <br />
										<input type="text" name="fileheight" placeholder="Video Height" value="<?=$videoArray[0]['height']?>"><br /><br />
										<input type="submit" value="Update Video" id="btn"><br /><br />
										</form>

										*/ ?>

										</div>

									<?php } ?>

									<?php /*

									<?php if (getOption("embedVideo") === "yes") { ?>

										<span class="head">Embed Video</span><br />
										<form name="embed" method="post">
											<input type="hidden" name="picID" id="picID" value="<?= $picID ?>">
											<input type="hidden" name="catID" id="catID" value="<?= $catID ?>">
											<textarea name="embedCode0" id="embedCode0" class="mceNoEditor" style="width: 350px; height: 100px; font-family: Helvetica, Arial;"><?= $videoArray[0]['embed'] ?></textarea><br />

											<button id="btn" class="updateVideo" style="float: left;">Update Video</button> <a href="#" class="addVideosLink" style="margin-left: 15px; position: relative; top: 2px; display: none;">Add Additional Video(s)</a><br /><br />

										</form><br />

									<?php } ?>

									*/ ?>

								<?php } ?>

								<?php if (getOption("tags") === "yes") { ?>

									<span class="head">Content Tag(s)</span><br /><br />
									<form name="tags" method="post">
										<input type="hidden" name="picID" id="picID" value="<?= $picID ?>">
										<input type="hidden" name="catID" id="catID" value="<?= $catID ?>">
										<input type="hidden" name="type" id="type" value="product">

										<?php
										$arrayCount = 0;
										foreach ($tagSectionArray as $tagSet) {
										?>

											<b style="padding-bottom: 3px; display: block;"><?= $tagNameArray[$arrayCount] ?></b>

											<?php

											// need to have unique fields for each type of tag
											$projectTags = $mainPic[$tagSet];
											$tagArray = explode(",", $projectTags);

											$tags = getTags($tagSet);
											for ($count = 1; $tag = mysqli_fetch_array($tags); ++$count) {

											?>

												<?php if (in_array($tag['slug'], $tagArray)) { ?>
													<input type="checkbox" name="tag" class="tag" value="<?= $tag['id'] ?>" checked> <?= $tag['tag'] ?>&nbsp;&nbsp;
												<?php } else { ?>
													<input type="checkbox" name="tag" class="tag" value="<?= $tag['id'] ?>"> <?= $tag['tag'] ?>&nbsp;&nbsp;
												<?php } ?>

											<?php } ?>

										<?php

											++$arrayCount;

											echo "<br /><br />";
										}

										?>

										<br /><button id="btn" class="updateTags" style="float: left; margin-top: 10px;">Update Tags</button>

									</form>

									<div style="clear: both; height: 15px;"></div>

								<?php } ?>

							</div>

						</td>
						<td align="left" valign="top" style="width: 47%">

							<div class="main" style="float: right; margin-right: 18px;">

								<div>

									<span class="head">Description:</span><br />
									<b>Editor Tip</b> - To create a single line break hold down the SHIFT + ENTER Key.<br />
									<form name="textEditor" method="post">
										<input type="hidden" id="picID" name="picID" value="<?= $picID ?>">
										<input type="hidden" id="catID" name="catID" value="<?= $catID ?>">

										<textarea name="myText" id="myText" class="myText mceEditor" style="width: 100%; height: 300px;"><?= stripslashes($text['content']) ?></textarea><br /><br />

								</div>

								<div style="<?php if (getOption("itemDetails2") === "yes") { ?>display: block;<?php } else { ?>display: none;<?php } ?>">
									<span class="head">Additional Details:</span><br />
									<textarea name="myText2" id="myText2" class="myText2 mceEditor" style="width: 550px; height: 200px;"><?= stripslashes($text['content2']) ?></textarea><br>
								</div>

								<?php if (isset($type) and $type === "product") { ?>
									Weight: <input type="text" name="weight" id="weight" value="<?= $mainPic['weight'] ?>"> LBS.<br /><br />
								<?php } ?>

								<button id="btn" class="update" style="float: left; margin-right: 5px;"><?php if (isset($type) and $type === "product") { ?>Update Product<?php } else { ?>Update<?php } ?></button>

								<div class="theResponse" style="margin-top: 3px; float: left;"></div>
								<div style="clear: both; height: 10px;"></div>
								</form>

							</div>

						</td>
					</tr>
				</table>

				<?php if (getOption("modules") === "yes" and $theParent['pageType'] != "news") { ?>

					<span class="head">SECTIONS:</span><br />

					<div id="line" style="width: 99%; margin-bottom: 2px; clear: both;"></div>
					<div id="line" style="width: 99%;"></div><br />

					<div class="moduleAdd label">
						+ add a section
					</div>

					<div id="addModule" style="display: none;">
						<form name="module" action="" method="post">
							Module Title:<br />
							<input type="text" id="moduleTitle" name="title" value=""><br /><br />

							Layout Type:<br />
							<select name="moduleLayout" id="moduleLayout">
								<?php for ($count = 1; $mod = mysqli_fetch_array($allmods); ++$count) { ?>
									<option value="<?= $mod['layout'] ?>"><?= $mod['layout'] ?></option>
								<?php } ?>
							</select><br /><br />

							<button id="btn" class="createModule" style="margin-bottom: 15px; margin-right: 5px; float: left;">Create Module</button>
							<div style="clear: both;"></div>
							<div id="line" style="width: 99%;"></div><br />
						</form>
					</div>

					<div id="moduleHolder">

						<ul id="modules" class="toplevel">

							<?php for ($count = 1; $module = mysqli_fetch_array($modules); ++$count) { ?>

								<li id="recordsArray_<?php echo $module['id']; ?>">
									<?php include('loadModule.php'); ?>
									<div id="line" style="width: 99%; color: #dadada;"></div><br />
								</li>

							<?php } ?>

						</ul>

					</div>

				<?php } ?>

			</div>

		</div>

	</div>

	<?php include('includes/overlay.php'); ?>

	<script type="text/javascript">
		tinymce.init({
			license_key: 'gpl',
			promotion: false,
			mode: "specific_textareas",
			selector: ".mceEditor",
			images_upload_url: 'postAcceptor.php',
			plugins: "image link media lists code",
			toolbar: "styles bold italic forecolor alignleft aligncenter alignright alignjustify bullist numlist outdent indent link image media code",
			preview_styles: false,
			style_formats: [{
					title: 'Heading 1',
					block: 'h1'
				},
				{
					title: 'Heading 2',
					block: 'h2'
				},
				{
					title: 'Heading 3',
					block: 'h3'
				},
				{
					title: 'Paragraph',
					block: 'p'
				},
			],
			image_class_list: [{
					title: 'None',
					value: ''
				},
				{
					title: 'Align Left',
					value: 'float_left'
				},
				{
					title: 'Align Right',
					value: 'float_right'
				}
			],
			link_class_list: [{
					title: 'None',
					value: ''
				},
				{
					title: 'Button',
					value: 'btn'
				}
			],
			content_css: '/css/admin.css',
			relative_urls: false,
			/*
			setup: function(ed) {
				ed.addButton('column', {
					title: 'Make Column',
					text: 'Column',
					icon: false,
					onclick: function() {
						var text = ed.selection.getContent({
							'format': 'html'
						});
						if (text && text.length > 0) {
							ed.execCommand('mceInsertContent', false,
								'<div class="col">' + text + '</div>');
						}
					}
				});
			}
				*/
		});
	</script>

	<script type="text/javascript">
		// RESET THE PRODUCT FILTER SO IT CAN BE USED AGAIN.
		$('.catFilter').prop('selectedIndex', 0);

		/*
		$(function() {
			$("#managePics ul").sortable({ opacity: 0.6, cursor: 'move', update: function() {
				var order = $(this).sortable("serialize") + '&action=updateRecordsListings';
				$.post("updateImgOrder.php", order, function(theResponse){
					$("#alertDivPic").show();
					$("#alertDivPic").html(theResponse);

					// hide the element
					$("#alertDivPic").fadeOut(3000);
				});
			}
			});
		});
		*/

		$(function() {
			$("#moduleHolder ul.toplevel").sortable({
				opacity: 0.6,
				cursor: 'move',
				handle: '.moduleHandle',
				update: function() {
					var order = $(this).sortable("serialize") + '&action=updateRecordsListings';
					$.post("updateModuleOrder.php", order, function(theResponse) {

						var thePage = "detail.php?picID=<?= $picID ?>&category=<?= $catID ?>";
						window.location.href = thePage;

					});
				}
			});
		});

		$(".addOption").click(function() {

			var openID = $(this).attr("id");
			var groupID = $(this).attr("lang");
			$("#option_" + groupID + "_" + openID).slideToggle();

		});

		// product options

		$(".optionsTitle").click(function() {

			$(".prodOptions").slideToggle();

		});

		// related colors

		$(".colorTitle").click(function() {

			$(".relatedColors").slideToggle();

		});

		$(".relatedColorFilter").change(function() {

			var selCatID = $(this).val();
			var picID = $("#picID").val();
			$.ajax({
				url: 'getColors.php?picID=' + picID + '&category=' + selCatID,
				cache: false,
				success: function(data) {
					$('#listColors').html(data);

				}
			});

		});


		// related items

		$(".relatedTitle").click(function() {

			$(".relatedChoices").slideToggle();

		});

		$(".relatedFilter").change(function() {

			var selCatID = $(this).val();
			var picID = $("#picID").val();
			$.ajax({
				url: 'getProducts.php?picID=' + picID + '&category=' + selCatID,
				cache: false,
				success: function(data) {
					$('#listProducts').html(data);

				}
			});

		});

		// update main image

		$('#img form[name="editMain"]').iframePostForm({
			json: false,
			post: function() {
				var message;

				if (!$('.message').length) {
					$('#img').before('<div class="message" style="display:none; padding:10px; text-align:center" />');
				}


				if ($('input[type=file]').val().length) {
					$('.message')
						.html('Uploading file&hellip;')
						.css({
							width: '300px',
							color: '#000',
							background: '#ededed',
							border: '1px solid #e7e7e6'
						})
						.slideDown();
				} else {
					$('.message')
						.html('Please select an image for uploading.')
						.css({
							width: '300px',
							color: '#9c0006',
							background: '#ffc7ce',
							border: '1px solid #9c0006'
						})
						.slideDown();

					return false;
				}
			},
			complete: function(response) {
				var style,
					width,
					html = '';

				if (response != "success") {
					$('.message').slideUp(function() {
						$(this)
							.html('There was a problem with the image you uploaded')
							.css({
								color: '#9c0006',
								background: '#ffc7ce',
								borderColor: '#9c0006'
							})
							.slideDown();
					});
				} else {

					// success reload the detail page

					window.location.href = "detail.php?picID=<?= $picID ?>&parentPic=<?= $parentPic ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>&category=<?= $catID ?>&type=<?= $type ?>fromModule=<?= $fromModule ?>&pageModule=<?= $pageModule ?>&moduleid=<?= $fromModuleId ?>&status=main_image_has_been_updated";

				}
			}
		});

		// update pdf

		$('#img form[name="editDownload"]').iframePostForm({
			json: false,
			post: function() {

				var message;

				if (!$('.message').length) {
					$('#img').before('<div class="message" style="display:none; padding:10px; text-align:center" />');
				}


				if ($('input[name="pdfFile"]').val().length) {
					$('.message')
						.html('Uploading file&hellip;')
						.css({
							width: '300px',
							color: '#000',
							background: '#ededed',
							border: '1px solid #e7e7e6'
						})
						.slideDown();
				} else {
					$('.message')
						.html('Please select a file for uploading.')
						.css({
							width: '300px',
							color: '#9c0006',
							background: '#ffc7ce',
							border: '1px solid #9c0006'
						})
						.slideDown();

					return false;
				}
			},
			complete: function(response) {
				var style,
					width,
					html = '';

				if (response != "success") {
					$('.message').slideUp(function() {
						$(this)
							.html('There was a problem with the file you uploaded')
							.css({
								color: '#9c0006',
								background: '#ffc7ce',
								borderColor: '#9c0006'
							})
							.slideDown();
					});
				} else {

					// success reload the detail page

					window.location.href = "detail.php?picID=<?= $picID ?>&parentPic=<?= $parentPic ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>&category=<?= $catID ?>&type=<?= $type ?>fromModule=<?= $fromModule ?>&pageModule=<?= $pageModule ?>&moduleid=<?= $fromModuleId ?>&status=pdf_has_been_updated";

				}
			}
		});

		// update poster image

		$('#img form[name="editPoster"]').iframePostForm({
			json: false,
			post: function() {
				var message;

				if (!$('.message').length) {
					$('#img').before('<div class="message" style="display:none; padding:10px; text-align:center" />');
				}


				if ($('input[name="posterImg"]').val().length) {
					$('.message')
						.html('Uploading file&hellip;')
						.css({
							width: '300px',
							color: '#000',
							background: '#ededed',
							border: '1px solid #e7e7e6'
						})
						.slideDown();
				} else {
					$('.message')
						.html('Please select an image for uploading.')
						.css({
							width: '300px',
							color: '#9c0006',
							background: '#ffc7ce',
							border: '1px solid #9c0006'
						})
						.slideDown();

					return false;
				}
			},
			complete: function(response) {
				var style,
					width,
					html = '';

				if (response != "success") {
					$('.message').slideUp(function() {
						$(this)
							.html('There was a problem with the image you uploaded')
							.css({
								color: '#9c0006',
								background: '#ffc7ce',
								borderColor: '#9c0006'
							})
							.slideDown();
					});
				} else {

					// success reload the detail page

					window.location.href = "detail.php?picID=<?= $picID ?>&parentPic=<?= $parentPic ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>&category=<?= $catID ?>&type=<?= $type ?>fromModule=<?= $fromModule ?>&pageModule=<?= $pageModule ?>&moduleid=<?= $fromModuleId ?>&status=poster_image_has_been_updated";

				}
			}
		});

		// update video file

		$('#videowrap form[name="editVideo"]').iframePostForm({
			json: false,
			post: function() {
				var message;

				if (!$('.message').length) {
					$('#videowrap').before('<div class="message" style="display:none; padding:10px; text-align:center" />');
				}


				if ($('input[name="video"]').val().length) {
					$('.message')
						.html('Uploading file&hellip;')
						.css({
							width: '300px',
							color: '#000',
							background: '#ededed',
							border: '1px solid #e7e7e6'
						})
						.slideDown();
				} else {
					$('.message')
						.html('Please select a video for uploading.')
						.css({
							width: '300px',
							color: '#9c0006',
							background: '#ffc7ce',
							border: '1px solid #9c0006'
						})
						.slideDown();

					return false;
				}
			},
			complete: function(response) {
				var style,
					width,
					html = '';

				if (response != "success") {
					$('.message').slideUp(function() {
						$(this)
							.html('There was a problem with the image you uploaded')
							.css({
								color: '#9c0006',
								background: '#ffc7ce',
								borderColor: '#9c0006'
							})
							.slideDown();
					});
				} else {

					// success reload the detail page

					window.location.href = "detail.php?picID=<?= $picID ?>&parentPic=<?= $parentPic ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>&category=<?= $catID ?>&type=<?= $type ?>fromModule=<?= $fromModule ?>&pageModule=<?= $pageModule ?>&moduleid=<?= $fromModuleId ?>&status=full_video_has_been_updated";

				}
			}
		});

		// update hover video file

		$('#videowrap form[name="editVideoHover"]').iframePostForm({
			json: false,
			post: function() {
				var message;

				if (!$('.message').length) {
					$('#videowrap').before('<div class="message" style="display:none; padding:10px; text-align:center" />');
				}


				if ($('input[name="videoHover"]').val().length || $('input[name="currentVideo"]').val().length) {
					$('.message')
						.html('Uploading file&hellip;')
						.css({
							width: '300px',
							color: '#000',
							background: '#ededed',
							border: '1px solid #e7e7e6'
						})
						.slideDown();
				} else {
					$('.message')
						.html('Please select a video for uploading.')
						.css({
							width: '300px',
							color: '#9c0006',
							background: '#ffc7ce',
							border: '1px solid #9c0006'
						})
						.slideDown();

					return false;
				}
			},
			complete: function(response) {
				var style,
					width,
					html = '';

				if (response != "success") {
					$('.message').slideUp(function() {
						$(this)
							.html('There was a problem with the image you uploaded')
							.css({
								color: '#9c0006',
								background: '#ffc7ce',
								borderColor: '#9c0006'
							})
							.slideDown();
					});
				} else {

					// success reload the detail page

					window.location.href = "detail.php?picID=<?= $picID ?>&parentPic=<?= $parentPic ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>&category=<?= $catID ?>&type=<?= $type ?>fromModule=<?= $fromModule ?>&pageModule=<?= $pageModule ?>&moduleid=<?= $fromModuleId ?>&status=autoplay_video_has_been_updated";

				}
			}
		});

		// HANDLE FORM SUBMISSION
		$(".updateAlt").click(function() {

			var module;
			if ($(this).hasClass('module')) {
				module = "yes";
			}

			var category = $("input#catID").val();
			var detailID = $("input#detailID").val();
			var moduleID = $(this).data('modid');

			var picIDArray = [];
			$('.moduleOpen .picID').each(function() {
				var picID = $(this).val();
				picIDArray.push(picID);
			});

			var imgArray = [];
			$('.moduleOpen .img').each(function() {
				var img = $(this).val();
				imgArray.push(img);
			});

			var titleArray = [];
			$('.moduleOpen .titleField').each(function() {
				var title = $(this).val();
				titleArray.push(title);
			});

			var captionsArray = [];
			$('.moduleOpen .captions').each(function() {
				var captions = $(this).val();
				captionsArray.push(captions);
			});

			var picURLArray = [];
			$('.moduleOpen .picURL').each(function() {
				var picURL = $(this).val();
				picURLArray.push(picURL);
			});

			var shortDescArray = [];
			$('.moduleOpen .shortDesc').each(function() {
				var shortDesc = $(this).val();
				shortDescArray.push(shortDesc);
			});

			var hoverArray = [];
			$('.moduleOpen .hover').each(function() {
				if ($(this).is(':checked')) {
					var hover = "yes";
				} else {
					var hover = "";
				}
				hoverArray.push(hover);
			});

			var moveCatArray = [];
			$('.moduleOpen .moveCat').each(function() {
				var moveCat = $(this).val();
				moveCatArray.push(moveCat);
			});

			var copyCatArray = [];
			$('.moduleOpen .copyCat').each(function() {
				var copyCat = $(this).val();
				copyCatArray.push(copyCat);
			});

			var deleteArray = [];
			$('.moduleOpen .deleteCheck').each(function() {
				if ($(this).is(':checked')) {
					var deleteThis = "yes";
				} else {
					var deleteThis = "no";
				}
				deleteArray.push(deleteThis);
			});

			var rowBreakArray = [];
			$('.moduleOpen .rowBreak').each(function() {
				if ($(this).is(':checked')) {
					var rowBreak = "yes";
				} else {
					var rowBreak = "";
				}
				rowBreakArray.push(rowBreak);
			});

			var imgSizeArray = [];
			$('.moduleOpen .imgSize').each(function() {
				var imgSize = $(this).val();
				imgSizeArray.push(imgSize);
			});

			var data = {
				"category": category,
				"detailID": detailID,
				"moduleID": moduleID,
				"picID": picIDArray,
				"img": imgArray,
				"title": titleArray,
				"captions": captionsArray,
				"picURL": picURLArray,
				"shortDesc": shortDescArray,
				"hover": hoverArray,
				"module": module,
				"moveCat": moveCatArray,
				"copyCat": copyCatArray,
				"deleteArray": deleteArray,
				"rowBreakArray": rowBreakArray,
				"imgSizeArray": imgSizeArray
			};
			var data_encoded = JSON.stringify(data);

			$.ajax({
				type: "POST",
				url: "updateImages.php",
				data: {
					"myContent": data_encoded
				},
				success: function(response) {
					//window.location.href = response;
					window.location.href = "detail.php?picID=<?= $picID ?>&parentPic=<?= $parentPic ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>&category=<?= $catID ?>&type=<?= $type ?>fromModule=<?= $fromModule ?>&pageModule=<?= $pageModule ?>&moduleid=<?= $fromModuleId ?>&status=images_have_been_updated";
				},
				error: function() {
					alert('failure');
				}
			});
			return false;

		});

		$(".main .update, .updateDetails").click(function() {

			var ed = tinymce.get('myText'); // get editor instance
			var myText = ed.save(); // retrieve the content save to variable

			var ed2 = tinymce.get('myText2'); // get editor instance
			var myText2 = ed2.save(); // retrieve the content save to variable

			/*
			var ed3 = tinymce.get('title'); // get editor instance
			var title = ed3.save(); // retrieve the content save to variable
			*/

			var catID = $("input#catID").val();
			var picID = $("input#picID").val();
			var weight = $("input#weight").val();
			var caption = $("input#caption").val();
			var title = $("input#title").val();
			var videoFile = $("input#videoFile").val();
			var price = $("input#price").val();
			var wPrice = $("input#wPrice").val();
			var salesPrice = $("input#salesPrice").val();
			var featuredQty = $("input#featuredQty").val();
			var soldOut = $("input[name=soldOut]:radio:checked").val();

			var bgColor = $("input#colorpicker").val();
			var customSlug = $("#customSlug").val();

			var data = {
				"content": myText,
				"content2": myText2,
				"catID": catID,
				"picID": picID,
				"weight": weight,
				"title": title,
				"caption": caption,
				"price": price,
				"wPrice": wPrice,
				"salesPrice": salesPrice,
				"featuredQty": featuredQty,
				"soldOut": soldOut,
				"videoFile": videoFile,
				"bgColor": bgColor,
				"customSlug": customSlug
			};
			var data_encoded = JSON.stringify(data);

			$.ajax({
				type: "POST",
				url: "updateDetail.php",
				data: {
					"myContent": data_encoded
				},
				success: function(response) {
					//$('.theResponse').html(response);
					window.location.href = "detail.php?picID=<?= $picID ?>&parentPic=<?= $parentPic ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>&category=<?= $catID ?>&type=<?= $type ?>fromModule=<?= $fromModule ?>&pageModule=<?= $pageModule ?>&moduleid=<?= $fromModuleId ?>&status=item_has_been_updated";
				},
				error: function() {
					alert('failure');
				}
			});
			return false;

		});

		// add additional videos
		$('.addVideosLink').click(function() {

			$('#addVideos').slideToggle();
			return false;

		});

		// update video embed code

		$(".updateVideo").click(function() {

			var catID = $("input#catID").val();
			var picID = $("input#picID").val();
			var embedCode0 = $("#embedCode0").val();
			var embedCode1 = $("#embedCode1").val();
			var embedCode2 = $("#embedCode2").val();
			var embedCode3 = $("#embedCode3").val();
			var embedCode4 = $("#embedCode4").val();
			var embedCode5 = $("#embedCode5").val();

			var data = {
				"catID": catID,
				"picID": picID,
				"embedCode0": embedCode0,
				"embedCode1": embedCode1,
				"embedCode2": embedCode2,
				"embedCode3": embedCode3,
				"embedCode4": embedCode4,
				"embedCode5": embedCode5
			};
			var data_encoded = JSON.stringify(data);

			//alert(data_encoded);
			//return false;

			$.ajax({
				type: "POST",
				url: "updateVideo.php",
				data: {
					"myContent": data_encoded
				},
				success: function(response) {
					//window.location.href = response;
					window.location.href = "detail.php?picID=<?= $picID ?>&parentPic=<?= $parentPic ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>&category=<?= $catID ?>&type=<?= $type ?>fromModule=<?= $fromModule ?>&pageModule=<?= $pageModule ?>&moduleid=<?= $fromModuleId ?>&status=video_has_been_updated";
				},
				error: function() {
					alert('failure');
				}
			});
			return false;

		});

		// delete existing mp4 file

		$(".deletemp4").click(function() {

			var catID = $("input#catID").val();
			var picID = $("input#picID").val();

			var data = {
				"picID": picID,
				"catID": catID
			};
			var data_encoded = JSON.stringify(data);

			$.ajax({
				type: "POST",
				url: "delete_image_video.php",
				data: {
					"myContent": data_encoded
				},
				success: function(response) {
					window.location.href = "detail.php?picID=<?= $picID ?>&parentPic=<?= $parentPic ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>&category=<?= $catID ?>&type=<?= $type ?>fromModule=<?= $fromModule ?>&pageModule=<?= $pageModule ?>&moduleid=<?= $fromModuleId ?>&status=video_has_been_deleted";
				},
				error: function() {
					alert('failure');
				}
			});
			return false;

		});

		// delete existing mp4 file

		$(".deleteImageVideo").click(function(e) {

			e.preventDefault();

			var picID = $(this).data('picid');

			var data = {
				"picID": picID
			};
			var data_encoded = JSON.stringify(data);

			$.ajax({
				type: "POST",
				url: "delete_image_video.php",
				data: {
					"myContent": data_encoded
				},
				success: function(response) {
					window.location.href = "detail.php?picID=<?= $picID ?>&parentPic=<?= $parentPic ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>&category=<?= $catID ?>&type=<?= $type ?>fromModule=<?= $fromModule ?>&pageModule=<?= $pageModule ?>&moduleid=<?= $fromModuleId ?>&status=video_has_been_deleted";
				},
				error: function() {
					alert('failure');
				}
			});
			return false;

		});

		$(".deleteImageHoverVideo").click(function(e) {

			e.preventDefault();

			var picID = $(this).data('picid');

			var data = {
				"picID": picID
			};
			var data_encoded = JSON.stringify(data);

			$.ajax({
				type: "POST",
				url: "delete_image_hover_video.php",
				data: {
					"myContent": data_encoded
				},
				success: function(response) {
					window.location.href = "detail.php?picID=<?= $picID ?>&parentPic=<?= $parentPic ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>&category=<?= $catID ?>&type=<?= $type ?>fromModule=<?= $fromModule ?>&pageModule=<?= $pageModule ?>&moduleid=<?= $fromModuleId ?>&status=video_has_been_deleted";
				},
				error: function() {
					alert('failure');
				}
			});
			return false;

		});

		$(".deleteImageHoverVideoMobile").click(function(e) {

			e.preventDefault();

			var picID = $(this).data('picid');

			var data = {
				"picID": picID
			};
			var data_encoded = JSON.stringify(data);

			$.ajax({
				type: "POST",
				url: "delete_image_hover_video_mobile.php",
				data: {
					"myContent": data_encoded
				},
				success: function(response) {
					window.location.href = "detail.php?picID=<?= $picID ?>&parentPic=<?= $parentPic ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>&category=<?= $catID ?>&type=<?= $type ?>fromModule=<?= $fromModule ?>&pageModule=<?= $pageModule ?>&moduleid=<?= $fromModuleId ?>&status=video_has_been_deleted";
				},
				error: function() {
					alert('failure');
				}
			});
			return false;

		});

		// update tags

		$(".updateTags").click(function() {

			var catID = $("input#catID").val();
			var picID = $("input#picID").val();
			var type = $("input#type").val();

			var tagArray = [];
			$('.tag').each(function() {
				if ($(this).is(':checked')) {
					var theTag = $(this).val();
					tagArray.push(theTag);
				}
			});

			var data = {
				"catID": catID,
				"picID": picID,
				"tags": tagArray,
				"type": type
			};
			var data_encoded = JSON.stringify(data);

			$.ajax({
				type: "POST",
				url: "updateTags.php",
				data: {
					"myContent": data_encoded
				},
				success: function(response) {
					//window.location.href = response;
					window.location.href = "detail.php?picID=<?= $picID ?>&parentPic=<?= $parentPic ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>&category=<?= $catID ?>&type=<?= $type ?>fromModule=<?= $fromModule ?>&pageModule=<?= $pageModule ?>&moduleid=<?= $fromModuleId ?>&status=tags_have_been_updated";
				},
				error: function() {
					alert('failure');
				}
			});
			return false;

		});

		$(".picHolder").hoverIntent(
			function() {
				$(this).children(".picInfo").fadeTo(200, 1);
				$(this).children(".picCover").fadeTo(200, .5);
			},
			function() {
				$(this).children(".picInfo").fadeTo(200, 0);
				$(this).children(".picCover").fadeTo(200, 0);
			}
		);

		// HANDLE SAVING OPTIONS
		$(".saveOptions").click(function() {

			var category = $("input#category").val();
			var picID = $("input#picID").val();

			var optionIDArray = [];
			$('.optionID').each(function() {
				var opID = $(this).val();
				optionIDArray.push(opID);
			});

			// need to create the name and option arrays based on group id

			var nameArray = [];
			var sortByArray = [];
			var optionArray = [];

			$.each(optionIDArray, function() {

				var arrayName = this + "_nameArray";
				arrayName = [];
				$('.' + this + '_optionName').each(function() {
					var opID = $(this).val();
					arrayName.push(opID);
				});

				nameArray.push(arrayName);

				var arraySort = this + "_sortArray";
				arraySort = [];
				$('.' + this + '_sortBy').each(function() {
					var sort = $(this).val();
					arraySort.push(sort);
				});

				sortByArray.push(arraySort);

				var arrayOptions = this + "_optionsArray";
				arrayOptions = [];
				$('.' + this + '_option').each(function() {
					var option = $(this).val();
					arrayOptions.push(option);
				});

				optionArray.push(arrayOptions);


			});

			var data = {
				"category": category,
				"optionID": optionIDArray,
				"picID": picID,
				"sortBy": sortByArray,
				"name": nameArray,
				"option": optionArray
			};
			var data_encoded = JSON.stringify(data);

			$.ajax({
				type: "POST",
				url: "updateOptions.php",
				data: {
					"myContent": data_encoded
				},
				success: function(response) {
					$(".alertDivOptions").show();
					$('.alertDivOptions').html(response);
					$(".alertDivOptions").fadeOut(3000);
				},
				error: function() {
					alert('failure');
				}
			});
			return false;

		});

		$(document).ready(function() {

			<?php if ($modedit != "") { ?>
				$("#<?= $modedit ?>").addClass('moduleOpen');
				$("#<?= $modedit ?>").find('.moduleEdit').show();

				$('#contentArea').animate({
					scrollTop: $("#<?= $modedit ?>").offset().top
				}, 500);

			<?php } ?>

			/* JS OVERRIDES TO SHOW CORRECT DEFAULT MENU */

			$("#contentTopMenu").css('display', 'block');
			$("#catList").css('display', 'block');

			// modules functions
			$('.moduleAdd').click(function() {

				$('#addModule').slideToggle();

			});

			$('#modules li .moduleLabel').click(function() {

				if ($(this).parent().hasClass('moduleOpen')) {
					$(this).parent().removeClass('moduleOpen');
					$(this).parent().find('.moduleEdit').hide();
				} else {
					$(this).parent().addClass('moduleOpen');
					$(this).parent().find('.moduleEdit').show();

				}

			});

			// save new module
			$(".createModule").click(function() {

				var title = $("input#moduleTitle").val();
				var picID = $("input#picID").val();
				var catID = $("input#catID").val();
				var pageType = $("#moduleLayout option:selected").val();

				var data = {
					"picID": picID,
					"catID": catID,
					"title": title,
					"pageType": pageType
				};
				var data_encoded = JSON.stringify(data);

				$.ajax({
					type: "POST",
					url: "createModule.php",
					data: {
						"myContent": data_encoded
					},
					success: function(response) {
						var thePage = "detail.php?picID=<?= $picID ?>&category=<?= $catID ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>&type=<?= $type ?>&modedit=recordsArray_" + response;
						window.location.href = thePage;
					},
					error: function() {
						alert('failure');
					}
				});
				return false;

			});

			$("#colorpicker").spectrum({
				color: '<?= $mainPic['bgColor'] ?>',
				showInput: true,
				showAlpha: false,
				preferredFormat: "name"
			});

			$(".delete").colorbox({
				width: "402px",
				height: "227px",
				iframe: true,
				opacity: ".95"
			});

		});
	</script>

</body>

</html>