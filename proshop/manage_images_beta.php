<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML>
<HEAD>

<?php include 'includes/pageHead.php'; ?>

</HEAD>
<BODY>

<script type="text/javascript">

// image upload js

var swfu;

function imageLoad() {
	var settings = {
		flash_url : "swfupload/swfupload.swf",
		upload_url: "upload.php",
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
		button_text_style: ".theFont { font-size: 11; font-family: Arial; color: fff; background: #50b6b8;}",
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

			$.ajax({
			  url: 'manage_images.php?category=<?=$catID?>',
			  success: function(data) {
				$('#pageContent').html(data);
				Cufon.refresh();
			  }
			});

			//window.location = 'manage_images.php?category=<?=$catID?>';
		}
	}
 };

// HANDLE FORM SUBMISSION
$(".update").click(function() {

	var category = $("input#category").val();
	var parentID = $("input#parentID").val();

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

	 var data = {"category" : category, "parentID" : parentID, "picID" : picIDArray, "img" : imgArray, "title" : titleArray, "captions" : captionsArray, "picURL" : picURLArray, "shortDesc" : shortDescArray, "price" : priceArray, "wprice" : wpriceArray, "moveCat" : moveCatArray, "copyCat" : copyCatArray};
	 var data_encoded = JSON.stringify(data);

	 $.ajax({
	   type: "POST",
	   url: "updateImages.php",
	   data: {
		 "myContent" : data_encoded
	   },
	   success: function(response) {
		 $("#alertDivPic").show();
		 $('#alertDivPic').html(response);
		 $("#alertDivPic").fadeOut(3000);
	   },
	   error: function(){
		 alert('failure');
	   }
	});
	return false;

});

$(".uploaderLink").click(function() {

	$(".uploader").slideToggle();

});

var detailState = "closed";
$(".detailLink").click(function() {
	if (detailState === "closed") {
		$(this).html("QUICK VIEW");
		detailState = "open";
		$(".picInfo").fadeTo(200, 1);
		$(".picCover").fadeTo(200, .5);
	} else {
		$(this).html("DETAIL VIEW");
		detailState = "closed";
		$(".picInfo").fadeTo(200, 0);
		$(".picCover").fadeTo(200, 0);
	}
	Cufon.refresh();
});

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

$(document).ready(function() {

	//Examples of how to assign the ColorBox event to elements
	$(".delete").colorbox({
		width:"60%",
		height:"60%",
		iframe:true});

	// in page ajax links

	$("A.ajax").click(function() {

		var thePage = $(this).attr("id");

		loadPage(thePage);

	});

});
</script>

<div id="leftCol"></div>

