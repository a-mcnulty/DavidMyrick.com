<?php

session_start();
include 'includes/secure.php';
include '../includes/connect.php';
include 'includes/functions.php';


$catID = $_GET['category'];
$theCat = catDetails($catID);
if (isset($_GET['parentID'])) {
	$parentID = $_GET['parentID'];
}
$theParent = catDetails($parentID);
if (isset($_GET['parentParentID'])) {
	$parentParentID = $_GET['parentParentID'];
}
$tags = getTags("tags");

if (isset($_GET['show']) and $_GET['show'] === "all") {
	$limit = 5000;
} else {
	$limit = 5000;
}

/*
	echo $parentID;
	echo $theParent['pageType'];
	exit;
	*/

$result2 = getImages($catID, $theCat['sorter']);

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

		<div id="pageContent" class="manageimages">

			<p><span class="head uploaderLink"><?php if ($parentID == 0) { ?><?= $theCat['name'] ?><?php } else { ?><?= $theCat['subName'] ?><?php } ?></span> (<a href="/<?= $theCat['slug'] ?>" target="_blank">VIEW PAGE</a>)</p>

			<div id="line" style="width: 99%; margin-bottom: 2px;"></div>
			<div id="line" style="width: 99%;"></div><br />

			<input type="hidden" id="catID" name="catID" value="<?= $catID ?>">
			<input type="hidden" id="parentID" name="parentID" value="<?= $parentID ?>">

			<?php include('metaEditor.php'); ?>

			<div class="uploadHolder" style="width: 60%; float: left;">
				<h3>ADD NEW ITEM(S)</h3>
				<div style="display: flex; flex-wrap: wrap; align-items: center; margin-bottom: 5px;">
					<label for="myfile" style="width: auto;">Upload File(s):</label><input id="files-upload" type="file" style="margin-bottom: 0;" multiple>
				</div>
				or select from <a href="#" class="open_media" data-type="category" data-id="<?= $catID ?>" data-source="manage_images.php?category=<?= $catID ?>&parentID=<?= $parentID ?>">Media Library</a>
				<p id="drop-area" style="margin-top: 10px;">
					<span class="drop-instructions">or Drop files here!</span>
					<!--<span class="drop-over">Drop files here!</span>-->
				</p>

				<ul id="file-list">
					<li class="no-items">(no files uploaded yet)</li>
				</ul>
			</div>

			<div class="uploadHolder" style="width: 35%; float: right; <?php if ($theParent['pageType'] === "artist") { ?>display: block;<?php } else { ?>display: none;<?php } ?>">
				<h3>CHOOSE CATEGORIES(S)</h3><br /><br />

				<form name="catTags" method="post" action="">
					<input type="hidden" name="catID" id="catID" value="<?= $catID ?>">

					<?php

					// need to have unique fields for each type of tag
					$catTags = $theCat['tags'];
					$tagArray = explode(",", $catTags);

					for ($count = 1; $tag = mysqli_fetch_array($tags); ++$count) {

					?>

						<?php if (in_array($tag['slug'], $tagArray)) { ?>
							<input type="checkbox" name="tag" class="tag" value="<?= $tag['id'] ?>" checked> <?= $tag['tag'] ?>&nbsp;&nbsp;
						<?php } else { ?>
							<input type="checkbox" name="tag" class="tag" value="<?= $tag['id'] ?>"> <?= $tag['tag'] ?>&nbsp;&nbsp;
						<?php } ?>

					<?php } ?>

					<br /><br /><button id="btn" class="updateCatTags" style="margin-bottom: 15px; float: left; margin-right: 15px;">Update Categories</button>

				</form>

			</div>

			<?php if (getOption("textEntries") === "yes" and $theCat['pageType'] === "pitches") { ?>
				<div style="width: 25%; float: right;">
					<b>ADD NEW TEXT ENTRY</b><br /><br />

					<form name="newEntry">
						<input type="text" name="title" id="title" value="title:" onfocus="if (this.value == 'title:') this.value='';" onblur="if (this.value == '') this.value='title:';"><br /><br />

						<input type="text" name="caption" id="caption" value="caption:" onfocus="if (this.value == 'caption:') this.value='';" onblur="if (this.value == '') this.value='caption:';"><br /><br />

						<button id="btn" class="addEntry" style="margin-bottom: 15px; float: left; margin-right: 15px;">Add Entry</button>

					</form>
				</div>
			<?php } ?>

			<div style="clear: both;"></div>

			<p style="width: 99%;">
				<span class="head">Arrange / Edit Photos (<?= mysqli_num_rows($result2) ?> Photos)</span> (Click and drag images to rearrange the order)

			<form name="sortOrder" action="updateCatSort.php" method="post">
				<input type="hidden" name="currentcat" value="<?= $catID ?>" />
				<input type="hidden" name="parentID" value="<?= $parentID ?>" />
				<input type="hidden" name="parentParentID" value="<?= $parentParentID ?>" />
				Sort By: <select name="thesort" onchange="this.form.submit()">
					<option value="filename" <?php if ($theCat['sorter'] === "filename") { ?>selected<?php } ?>>filename</option>
					<option value="drag" <?php if ($theCat['sorter'] === "drag") { ?>selected<?php } ?>>drag</option>
				</select>
			</form>

			</p>

			<div id="line" style="width: 99%; margin-bottom: 2px;"></div>
			<div id="line" style="width: 99%;"></div>

			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td align="left" valign="top">

						<div id="managePics">

							<form name="content" action="updateImages.php" method="post">
								<input type="hidden" id="category" name="category" value="<?= $catID ?>">
								<input type="hidden" id="parentID" name="parentID" value="<?= $parentID ?>">
								<input type="hidden" id="parentParentID" name="parentParentID" value="<?= $parentParentID ?>">

								<div class="toolbar">

									<div class="inner">

										<button id="btn" class="update">SAVE CHANGES</button>

										<ul class="views">
											<li><span class="quickLink" style="cursor: pointer;">QUICK VIEW</span></li>
											<li><span class="detailLink" style="cursor: pointer; opacity: .5;">DETAIL VIEW</span></li>
										</ul>

										<ul class="tools">
											<li class="divider"></li>
											<li><input type="checkbox" name="selectAll" id="checkAll" />SELECT ALL</li>
											<li class="divider"></li>
											<li class="counter"><a href="#" class="deleteAll">X DELETE SELECTED (<span class="count">0</span>)</a></li>
											<li class="divider"></li>
											<li>
												<span class="label">COPY TO:</span>
												<select name="copyCatAll[]" class="copyCatAll">
													<option value="" selected>--select--</option>

													<?php

													$myCats = catList();
													for ($catCount = 1; $cat = mysqli_fetch_array($myCats); ++$catCount) {

														$thisID = $cat['id'];

													?>

														<option value="<?= $cat['id'] ?>"><?= ucwords(strtolower($cat['name'])) ?></option>

														<?php

														$mySubs = subcatList($thisID);
														for ($subCount = 1; $subcat = mysqli_fetch_array($mySubs); ++$subCount) {

															$thisSubID = $subcat['id'];

														?>

															<option value="<?= $subcat['id'] ?>">&nbsp;&nbsp; - <?= ucwords(strtolower($subcat['subName'])) ?></option>

															<?php

															$mySubSubs = subcatList($thisSubID);
															for ($subsubCount = 1; $subsubcat = mysqli_fetch_array($mySubSubs); ++$subsubCount) {

															?>

																<option value="<?= $subsubcat['id'] ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - <?= strtolower($subsubcat['subName']) ?></option>

															<?php } ?>

														<?php } ?>

													<?php } ?>

												</select><br />
											</li>
											<li class="divider"></li>
											<li>
												<span class="label">MOVE TO:</span>
												<select name="moveCatAll[]" class="moveCatAll">
													<option value="<?= $catID ?>" selected>--select--</option>

													<?php

													$myCats = catList();
													for ($catCount = 1; $cat = mysqli_fetch_array($myCats); ++$catCount) {

														$thisID = $cat['id'];

													?>

														<option value="<?= $cat['id'] ?>"><?= ucwords(strtolower($cat['name'])) ?></option>

														<?php

														$mySubs = subcatList($thisID);
														for ($subCount = 1; $subcat = mysqli_fetch_array($mySubs); ++$subCount) {

															$thisSubID = $subcat['id'];

														?>

															<option value="<?= $subcat['id'] ?>">&nbsp;&nbsp; - <?= ucwords(strtolower($subcat['subName'])) ?></option>

															<?php

															$mySubSubs = subcatList($thisSubID);
															for ($subsubCount = 1; $subsubcat = mysqli_fetch_array($mySubSubs); ++$subsubCount) {

															?>

																<option value="<?= $subsubcat['id'] ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - <?= strtolower($subsubcat['subName']) ?></option>

															<?php } ?>

														<?php } ?>

													<?php } ?>

												</select><br />
											</li>
										</ul>

									</div>

								</div>

								<div id="alertDivPic" style="float: left;">

								</div>

								<div style="clear: both;"></div>

								<ul class="sortme">

									<?php

									for ($count = 1; $pic = mysqli_fetch_array($result2); ++$count) {

										$ext = pathinfo(getOption("physicalPath") . "/images/pics/" . $pic['img'], PATHINFO_EXTENSION);

										if (file_exists(getOption("physicalPath") . "/images/pics/500/" . $pic['img'])) {
											$theImg = getOption("imagePath") . "500/" . $pic['img'];
										} else {
											$theImg = getOption("imagePath") . $pic['img'];
										}

										if ($ext === "gif") {
											$theImg = getOption("imagePath") . $pic['img'];
										}

									?>

										<li class="picHolder" id="recordsArray_<?php echo $pic['id']; ?>">
											<?php if ($pic['img'] != "no-image.jpg") { ?>

												<img src="<?= $theImg ?>" border="0"><br />

											<?php } else { ?>
												<div style="width: 150px; height: 150px; position: absolute; top: 0px; left: 0px; text-align: center; border: 1px solid #ccc;">
													<div style="padding-top: 44%;"><?= $pic['title'] ?></div>
												</div>
											<?php } ?>
											<input type="hidden" class="picID" name="picID[]" value="<?= $pic['id'] ?>">
											<input type="hidden" class="img" name="img[]" value="<?= $pic['img'] ?>">
											<input type="hidden" name="copyCat[]" class="copyCat" value="" />
											<input type="hidden" name="moveCat[]" class="moveCat" value="<?= $catID ?>" />

											<div class="picCover" style="display: none;"></div>

											<div class="picInfo" style="display: none;">

												<?php if (getOption("title") === "yes") { ?>
													<input type="text" name="title[]" class="titleField" <?php if ($pic['title'] != "") { ?>value="<?= $pic['title'] ?>" <?php } else { ?>value="title:" <?php } ?> onfocus="if (this.value == 'title:') this.value='';" onblur="if (this.value == '') this.value='title:';"><br />
												<?php } ?>

												<?php if ($catID === "417" or $catID === "420") { ?>

													<?php if (getOption("caption") === "yes") { ?>
														<input type="text" name="captions[]" class="captions" <?php if ($pic['caption'] != "") { ?>value="<?= $pic['caption'] ?>" <?php } else { ?>value="press date:" <?php } ?> onfocus="if (this.value == 'press date:') this.value='';" onblur="if (this.value == '') this.value='press date:';"><br />
													<?php } ?>

												<?php } else { ?>

													<?php if (getOption("caption") === "yes") { ?>
														<input type="text" name="captions[]" class="captions" <?php if ($pic['caption'] != "") { ?>value="<?= $pic['caption'] ?>" <?php } else { ?>value="caption:" <?php } ?> onfocus="if (this.value == 'caption:') this.value='';" onblur="if (this.value == '') this.value='caption:';"><br />
													<?php } ?>

												<?php } ?>

												<?php if (getOption("picURL") === "yes") { ?>
													<input type="text" name="picURL[]" class="picURL" <?php if ($pic['picURL'] != "") { ?>value="<?= $pic['picURL'] ?>" <?php } else { ?>value="url:" <?php } ?> onfocus="if (this.value == 'url:') this.value='';" onblur="if (this.value == '') this.value='url:';"><br />
												<?php } ?>

												<?php if (getOption("shortDesc") === "yes") { ?>
													<?php if ($catID == 324) { ?>
														<textarea name="shortDesc[]" class="shortDesc" style="width: 170px; height: 15px; font-size: 11px; font-family: Helvetica, Arial;"><?php if ($pic['shortDesc'] != "") { ?><?= $pic['shortDesc'] ?><?php } else { ?>short description:<?php } ?></textarea><br />
													<?php } ?>
												<?php } ?>

												<?php /*

											<span class="smallGray">move to:</span>
											<select name="moveCat[]" class="moveCat" style="font-size: 10px; width: 122px;">
												<option value="<?=$catID?>" selected>--select--</option>

												<?php

													$myCats = catList();
													for ($catCount = 1; $cat = mysqli_fetch_array($myCats); ++$catCount) {

														$thisID = $cat['id'];

												?>

													<option value="<?=$cat['id']?>"><?=$cat['name']?></option>

													<?php

														$mySubs = subcatList($thisID);
														for ($subCount = 1; $subcat = mysqli_fetch_array($mySubs); ++$subCount) {

													?>

														<option value="<?=$subcat['id']?>" style="padding-left: 10px;"><?=$subcat['subName']?></option>

													<?php } ?>

												<?php } ?>

											</select><br />

											<span class="smallGray">copy to:</span>
											<select name="copyCat[]" class="copyCat" style="font-size: 10px; width: 122px;">
												<option value="">--select--</option>

												<?php

													$myCats = catList();
													for ($catCount = 1; $cat = mysqli_fetch_array($myCats); ++$catCount) {

														$thisID = $cat['id'];

												?>

													<option value="<?=$cat['id']?>"><?=$cat['name']?></option>

													<?php

														$mySubs = subcatList($thisID);
														for ($subCount = 1; $subcat = mysqli_fetch_array($mySubs); ++$subCount) {

													?>

														<option value="<?=$subcat['id']?>" style="padding-left: 10px;"><?=$subcat['subName']?></option>

													<?php } ?>

												<?php } ?>

											</select><br />
											
											*/ ?>


												<?php if ($theCat['pageType'] === "grid - masonry") { ?>

													<span class="smallGray">image size:</span>
													<select name="imgSize[]" class="imgSize" style="font-size: 10px; width: 122px;">
														<option value="" selected>--select--</option>

														<option value="large" <?php if ($pic['catSize'] === "large") { ?>selected<?php } ?>>large</option>

													</select><br />

												<?php } ?>

												<?php if ($theCat['pageType'] === "grid - justified") { ?>

													<input type="checkbox" name="rowBreak[]" class="rowBreak" value="no" style="width: 20px;" <?php if ($pic['catBreak'] === "yes") { ?>checked<?php } ?>> <span style="font-size: 10px; color: #fff; position: relative; top: -3px;">end row</span><br />

												<?php } ?>

											</div>

											<div class="imagetools">
												<a href="detail.php?picID=<?= $pic['id'] ?>&category=<?= $catID ?>&imgName=<?= $pic['img'] ?>">

													<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
														<path d="M63.8,18L46.1,0.2l-8.3,8.3L0,46.2V64h17.8l39.1-39l0,0L63.8,18z M58.2,18L54,22.2L41.9,10l4.1-4.2L58.2,18z M4,51.9l8.3,8.1H4V51.9z M17,59.1l-12.3-12l34.3-34.2L51.2,25L17,59.1z" />
													</svg>
													<span class="label">edit</span>

												</a>
												<a href="delete_image.php?id=<?= $pic['id'] ?>&category=<?= $catID ?>&imgName=<?= $pic['img'] ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>" class="delete">
													<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
														<g>
															<path d="M54,8H42V4c0-2.2-1.8-4-4-4H26c-2.2,0-4,1.8-4,4v4H10c-2.2,0-4,1.8-4,4v8h4.1l2.6,38.4c0.2,3.1,2.8,5.6,6,5.6h26.5c3.1,0,5.8-2.5,6-5.6L53.9,20H58v-8C58,9.8,56.2,8,54,8z M26,4h12v4H26V4z M47.3,58.1c-0.1,1-0.9,1.9-2,1.9H18.7c-1,0-1.9-0.8-2-1.9L14.1,20h35.7L47.3,58.1z M54,16H10v-4h44V16z" />
															<rect x="9" y="37" transform="matrix(7.669654e-02 0.9971 -0.9971 7.669654e-02 59.1978 14.0736)" width="26.1" height="4" />
															<rect x="30" y="26" width="4" height="26" />
															<rect x="40" y="26" transform="matrix(0.9971 7.665917e-02 -7.665917e-02 0.9971 3.1133 -3.1049)" width="4" height="26.1" />
														</g>
													</svg>
													<span class="label">delete</span>
												</a>
												<span><input type="checkbox" name="selectCheck" class="selectCheck" data-id="<?= $pic['id'] ?>" value="yes"></span>
												<input type="checkbox" name="deleteCheck" data-id="<?= $pic['id'] ?>" class="deleteCheck" data-id="<?= $pic['id'] ?>" value="yes" style="display: none;">
											</div>

										</li>

									<?php

									}

									?>

								</ul>

						</div>

						<div id="clear" style="height: 10px;"></div>

						</form>

						<!--
					<?php if (isset($_GET['show']) and $_GET['show'] != "all") { ?>
						<br /><br /><a href="#" id="manage_images.php?category=<?= $catID ?>&show=all" class="ajax" onclick="return false">View All</a><br /><br />
					<?php } ?>
					-->

					</td>
				</tr>
			</table>

		</div>

	</div>

	<?php include('includes/overlay.php'); ?>

