<?php

$modtext = getModule($module['id']);
$images = getModuleImages($module['id'], $module['sorter']);

$mediaLibrarySrc = "detail.php?picID=" . $picID . "&category=" . $catID . "&parentID=" . $parentID;
if ($modType === "category") {
	$mediaLibrarySrc = "manage_modules.php?category=" . $catID . "&parentID=" . $parentID . "&parentParentID=" . $parentParentID;
}

?>

<div class="moduleLabel">
	<?php if ($modType != "detail") { ?>
		<span class="icon">
			<?= $module['icon'] ?>
		</span>
	<?php } ?>
	<span class="label" style="font-size: 16px; font-weight: normal; color: #3e4e69;"><?= $module['title'] ?></span> <?php if ($module['status'] === "preview") { ?><span class="smallRed">(hidden)</span><?php } ?>
</div>
<div style="float: right;">
	<div class="moduleHandle">
		<span></span>
		<span></span>
		<span></span>
	</div>
	<a href="delete_module.php?id=<?= $module['id'] ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>&picID=<?= $picID ?>&category=<?= $catID ?>&moduleName=<?= $module['title'] ?>&modType=<?= $modType ?>&confirm=" class="delete"><img src="images/icons/deleteCircle.png" class="moduleDelete"></a>
</div>
<div style="clear: both;"></div>

