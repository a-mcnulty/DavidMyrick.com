<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	$pageStatus = checkStatus($catID);
	$myText = getPageDetailText($picID);
	$parentID = getParentID($catID);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML>
<HEAD>

<?php include 'includes/pageHead.php'; ?>

<script type="text/javascript" src="tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
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
  inupload_sub_path:'http://smartglassjewelry.com.previewdns.com/beta/images/pics/userimages/',
  inupload_min_width:'0',
  inupload_max_width:'0',
  document_base_url : 'http://smartglassjewelry.com.previewdns.com/beta/',
  relative_urls: false,
  remove_script_host : false
});
</script>

<!--IMAGE UPLOADER CODE BELOW-->
<link href="css/uploader.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="swfupload/swfupload.js"></script>
<script type="text/javascript" src="js/swfupload.queue.js"></script>
<script type="text/javascript" src="js/fileprogress.js"></script>
<script type="text/javascript" src="js/handlers.js"></script>
<script type="text/javascript">
		var swfu;

		window.onload = function() {
			var settings = {
				flash_url : "swfupload/swfupload.swf",
				upload_url: "upload_detail.php",
				post_params: {"PHPSESSID" : "<?php echo session_id(); ?>"},
				file_size_limit : "100 MB",
				file_types : "*.jpg;*.gif;*.png;*.pdf",
				file_types_description : "JPEG, GIF or PNG Files",
				file_upload_limit : 100,
				file_queue_limit : 0,
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel"
				},
				debug: false,

				// Button settings
				button_image_url: "images/browse.png",
				button_width: "80",
				button_height: "20",
				button_placeholder_id: "spanButtonPlaceHolder",
				button_text: '<span class="theFont">Browse...</span>',
				button_text_style: ".theFont { font-size: 11; font-family: Arial; color: black; background: #50b6b8;}",
				button_text_left_padding: 15,
				button_text_top_padding: 2,

				// The event handler functions are defined in handlers.js
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : MyUploadCompleteHandler,
				queue_complete_handler : queueComplete	// Queue plugin event
			};

			swfu = new SWFUpload(settings);

			function MyUploadCompleteHandler(file) {
				if (this.getStats().files_queued == 0) {
					window.location = 'detail.php?picID=<?=$picID?>';
				}
			}
	     };
	</script>

<script type="text/javascript">
$(document).ready(function(){

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

	$(".addOption").click(function() {

		var openID = $(this).attr("id");
		$("#option"+openID).slideToggle();

	});

});
</script>

</HEAD>
<BODY>

<?php showStatus($_REQUEST['status']) ?>

<div id="leftCol">

	<?php include 'includes/leftCol.php' ?>

</div>