</body>

</html>

<script type="text/javascript">
	// HTML 5 FILE UPLOAD
	(function() {
		var filesUpload = document.getElementById("files-upload"),
			dropArea = document.getElementById("drop-area"),
			fileList = document.getElementById("file-list");

		function uploadFile(file, currentfile, totalfiles) {
			var li = document.createElement("li"),
				div = document.createElement("div"),
				img,
				progressBarContainer = document.createElement("div"),
				progressBar = document.createElement("div"),
				reader,
				xhr,
				fileInfo;

			li.appendChild(div);

			progressBarContainer.className = "progress-bar-container";
			progressBar.className = "progress-bar";
			progressBarContainer.appendChild(progressBar);
			li.appendChild(progressBarContainer);

			/*
				If the file is an image and the web browser supports FileReader,
				present a preview in the file list
			*/
			if (typeof FileReader !== "undefined" && (/image/i).test(file.type)) {
				img = document.createElement("img");
				li.appendChild(img);
				reader = new FileReader();
				reader.onload = (function(theImg) {
					return function(evt) {
						theImg.src = evt.target.result;
					};
				}(img));
				reader.readAsDataURL(file);
			}

			// Uploading - for Firefox, Google Chrome and Safari
			xhr = new XMLHttpRequest();

			// Update progress bar
			xhr.upload.addEventListener("progress", function(evt) {
				if (evt.lengthComputable) {
					progressBar.style.width = (evt.loaded / evt.total) * 100 + "%";
				} else {
					// No data to calculate on
				}
			}, false);

			// File uploaded
			xhr.addEventListener("load", function() {
				progressBarContainer.className += " uploaded";
				progressBar.innerHTML = "Uploaded!";

				// count how many items have uploaded class
				var doneItems = $('.uploaded').length;

				if (doneItems == totalfiles) {

					var thePage = "manage_images.php?category=<?= $catID ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>";
					window.location.href = thePage;
					//console.log('done');

				}

			}, false);

			xhr.open("post", "upload_file.php", true);

			// Set appropriate headers
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.setRequestHeader("X-File-Name", file.name);
			xhr.setRequestHeader("X-File-Size", file.size);
			xhr.setRequestHeader("X-File-Type", file.type);
			xhr.setRequestHeader("X-File-Cat", <?= $catID ?>);
			xhr.setRequestHeader("X-File-Picid", '');
			xhr.setRequestHeader("X-File-Moduleid", '');

			// Send the file (doh)
			xhr.send(file);

			// Present file info and append it to the list of files
			//fileInfo = "<div><strong>Name:</strong> " + file.name + "</div>";
			fileInfo = "<div><strong>Size:</strong> " + parseInt(file.size / 1024, 10) + " kb</div>";
			fileInfo += "<div><strong>Type:</strong> " + file.type + "</div>";
			div.innerHTML = fileInfo;

			fileList.appendChild(li);
		}

		function traverseFiles(files) {
			if (typeof files !== "undefined") {
				for (var i = 0, l = files.length; i < l; i++) {
					uploadFile(files[i], i, l);
				}



			} else {
				fileList.innerHTML = "No support for the File API in this web browser";
			}
		}

		filesUpload.addEventListener("change", function() {
			traverseFiles(this.files);
		}, false);

		dropArea.addEventListener("dragleave", function(evt) {
			var target = evt.target;

			if (target && target === dropArea) {
				this.className = "";
			}
			evt.preventDefault();
			evt.stopPropagation();
		}, false);

		dropArea.addEventListener("dragenter", function(evt) {
			this.className = "over";
			evt.preventDefault();
			evt.stopPropagation();
		}, false);

		dropArea.addEventListener("dragover", function(evt) {
			evt.preventDefault();
			evt.stopPropagation();
		}, false);

		dropArea.addEventListener("drop", function(evt) {
			traverseFiles(evt.dataTransfer.files);
			this.className = "";
			evt.preventDefault();
			evt.stopPropagation();
		}, false);
	})();

	// HANDLE FORM SUBMISSION
	$(".update").click(function() {

		var category = $("input#category").val();
		var parentID = $("input#parentID").val();
		var parentParentID = $("input#parentParentID").val();

		var picIDArray = [];
		$('.picID').each(function() {
			var picID = $(this).val();
			picIDArray.push(picID);
		});

		var imgArray = [];
		$('.img').each(function() {
			var img = $(this).val();
			imgArray.push(img);
		});

		var titleArray = [];
		$('.titleField').each(function() {
			var title = $(this).val();
			titleArray.push(title);
		});

		var captionsArray = [];
		$('.captions').each(function() {
			var captions = $(this).val();
			captionsArray.push(captions);
		});

		var picURLArray = [];
		$('.picURL').each(function() {
			var picURL = $(this).val();
			picURLArray.push(picURL);
		});

		var shortDescArray = [];
		$('.shortDesc').each(function() {
			var shortDesc = $(this).val();
			shortDescArray.push(shortDesc);
		});

		var priceArray = [];
		$('.price').each(function() {
			var price = $(this).val();
			priceArray.push(price);
		});

		var wpriceArray = [];
		$('.wPrice').each(function() {
			var wprice = $(this).val();
			wpriceArray.push(wprice);
		});

		var moveCatArray = [];
		$('.moveCat').each(function() {
			var moveCat = $(this).val();
			moveCatArray.push(moveCat);
		});

		var copyCatArray = [];
		$('.copyCat').each(function() {
			var copyCat = $(this).val();
			copyCatArray.push(copyCat);
		});

		var iconArray = [];
		$('.icon').each(function() {
			var icon = $(this).val();
			iconArray.push(icon);
		});

		var artistArray = [];
		$('.artist').each(function() {
			var artist = $(this).val();
			artistArray.push(artist);
		});

		var showArray = [];
		$('.show').each(function() {
			var show = $(this).val();
			showArray.push(show);
		});

		var deleteArray = [];
		$('.deleteCheck').each(function() {
			if ($(this).is(':checked')) {
				var deleteThis = "yes";
			} else {
				var deleteThis = "no";
			}
			deleteArray.push(deleteThis);
		});

		var imgSizeArray = [];
		$('.imgSize').each(function() {
			var imgSize = $(this).val();
			imgSizeArray.push(imgSize);
		});

		var rowBreakArray = [];
		$('.rowBreak').each(function() {
			if ($(this).is(':checked')) {
				var rowBreak = "yes";
			} else {
				var rowBreak = "";
			}
			rowBreakArray.push(rowBreak);
		});

		var data = {
			"category": category,
			"parentID": parentID,
			"parentParentID": parentParentID,
			"picID": picIDArray,
			"img": imgArray,
			"title": titleArray,
			"captions": captionsArray,
			"picURL": picURLArray,
			"shortDesc": shortDescArray,
			"price": priceArray,
			"wprice": wpriceArray,
			"moveCat": moveCatArray,
			"copyCat": copyCatArray,
			"artists": artistArray,
			"show": showArray,
			"deleteArray": deleteArray,
			"imgSizeArray": imgSizeArray,
			"rowBreakArray": rowBreakArray
		};
		var data_encoded = JSON.stringify(data);

		$.ajax({
			type: "POST",
			url: "updateImages.php",
			data: {
				"myContent": data_encoded
			},
			success: function(response) {
				window.location.href = response;
				//console.log(response);
			},
			error: function() {
				alert('failure');
			}
		});
		return false;

	});

	// update category tags

	$(".updateCatTags").click(function(e) {

		e.preventDefault();

		var catID = $("input#catID").val();

		var tagArray = [];
		$('.tag').each(function() {
			if ($(this).is(':checked')) {
				var theTag = $(this).val();
				tagArray.push(theTag);
			}
		});

		var data = {
			"catID": catID,
			"tags": tagArray
		};
		var data_encoded = JSON.stringify(data);

		$.ajax({
			type: "POST",
			url: "updateCatTags.php",
			data: {
				"myContent": data_encoded
			},
			success: function(response) {
				var thePage = "manage_images.php?category=<?= $catID ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>";
				window.location.href = thePage;
			},
			error: function() {
				alert('failure');
			}
		});
		return false;

	});

	/* 2017 TOOLBAR ADDITIONS */

	$(".selectCheck").click(function() {

		var total = $('input[name="selectCheck"]:checked').length;
		$('.toolbar .count').html(total);

		if (total > 0) {
			$('.toolbar .counter').addClass('on');
		} else {
			$('.toolbar .counter').removeClass('on');
		}

	});

	$('.toolbar .copyCatAll').change(function() {
		var copyid = $(this).val();

		$('.selectCheck').each(function() {

			if ($(this).is(":checked")) {

				var picid = $(this).data('id');
				$('#recordsArray_' + picid).find('.copyCat').val(copyid);

			}

		});

	});

	$('.toolbar .moveCatAll').change(function() {
		var moveid = $(this).val();

		$('.selectCheck').each(function() {

			if ($(this).is(":checked")) {

				var picid = $(this).data('id');
				$('#recordsArray_' + picid).find('.moveCat').val(moveid);

			}

		});

	});

	$('.toolbar .deleteAll').click(function(e) {

		e.preventDefault();

		$('.selectCheck').each(function() {

			var picid = $(this).data('id');
			if ($(this).is(":checked")) {

				$('.deleteCheck[data-id="' + picid + '"]').prop('checked', true);

			} else {

				$('.deleteCheck[data-id="' + picid + '"]').prop('checked', false);

			}

		});

		$('.toolbar #btn').click();

	});

	$("#checkAll").click(function() {
		$(".selectCheck").prop('checked', $(this).prop('checked'));

		var total = $('input[name="selectCheck"]:checked').length;
		$('.toolbar .count').html(total);

		if (total > 0) {
			$('.toolbar .counter').addClass('on');
		} else {
			$('.toolbar .counter').removeClass('on');
		}

	});

	// HANDLE TEXT ENTRY
	$(".addEntry").click(function() {

		var catID = $("input#catID").val();
		var parentID = $("input#parentID").val();
		var title = $("input#title").val();
		var caption = $("input#caption").val();

		var data = {
			"catID": catID,
			"parentID": parentID,
			"title": title,
			"caption": caption
		};
		var data_encoded = JSON.stringify(data);

		$.ajax({
			type: "POST",
			url: "addTextEntry.php",
			data: {
				"myContent": data_encoded
			},
			success: function(response) {

				window.location.href = response;

			},
			error: function() {
				alert('failure');
			}
		});
		return false;

	});

	$(".detailLink").click(function() {

		$(this).fadeTo(200, 1);
		$('.quickLink').fadeTo(200, .5);

		$(".picInfo").fadeTo(200, 1);
		$(".picCover").fadeTo(200, .5);

		$('#managePics ul.sortme').unbind().removeData();
		$('#managePics ul.sortme li').removeClass('selected');

	});

	$(".quickLink").click(function() {

		$(this).fadeTo(200, 1);
		$('.detailLink').fadeTo(200, .5);

		$(".picInfo").fadeOut(200);
		$(".picCover").fadeOut(200);

		addMultiSort();

	});

	/*
	$(".picHolder").hoverIntent(
	  function () {
		$(this).children(".picInfo").fadeTo(200, 1);
		$(this).children(".picCover").fadeTo(200, .5);
	  },
	  function () {
		$(this).children(".picInfo").fadeTo(200, 0);
		$(this).children(".picCover").fadeTo(200, 0);
	  }
	);
	*/

	function addMultiSort() {

		$('#managePics ul.sortme').multisortable({
			items: "li",
			selectedClass: "selected",
			helper: "original",
			opacity: 0.6,
			click: function(e) {
				console.log("I'm selected.");
			},
			stop: function(e) {
				updatePostOrder();
			}
		});

	}

	addMultiSort();

	function updatePostOrder() {

		var order = $('#managePics ul.sortme').sortable("serialize") + '&action=updateRecordsListings&catID=<?= $catID ?>';
		$.post("updateImgOrder.php", order, function(theResponse) {

		});

		setTimeout(function() {
			$("#managePics ul.sortme li").removeClass('selected');
		}, 500);
	}

	/*

	$(function() {
		$("#managePics ul.sortme").sortable({ opacity: 0.6, cursor: 'move', update: function() {
			var order = $(this).sortable("serialize") + '&action=updateRecordsListings&catID=<?= $catID ?>';
			$.post("updateImgOrder.php", order, function(theResponse){

			});
		}
		});
	});

	*/

	$(document).ready(function() {

		$('#contentArea').scroll(function(event) {
			var scroll = $('#contentArea').scrollTop();
			console.log(scroll);
			if (scroll > 445) {
				$('body').addClass('fixed');
			} else {
				$('body').removeClass('fixed');
			}
		});

		/* JS OVERRIDES TO SHOW CORRECT DEFAULT MENU */

		$("#contentTopMenu").css('display', 'block');
		$("#catList").css('display', 'block');

		$(".uploaderLink").click(function() {

			$(".uploadHolder").slideToggle();

		});

		$("A.ajax").click(function() {

			var thePage = $(this).attr("id");

			loadPage(thePage);

		});

		$(".delete").colorbox({
			width: "402px",
			height: "227px",
			iframe: true,
			opacity: ".95"
		});

	});
</script>