<div class="moduleEdit" style="display: none;">
	<!--<span class="label">TEXT</span>-->
	<div class="text">
		<form name="textEditor" method="post">
			<input type="hidden" id="moduleID<?= $module['id'] ?>" name="moduleID" value="<?= $module['id'] ?>">

			Title:<br />
			<input type="text" name="moduleName<?= $module['id'] ?>" id="moduleName<?= $module['id'] ?>" value="<?= $module['title'] ?>"><br /><br />

			Include In Page Menu:<br />
			<select name="moduleShowTitle<?= $module['id'] ?>" id="moduleShowTitle<?= $module['id'] ?>">
				<option value="yes" <?php if ($module['showTitle'] === "yes") { ?>selected<?php } ?>>yes</option>
				<option value="no" <?php if ($module['showTitle'] === "no" or $module['showTitle'] === "") { ?>selected<?php } ?>>no</option>
			</select><br /><br />

			Status:<br />
			<select name="moduleStatus<?= $module['id'] ?>" id="moduleStatus<?= $module['id'] ?>">
				<option id="live" value="live" <?php if ($module['status'] === "live" or $module['status'] === "") { ?>selected<?php } ?>>live</option>
				<option id="preview" value="preview" <?php if ($module['status'] === "preview") { ?>selected<?php } ?>>preview</option>
			</select><br /><br />

			Layout Type:<br />
			<?php mysqli_data_seek($allmods, 0); ?>
			<select name="moduleLayout<?= $module['id'] ?>" id="moduleLayout<?= $module['id'] ?>">
				<?php for ($count = 1; $mod = mysqli_fetch_array($allmods); ++$count) { ?>
					<option value="<?= $mod['layout'] ?>" <?php if ($module['pageType'] === $mod['layout']) { ?>selected<?php } ?>><?= $mod['layout'] ?></option>
				<?php } ?>
			</select><br /><br />

			<textarea name="moduleText<?= $module['id'] ?>" id="moduleText<?= $module['id'] ?>" class="moduleText<?= $module['id'] ?> mceEditor" style="width: 90%; height: 200px; <?php if ($module['pageType'] === "text" or $module['pageType'] === "textblock" or $module['pageType'] === "boxed link") { ?>display: flex;<?php } else { ?>display: none;<?php } ?>"><?= stripslashes($modtext['content']) ?></textarea><br />

			<button id="btn" class="textUpdate<?= $module['id'] ?>" style="float: left; margin-right: 5px;">Update Module</button>
			<div class="theResponse<?= $module['id'] ?>" style="margin-top: 3px; float: left;"></div>
			<div style="clear: both; height: 10px;"></div>
		</form>
	</div>

	<!--<span class="label">IMAGES</span>-->
	<div class="images" style="<?php if ($module['pageType'] != "text" and $module['pageType'] != "tag block") { ?>display: block; width: 100%;<?php } else { ?>display: none;" <?php } ?>">

		<div style="width: 100%; float: left;">

			<h3>ADD NEW ITEM(S)</h3>
			<div style="display: flex; flex-wrap: wrap; align-items: center; margin-bottom: 5px;">
				<label for="myfile" style="width: auto;">Upload File(s):</label><input id="files-upload<?= $module['id'] ?>" class="files-upload" type="file" style="margin-bottom: 0;" multiple>
			</div>
			or select from <a href="#" class="open_media" data-type="module" data-id="<?= $module['id'] ?>" data-source="<?= $mediaLibrarySrc ?>">Media Library</a>

			<div class="dropHolder" style="margin-top: 10px;">
				<p id="drop-area<?= $module['id'] ?>" class="drop-area">
					<span class="drop-instructions">or drag and drop files here</span>
					<span class="drop-over">Drop files here!</span>
				</p>
			</div>

			<ul id="file-list<?= $module['id'] ?>" class="file-list" style="float: left; width: 100%;">
				<li class="no-items">(no files uploaded yet)</li>
			</ul>

		</div>

		<div style="width: 20%; float: left; margin: 0 5%; display: none;">

			<h3>ADD VIDEO EMBED URL</h3><br /><br />

			<form name="addVideoEmbed" method="post" action="">
				<input type="text" name="videoURL" id="videoURL" />

				<br /><br /><button id="btn" class="addVideoEmbed">Add Video</button>

			</form>


		</div>

		<div style="clear: both;"></div>

		<div id="managePics" class="managePics<?= $module['id'] ?>">

			<form name="content" action="updateImages.php" method="post">
				<input type="hidden" id="category" name="category" value="<?= $catID ?>">
				<input type="hidden" id="detailID" name="detailID" value="<?= $picID ?>">
				<input type="hidden" id="module" name="module" value="yes">

				<button id="btn" data-modid="<?= $module['id'] ?>" class="updateAlt module" style="margin-bottom: 15px;">Save Changes</button>
				<ul class="sortme">

					<?php

					for ($count = 1; $pic = mysqli_fetch_array($images); ++$count) {

						$ext = pathinfo(getOption("physicalPath") . "/images/pics/" . $pic['img'], PATHINFO_EXTENSION);

						if (file_exists(getOption("physicalPath") . "/images/pics/500/" . $pic['img'])) {
							$theImg = getOption("imagePath") . "500/" . $pic['img'];
						} else {
							$theImg = getOption("imagePath") . $pic['img'];
						}

						if ($ext === "gif") {
							$theImg = getOption("imagePath") . $pic['img'];
						}

						if ($pic['imgSrc'] != "upload") {
							$theImg = $pic['img'];
						}

					?>

						<li class="picHolder" id="recordsArray_<?php echo $pic['id']; ?>">
							<img src="<?= $theImg ?>" border="0"><br />
							<input type="hidden" class="picID" name="picID[]" value="<?= $pic['id'] ?>">
							<input type="hidden" class="img" name="img[]" value="<?= $pic['img'] ?>">

							<div class="picCover" style="display: none;"></div>

							<div class="picInfo" style="display: none;">

								<?php if (getOption("title") === "yes") { ?>
									<input type="text" name="title[]" class="titleField" <?php if ($pic['title'] != "") { ?>value="<?= $pic['title'] ?>" <?php } else { ?>value="title:" <?php } ?> onfocus="if (this.value == 'title:') this.value='';" onblur="if (this.value == '') this.value='title:';"><br />
								<?php } ?>

								<?php if (getOption("caption") === "yes") { ?>
									<input type="text" name="captions[]" class="captions" <?php if ($pic['caption'] != "") { ?>value="<?= $pic['caption'] ?>" <?php } else { ?>value="caption:" <?php } ?> onfocus="if (this.value == 'caption:') this.value='';" onblur="if (this.value == '') this.value='caption:';"><br />
								<?php } ?>

								<?php if (getOption("picURL") === "yes") { ?>
									<input type="text" name="picURL[]" class="picURL" <?php if ($pic['picURL'] != "") { ?>value="<?= $pic['picURL'] ?>" <?php } else { ?>value="url:" <?php } ?> onfocus="if (this.value == 'url:') this.value='';" onblur="if (this.value == '') this.value='url:';"><br />
								<?php } ?>


								<?php if ($module['pageType'] === "grid - justified") { ?>

									<input type="checkbox" name="rowBreak[]" class="rowBreak" value="no" style="width: 20px;" <?php if ($pic['modRowBreak'] === "yes") { ?>checked<?php } ?>> <span style="font-size: 10px; color: #fff; position: relative; top: -3px;">end row</span><br />

									<input type="hidden" name="imgSize[]" class="imgSize" value="<?= $pic['imgSize'] ?>" />

								<?php } ?>

								<?php if ($module['pageType'] === "grid - loose" or $module['pageType'] === "grid") { ?>

									<span class="smallGray">image size:</span>
									<select name="imgSize[]" class="imgSize" style="font-size: 10px; width: 122px;">
										<option value="" selected>--select--</option>

										<option value="large" <?php if ($pic['imgSize'] === "large") { ?>selected<?php } ?>>large</option>

									</select><br />

									<input type="hidden" name="rowBreak[]" class="rowBreak" value="<?= $pic['modRowBreak'] ?>" />

								<?php } ?>

								<?php /*

								<input type="checkbox" name="rowBreak[]" class="rowBreak" value="no" style="width: 20px;" <?php if ($pic['rowBreak'] === "yes") { ?>checked<?php } ?>> <span style="font-size: 10px; color: #fff; position: relative; top: -3px;">end row</span><br />
								
								<?php if (getOption("title") === "yes") { ?>
									<input type="text" name="title[]" class="titleField" <?php if ($pic['title'] != "") { ?>value="<?=$pic['title']?>"<?php } else { ?>value="title:"<?php } ?> onfocus="if (this.value == 'title:') this.value='';" onblur="if (this.value == '') this.value='title:';"><br />
								<?php } ?>

								<?php if (getOption("caption") === "yes") { ?>
									<input type="text" name="captions[]" class="captions" <?php if ($pic['caption'] != "") { ?>value="<?=$pic['caption']?>"<?php } else { ?>value="caption:"<?php } ?> onfocus="if (this.value == 'caption:') this.value='';" onblur="if (this.value == '') this.value='caption:';"><br />
								<?php } ?>

								<?php if (getOption("picURL") === "yes") { ?>
									<input type="text" name="picURL[]" class="picURL" <?php if ($pic['picURL'] != "") { ?>value="<?=$pic['picURL']?>"<?php } else { ?>value="url:"<?php } ?> onfocus="if (this.value == 'url:') this.value='';" onblur="if (this.value == '') this.value='url:';"><br />
								<?php } ?>

								<?php if (getOption("shortDesc") === "yes") { ?>
									<textarea name="shortDesc[]" class="shortDesc mceNoEditor" style="width: 170px; height: 60px; font-size: 11px; font-family: Helvetica, Arial;"><?php if ($pic['shortDesc'] != "") {?><?=$pic['shortDesc']?><?php } else { ?>short description:<?php } ?></textarea><br />
								<?php } ?>
								
								<input type="checkbox" name="hover[]" class="hover" value="yes" style="width: 20px;" <?php if ($pic['hover'] === "yes") { ?>checked<?php } ?>> <span style="font-size: 10px; color: #fff; position: relative; top: -3px;">hover image</span></br />
								
								*/ ?>

							</div>
							<div class="imagetools"> <a href="detail.php?picID=<?= $pic['id'] ?>&category=<?= $catID ?>&parentParentID=<?= $parentParentID ?>&imgName=<?= $pic['img'] ?>&parentPic=<?= $picID ?>&fromModule=yes&pageModule=yes&moduleid=<?= $module['id'] ?>"> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
										<path d="M63.8,18L46.1,0.2l-8.3,8.3L0,46.2V64h17.8l39.1-39l0,0L63.8,18z M58.2,18L54,22.2L41.9,10l4.1-4.2L58.2,18z M4,51.9l8.3,8.1H4V51.9z M17,59.1l-12.3-12l34.3-34.2L51.2,25L17,59.1z" />
									</svg> <span class="label">edit</span> </a> <a href="delete_image.php?id=<?= $pic['id'] ?>&category=<?= $catID ?>&imgName=<?= $pic['img'] ?>&parentID=<?= $picID ?>&modID=<?= $module['id'] ?>&modType=<?= $modType ?>" class="delete"> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
										<g>
											<path d="M54,8H42V4c0-2.2-1.8-4-4-4H26c-2.2,0-4,1.8-4,4v4H10c-2.2,0-4,1.8-4,4v8h4.1l2.6,38.4c0.2,3.1,2.8,5.6,6,5.6h26.5c3.1,0,5.8-2.5,6-5.6L53.9,20H58v-8C58,9.8,56.2,8,54,8z M26,4h12v4H26V4z M47.3,58.1c-0.1,1-0.9,1.9-2,1.9H18.7c-1,0-1.9-0.8-2-1.9L14.1,20h35.7L47.3,58.1z M54,16H10v-4h44V16z" />
											<rect x="9" y="37" transform="matrix(7.669654e-02 0.9971 -0.9971 7.669654e-02 59.1978 14.0736)" width="26.1" height="4" />
											<rect x="30" y="26" width="4" height="26" />
											<rect x="40" y="26" transform="matrix(0.9971 7.665917e-02 -7.665917e-02 0.9971 3.1133 -3.1049)" width="4" height="26.1" />
										</g>
									</svg> <span class="label">delete</span> </a> </div>

						</li>

					<?php

					}

					?>

				</ul>

		</div>

		<div style="clear: both;"></div>

	</div>