<div id="contentArea">

	<?php include 'includes/topMenu.php' ?>

	<div id="pageContent">

		<p><span class="head uploaderLink">Upload Image(s)</span></p>

		<div id="line" style="width: width: 100%; margin-bottom: 2px;"></div>
		<div id="line" style="width: width: 100%;"></div><br />

		<div class="uploader" style="display: none;">
			<form id="form1" action="index.php" method="post" enctype="multipart/form-data">
			<p>You can upload .jpg, .gif, or .png files.  Images should be compressed for Web Resolution (72 DPI)<br />
			<span id="blue"><?php echo imageSpecs(); ?></span></p><br />

			<div class="fieldset flash" id="fsUploadProgress">
			<span class="legend">Upload Queue</span>
			</div>
			<div id="divStatus">0 Files Uploaded</div>
			<div>
				<span id="spanButtonPlaceHolder" style="text-align: center;"></span>
				<input id="btnCancel" type="button" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px;" />
			</div>

			</form><br /><br />
		</div>

		<p><span class="head">Arrange / Edit Photos:</span> (Click and drag images to rearrange the order) <span class="detailLink" style="float: right; padding-right: 5px; padding-top: 6px; cursor: pointer; font-weight: normal;">DETAIL VIEW</span></p>

		<div id="line" style="width: width: 100%; margin-bottom: 2px;"></div>
		<div id="line" style="width: width: 100%;"></div><br />

		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td align="left" valign="top">

					<div id="managePics">

						<form name="content" action="updateImages.php" method="post">
						<input type="hidden" id="category" name="category" value="<?=$catID?>">
						<input type="hidden" id="parentID" name="parentID" value="<?=$parentID?>">

						<button id="btn" class="update" style="margin-bottom: 15px; float: left; margin-right: 15px;">Save Changes</button>

						<div id="alertDivPic" style="float: left;">

						</div>

						<div style="clear: both;"></div>

						<ul>

							<?php

								$query2 = "SELECT * FROM pics WHERE catid = '$catID' ORDER BY sortBy ASC";

								$result2 = mysql_query($query2);

								if (!$result2) {
										die('Could not query:' . mysql_error());
										exit;
								}

								for ($count = 1; $pic = mysql_fetch_array($result2); ++$count) {

							?>

									<li class="picHolder" id="recordsArray_<?php echo $pic['id']; ?>">
										<a href="detail.php?picID=<?=$pic['id']?>&category=<?=$catID?>&imgName=<?=$pic['img']?>"><img src="<?php echo getOption("imagePath"); ?>thumbs/<?=$pic['img']?>" width="200" height="200" border="0" style="position: absolute; top: 0px; left: 0px; z-index: 1;"></a><br />
										<input type="hidden" class="picID" name="picID[]" value="<?=$pic['id']?>">
										<input type="hidden" class="img" name="img[]" value="<?=$pic['img']?>">

										<div class="picCover" style="display: none;"></div>

										<div class="picInfo" style="display: none;">

											<?php if (getOption("title") === "yes") { ?>
												<input type="text" name="title[]" class="titleField" <?php if ($pic['title'] != "") { ?>value="<?=$pic['title']?>"<?php } else { ?>value="title:"<?php } ?> onfocus="if (this.value == 'title:') this.value='';" onblur="if (this.value == '') this.value='title:';"><br />
											<?php } ?>

											<?php if (getOption("caption") === "yes") { ?>
												<input type="text" name="captions[]" class="captions" <?php if ($pic['caption'] != "") { ?>value="<?=$pic['caption']?>"<?php } else { ?>value="style no:"<?php } ?> onfocus="if (this.value == 'style no:') this.value='';" onblur="if (this.value == '') this.value='style no:';"><br />
											<?php } ?>

											<?php if (getOption("picURL") === "yes") { ?>
												<input type="text" name="picURL[]" class="picURL" <?php if ($pic['picURL'] != "") { ?>value="<?=$pic['picURL']?>"<?php } else { ?>value="url:"<?php } ?> onfocus="if (this.value == 'url:') this.value='';" onblur="if (this.value == '') this.value='url:';"><br />
											<?php } ?>

											<?php if (getOption("shortDesc") === "yes") { ?>
												<?php if ($catID == 324) { ?>
													<textarea name="shortDesc[]" class="shortDesc" style="width: 150px; height: 30px; font-size: 12px; font-family: Helvetica, Arial;"><?php if ($pic['shortDesc'] != "") {?><?=$pic['shortDesc']?><?php } else { ?>short description:<?php } ?></textarea><br />
												<?php } ?>
											<?php } ?>

											<span class="smallGray">move to:</span>
											<select name="moveCat[]" class="moveCat" style="font-size: 10px; width: 122px;">
												<option value="<?=$pic['catid']?>" selected>--select--</option>

												<?php

													$myCats = catList();
													for ($catCount = 1; $cat = mysql_fetch_array($myCats); ++$catCount) {

														$thisID = $cat['id'];

												?>

													<option value="<?=$cat['id']?>"><?=$cat['name']?></option>

													<?php

														$mySubs = subcatList($thisID);
														for ($subCount = 1; $subcat = mysql_fetch_array($mySubs); ++$subCount) {

													?>

														<option value="<?=$subcat['id']?>" style="padding-left: 10px;"><?=$subcat['subName']?></option>

													<?php } ?>

												<?php } ?>

											</select><br />

											<span class="smallGray">copy to:</span>
											<select name="copyCat[]" class="copyCat" style="font-size: 10px; width: 122px;">
												<option value="" selected>--select--</option>

												<?php

													$myCats = catList();
													for ($catCount = 1; $cat = mysql_fetch_array($myCats); ++$catCount) {

														$thisID = $cat['id'];

												?>

													<option value="<?=$cat['id']?>"><?=$cat['name']?></option>

													<?php

														$mySubs = subcatList($thisID);
														for ($subCount = 1; $subcat = mysql_fetch_array($mySubs); ++$subCount) {

													?>

														<option value="<?=$subcat['id']?>" style="padding-left: 10px;"><?=$subcat['subName']?></option>

													<?php } ?>

												<?php } ?>

											</select><br />

											<a href="#" class="ajax" id="detail.php?picID=<?=$pic['id']?>&category=<?=$catID?>&imgName=<?=$pic['img']?>" onclick="return false;" style="float: left;">detail</a>
											<a href="delete_image.php?id=<?=$pic['id']?>&category=<?=$catID?>&imgName=<?=$pic['img']?>" class="delete" style="float: right;">delete</a>

										</div>

									</li>

							<?php

								}

							?>

						</ul>

					</div>

					<div id="clear"></div>

					<button id="btn" class="update">Save Changes</button>
					</form>

				</td>
			</tr>
		</table>

	</div>

</div>

<script>
	imageLoad();
</script>

<?php include 'includes/cufon.php' ?>

</BODY>
</HTML>