<div id="contentArea">

	<div id="breadcrumb">
		<a href="main.php">Dashboard</a> <?php echo breadcrumb($catID, $picID); ?>
	</div>

	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td align="left" valign="top">

				<div style="float: left; margin-right: 50px;">

					<span class="head">Main Item Image</span><br />

					<?php

						$query1 = "SELECT * FROM pics WHERE id = '$picID' ORDER BY sortBy ASC";

						$result1 = mysql_query($query1);

						if (!$result1) {
								die('Could not query:' . mysql_error());
								exit;
						}

						$mainPic = mysql_fetch_array($result1);

					?>

					<img src="<?php echo getOption("imagePath"); ?>thumbs/<?=$mainPic['img']?>" width="150" height="150"><br />
					<form name="editMain" action="updateProjectImg.php" enctype="multipart/form-data" method="post">
					<input type="hidden" name="picID" value="<?=$picID?>">
					<input type="hidden" name="catID" value="<?=$catID?>">
					<input type="file" name="mainImg"><br />
					<input type="submit" value="Update Image" id="btn"><br /><br />
					</form>

					<span class="head">Upload Additional Image(s)</span><br />

					<form id="form1" action="index.php" method="post" enctype="multipart/form-data">
					You can upload .jpg, .gif, or .png files.  Images should be compressed for Web Resolution (72 DPI)<br />
					<div id="blue"><?php echo imageSpecs(); ?></div><br /><br />

					<div class="fieldset flash" id="fsUploadProgress">
					<span class="legend">Upload Queue</span>
					</div>
					<div id="divStatus">0 Files Uploaded</div>
					<div>
						<span id="spanButtonPlaceHolder" style="text-align: center;"></span>
						<input id="btnCancel" type="button" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 11px; font-family: Arial; color: #000000; height: 20px;" />
					</div>

					</form><br /><br />

				</div>

			</td>
			<td align="left" valign="top">

				<div style="float: left;">

					<span class="head">Text Editor:</span><br />
					<b>Editor Tip</b> - To create a single line break hold down the SHIFT + ENTER Key.<br />
					<form name="textEditor" action="updateDetail.php" method="post">
					<input type="hidden" name="picID" value="<?=$picID?>">
					<input type="hidden" name="category" value="<?=$catID?>">

					<textarea name="content" id="content" style="width: 550px; height: 200px;"><?=stripslashes($myText)?></textarea><br>

					<input type="submit" value="Update Page" id="btn"><br /><br />

					</form>

					<?php if ($catID != 324) { ?>

						<span class="head">Color Options:</span><br />
						To add color options to an item add a name and select the colors below.  By default ALL colors are selected.<br /><br />

						<form name="colors" action="updateOptions.php" method="post">
						<input type="hidden" name="optionID" value="322">
						<input type="hidden" name="picID" value="<?=$picID?>">
						<input type="hidden" name="category" value="<?=$catID?>">

						<?php

							// NEED TO CHECK IF ANY COLORS ARE ALREADY ASSIGNED

							$optionsCount = checkOptions($picID);

							if ($optionsCount > 0) {

								$sql = "SELECT * FROM ec_options WHERE picid = '$picID'";

								$result = mysql_query($sql);

								for ($count = 1; $option = mysql_fetch_array($result); ++$count) {

						?>

								<?php

									$nextCount = $count + 1;

								?>

								<div id="option<?=$count?>">

									<div style="float: left; margin-right: 10px;">
										<span class="smallGray">Name:</span> <input type="text" name="colorName<?=$count?>" value="<?=$option['name']?>" style="width: 125px;">
									</div>
									<div style="float: left; margin-right: 10px;">
										<select name="color<?=$count?>[]" multiple>

											<?php getOptionsID($option['id'], 322); ?>

										</select><br />
									</div>

									<div id="addRow" style="float: left; width: 200px;">
										<img src="images/addIcon.png" border="0" id="addIcon"><a id="<?=$nextCount?>" class="addOption" style="cursor: pointer; position: relative; top: -3px;">Add Another Option</a>
									</div>

									<div style="clear: both;"></div>

								</div>


						<?php

								}

								$startCount = $count;

							} else {

								$startCount = 1;

							}

						?>

						<?php for ($i=$startCount; $i<=10; $i++) { ?>

							<?php

								$nextCount = $i + 1;

							?>

							<div id="option<?=$i?>" <?php if ($i == 1) { ?>style="display: block;"<?php } else { ?>style="display: none;"<?php } ?>>

								<div style="float: left; margin-right: 10px;">
									<span class="smallGray">Name:</span> <input type="text" name="colorName<?=$i?>" style="width: 125px;">
								</div>
								<div style="float: left; margin-right: 10px;">
									<select name="color<?=$i?>[]" multiple>

										<?php getOptions(322); ?>

									</select><br />
								</div>

								<div id="addRow" style="float: left; width: 200px;">
									<img src="images/addIcon.png" border="0" id="addIcon"><a id="<?=$nextCount?>" class="addOption" style="cursor: pointer; position: relative; top: -3px;">Add Another Option</a>
								</div>

								<div style="clear: both;"></div>

							</div>

						<?php } ?>

						<input type="submit" value="Add Color Option(s)" id="btn"><br /><br />

						</form>

					<?php } ?>

				</div>

			</td>
		</tr>
	</table>

	<span class="head">Arrange / Edit Photos:</span> (Click and drag images to rearrange the order)<br />

	<div id="line" style="width: width: 100%; margin-bottom: 2px;"></div>
	<div id="line" style="width: width: 100%;"></div><br />

	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td align="left" valign="top">

				<div id="managePics">

					<form name="content" action="updateImages.php" method="post">
					<input type="hidden" name="category" value="0">
					<input type="hidden" name="detailID" value="<?=$picID?>">

					<input type="submit" value="Save Changes" id="btn" style="margin-bottom: 15px; float: left; margin-right: 15px;">

					<div id="alertDivPic" style="float: left;">

					</div>

					<div style="clear: both;"></div>

					<ul>

						<?php

							$query2 = "SELECT * FROM pics WHERE picid = '$picID' ORDER BY sortBy ASC";

							$result2 = mysql_query($query2);

							if (!$result2) {
									die('Could not query:' . mysql_error());
									exit;
							}

							for ($count = 1; $pic = mysql_fetch_array($result2); ++$count) {

						?>

								<li id="recordsArray_<?php echo $pic['id']; ?>">
									<img src="<?php echo getOption("imagePath"); ?>thumbs/<?=$pic['img']?>" width="150" height="150"><br />
									<input type="hidden" name="picID[]" value="<?=$pic['id']?>">

									<!--

									<?php if (getOption("caption") === "yes") { ?>
										<span class="smallGray">caption:</span><br />
										<input type="text" name="captions[]" value="<?=$pic['caption']?>"><br />
									<?php } ?>

									<?php if (getOption("title") === "yes") { ?>
										<span class="smallGray">title:</span><br />
										<input type="text" name="title[]" value="<?=$pic['title']?>"><br />
									<?php } ?>

									<?php if (getOption("picURL") === "yes") { ?>
										<span class="smallGray">url:</span><br />
										<input type="text" name="picURL[]" value="<?=$pic['picURL']?>"><br />
									<?php } ?>

									<?php if (getOption("shortDesc") === "yes") { ?>
										<span class="smallGray">short description:</span><br />
										<input type="text" name="shortDesc[]" value="<?=$pic['shortDesc']?>"><br />
									<?php } ?>
									<a href="detail.php?id=<?=$pic['id']?>&category=<?=$catID?>&imgName=<?=$pic['img']?>" style="float: left;">detail</a>

									-->

									<a href="delete_image.php?id=<?=$pic['id']?>&category=<?=$catID?>&imgName=<?=$pic['img']?>" class="delete" style="float: right;">delete</a>
								</li>

						<?php

							}

						?>

					</ul>

				</div>

				<div id="clear"></div>

				<input type="submit" value="Save Changes" id="btn">
				</form>

			</td>
		</tr>
	</table>
</div>

</BODY>
</HTML>