</div>

<script type="text/javascript">
	// HTML 5 FILE UPLOAD
	(function() {
		var filesUpload = document.getElementById("files-upload<?= $module['id'] ?>"),
			dropArea = document.getElementById("drop-area<?= $module['id'] ?>"),
			fileList = document.getElementById("file-list<?= $module['id'] ?>");

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

					$.get("getModuleImages.php", {
							modId: "<?= $module['id'] ?>",
							picId: "<?= $picID ?>",
							catId: "<?= $catID ?>",
							modType: "<?= $modType ?>"
						})
						.done(function(data) {
							$('#managePics ul').html(data);
							$('ul.file-list li').remove();

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

							$(".delete").colorbox({
								width: "402px",
								height: "227px",
								iframe: true,
								opacity: ".95"
							});

							orderImages();

						});

				}

			}, false);

			xhr.open("post", "upload_file.php", true);

			// Set appropriate headers
			xhr.setRequestHeader("Content-Type", "multipart/form-data");
			xhr.setRequestHeader("X-File-Name", file.name);
			xhr.setRequestHeader("X-File-Size", file.size);
			xhr.setRequestHeader("X-File-Type", file.type);
			xhr.setRequestHeader("X-File-Cat", 0);
			xhr.setRequestHeader("X-File-Picid", <?= $picID ?>);
			xhr.setRequestHeader("X-File-Moduleid", <?= $module['id'] ?>);

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

	// add video embed

	$(".addVideoEmbed").click(function(e) {

		e.preventDefault();

		var picID = $(this).closest('.moduleOpen').find("input#picID").val();
		var moduleID = $(this).closest('.moduleOpen').find("input#moduleID<?= $module['id'] ?>").val();
		var videoURL = $(this).closest('.moduleOpen').find("input#videoURL").val();

		var data = {
			"moduleID": moduleID,
			"picID": picID,
			"videoURL": videoURL
		};
		var data_encoded = JSON.stringify(data);

		$.ajax({
			type: "POST",
			url: "addVideoEmbed.php",
			data: {
				"myContent": data_encoded
			},
			success: function(response) {
				$.get("getModuleImages.php", {
						modId: "<?= $module['id'] ?>",
						picId: "<?= $picID ?>",
						catId: "<?= $catID ?>",
						modType: "<?= $modType ?>"
					})
					.done(function(data) {
						$('#managePics ul').html(data);
						$('ul.file-list li').remove();

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

						$(".delete").colorbox({
							width: "402px",
							height: "227px",
							iframe: true,
							opacity: ".95"
						});

						orderImages();

					});
			},
			error: function() {
				alert('failure');
			}
		});
		return false;

	});

	// module text editor

	$(".textUpdate<?= $module['id'] ?>").click(function(e) {

		var ed = tinymce.get('moduleText<?= $module['id'] ?>'); // get editor instance
		var myText = ed.save(); // retrieve the content save to variable

		var catID = $("input#catID").val();
		var picID = $("input#picID").val();
		var moduleID = $("input#moduleID<?= $module['id'] ?>").val();
		var moduleName = $("input#moduleName<?= $module['id'] ?>").val();
		var pageType = $("#moduleLayout<?= $module['id'] ?> option:selected").val();
		var moduleStatus = $("#moduleStatus<?= $module['id'] ?> option:selected").val();
		var moduleShowTitle = $("#moduleShowTitle<?= $module['id'] ?> option:selected").val();

		var data = {
			"content": myText,
			"moduleID": moduleID,
			"moduleName": moduleName,
			"pageType": pageType,
			"moduleStatus": moduleStatus,
			"moduleShowTitle": moduleShowTitle,
			"picID": picID,
			"catID": catID
		};
		var data_encoded = JSON.stringify(data);

		$.ajax({
			type: "POST",
			url: "updateModuleText.php",
			data: {
				"myContent": data_encoded
			},
			success: function(response) {
				<?php if ($modType === "category") { ?>
					var thePage = "manage_modules.php?category=<?= $catID ?>&parentID=<?= $parentID ?>&parentParentID=<?= $parentParentID ?>&modedit=recordsArray_" + response;
				<?php } else { ?>
					var thePage = "detail.php?picID=<?= $picID ?>&category=<?= $catID ?>&type=<?= $type ?>&modedit=recordsArray_" + response;
				<?php } ?>
				window.location.href = thePage;
			},
			error: function() {
				alert('failure');
			}
		});
		return false;

	});

	function orderImages() {

		$(".managePics<?= $module['id'] ?> ul").sortable({
			opacity: 0.6,
			cursor: 'move',
			update: function() {
				var order = $(this).sortable("serialize") + '&action=updateRecordsListings&modID=<?= $module['id'] ?>';
				$.post("updateImgOrder.php", order, function(theResponse) {});
			}
		});

	};
	orderImages();

	$("A.ajax").click(function() {

		var thePage = $(this).attr("id");

		loadPage(thePage);

	});
</script>