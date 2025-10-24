<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';
	
	$query2 = "SELECT videos.* FROM videos JOIN cat_videos ON cat_videos.vidid = videos.id WHERE cat_videos.catid = '$catID' ORDER BY cat_videos.sortBy ASC";

	$result2 = mysql_query($query2);

	if (!$result2) {
			die('Could not query:' . mysql_error());
			exit;
	}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML>
<HEAD>

<?php include 'includes/pageHead.php'; ?>

</HEAD>
<BODY>

<?php showStatus($_REQUEST['status']) ?>

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

			<p style="float: left;"><span class="head">Manage Videos:</span></p>

			<div id="breadcrumb">
				<a href="main.php">DASHBOARD</a> > <span class="pageOn">VIDEOS</span>
			</div>

			<div id="line" style="width: 99%; margin-bottom: 2px; clear: both;"></div>
			<div id="line" style="width: 99%; margin-bottom: 25px;"></div>
			
			<?php include('metaEditor.php'); ?>

			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					
					<?php /*
					
					<td align="left" valign="top">

						<div style="float: left; height: 260px;">

							<span class="head">Upload Video</span><br />

							<form id="form1" action="index.php" method="post" enctype="multipart/form-data">
							You can upload .flv and .mov video files.<br /><br />

							<div class="fieldset flash" id="fsUploadProgress">
							<span class="legend">Upload Queue</span>
							</div>
							<div id="divStatus">0 Files Uploaded</div>
							<div>
								<span id="spanButtonPlaceHolder" style="text-align: center;"></span>
								<input id="btnCancel" type="button" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 11px; font-family: Arial; color: #000000; height: 20px;" />
							</div>

							</form>

						</div>
					</td>
					
					*/ ?>
					
					<td align="left" valign="top">

						<div style="float: left; height: 260px;">

							<span class="head">Embed Video</span><br />
							<form name="embed" action="addVideo.php" method="post">
							<input type="hidden" name="catID" id="catID" value="<?=$catID?>">
							<textarea name="embedCode" id="embedCode" style="width: 350px; height: 150px;"></textarea><br />

							<input type="text" name="title" id="title" value="Video Title..." onClick="this.value=''" class="lgField" style="width: 350px;"><br />

							<button id="btn" class="add-embed">Add Video</button><br>

							</form>

						</div>

					</td>
				</tr>
				<tr>
					<td colspan="2" valign="top"><br />

						<p style="float: left; width: 99%;"><span class="head">Arrange / Edit Videos:</span> (Click and drag videos to rearrange the order) <span class="detailLink" style="float: right; padding-right: 5px; padding-top: 6px; cursor: pointer; font-weight: normal;">DETAIL VIEW</span></p>

						<div id="line" style="width: 99%; margin-bottom: 2px; clear: both;"></div>
						<div id="line" style="width: 99%;"></div><br />

						<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td align="left" valign="top">

									<div id="managePics">

										<form name="content" action="updateVideos.php" method="post">
										<input type="hidden" name="category" id="category" value="<?=$catID?>">

										<button id="btn" class="update" style="margin-bottom: 15px; float: left; margin-right: 15px;">Save Changes</button>

										<div class="alertDivPic" style="float: left; margin: 0px 5px 0 0;"></div>

										<div style="clear: both;"></div>

										<ul>

											<?php

												for ($count = 1; $pic = mysql_fetch_array($result2); ++$count) {
												

											?>

													<li class="picHolder" id="recordsArray_<?php echo $pic['id']; ?>">
														<?php if ($pic['thumbnail'] != "") { ?>
														
															<img src="<?php echo getOption("imagePath"); ?><?=$pic['thumbnail']?>?fit=crop&w=200&h=200" width="200" height="200" border="0" style="position: absolute; top: 0px; left: 0px; z-index: 1;"><br />

														<?php } else { ?>
															<img src="images/no-poster.png?fit=crop&w=200&h=200" width="200" height="200" border="0" style="position: absolute; top: 0px; left: 0px; z-index: 1;"><br />
														<?php } ?>
														<input type="hidden" class="videoID" name="videoID[]" value="<?=$pic['id']?>">
														<input type="hidden" class="img" name="img[]" value="<?=$pic['thumbnail']?>">

														<div class="picCover" style="display: none;"></div>

														<div class="picInfo" style="display: none;">

															<input type="text" name="title[]" class="titles" <?php if ($pic['title'] != "") { ?>value="<?=htmlentities($pic['title'])?>"<?php } else { ?>value="title:"<?php } ?> onfocus="if (this.value == 'title:') this.value='';" onblur="if (this.value == '') this.value='title:';"><br />

															<input type="text" name="captions[]" class="captions" <?php if ($pic['caption'] != "") { ?>value="<?=htmlentities($pic['caption'])?>"<?php } else { ?>value="caption:"<?php } ?> onfocus="if (this.value == 'caption:') this.value='';" onblur="if (this.value == '') this.value='caption:';"><br />

															<span class="smallGray">move to:</span>
															<select name="moveCat[]" class="moveCat" style="font-size: 10px; width: 122px;">
																<option value="<?=$catID?>" selected>--select--</option>

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

														</div>
														
														<div style="position: absolute; bottom: -24px; left: 0px; font-size: 10px; width: 100%;">
															<a href="#" class="ajax" id="videoDetail.php?vidID=<?=$pic['id']?>&category=<?=$catID?>" onclick="return false;" style="float: left; color: #000; text-decoration: none;"><img src="images/icons/edit.png"> <span style="position: relative; top: -5px;">edit</span></a>
															<div style="float: right; margin-right: 5px;">
																<a href="delete_video.php?id=<?=$pic['id']?>&category=<?=$catID?>&caption=<?=$pic['caption']?>&vidFile=<?=$pic['file']?>" class="delete" style="color: #000; text-decoration: none;"><img src="images/icons/delete.png" style="position: relative; top: -3px;"/> <span style="position: relative; top: -5px;">delete</span></a>
																<span><input type="checkbox" name="deleteCheck" class="deleteCheck" value="yes" style="width: auto; margin-bottom: 0px; position: relative; top: -3px;"></span>
															</div>
														</div>

													</li>

											<?php

												}

											?>

										</ul>

									</div>

									<div id="clear"></div>

									<button id="btn" class="update" style="float: left; margin-right: 15px; margin-top: 15px;">Save Changes</button>
									<div class="alertDivPic" style="float: left; margin: 0px 5px 0 0;"></div>
									</form>

								</td>
							</tr>
						</table>

					</td>
				</tr>
			</table>

		</div>
		
	</div>
	
</div>

</body>
</html>

<script type="text/javascript">
	// image upload js

	var swfu;

	imageLoad = function () {
		var settings = {
			flash_url : "swfupload/swfupload.swf",
			upload_url: "uploadVideo.php",
			post_params: {"PHPSESSID" : "<?php echo session_id(); ?>"},
			file_size_limit : "100 MB",
			file_types : "*.flv;*.mov",
			file_types_description : "Flash and Quicktime Videos",
			file_upload_limit : 1,
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

				$("body").animate({scrollTop : 0},'slow');

				$('#pageContent').html('<div style="text-align: center; width: 100%; margin-top: 25%; color: #ccc;"><img src="images/ajax-loader.gif"><p>Loading...</p></div>');

				var thePage = "manage_video.php?category=<?=$catID?>";
				loadPage(thePage);

			}
		}
	};

	$(function() {
		$("#managePics ul").sortable({ opacity: 0.6, cursor: 'move', update: function() {
			var order = $(this).sortable("serialize") + '&action=updateRecordsListings&catID=<?=$catID?>';
			$.post("updateVidOrder.php", order, function(theResponse){
				$("#alertDivVid").show();
				$("#alertDivVid").html(theResponse);

				// hide the element
				$("#alertDivVid").fadeOut(3000);
			});
		}
		});
	});


	// HANDLE VIDEO UPDATES
	$(".update").click(function() {

		var category = $("#category").val();

		var videoIDArray = [];
		$('.videoID').each(function() {
			var videoID = $(this).val();
			videoIDArray.push(videoID);
		});
		
		var titlesArray = [];
		$('.titles').each(function() {
			var titles = $(this).val();
			titlesArray.push(titles);
		});

		var captionsArray = [];
		$('.captions').each(function() {
			var captions = $(this).val();
			captionsArray.push(captions);
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
		 
		 var deleteArray = [];
		 $('.deleteCheck').each(function() {
			if ($(this).is(':checked')) {
				var deleteThis = "yes";
			} else {
				var deleteThis = "no";
			}
			deleteArray.push(deleteThis);
		 });

		var data = {"category" : category, "videoID" : videoIDArray, "captions" : captionsArray, "titles" :  titlesArray, "moveCat" : moveCatArray, "copyCat" : copyCatArray, "deleteArray" : deleteArray};
		var data_encoded = JSON.stringify(data);

		$.ajax({
		   type: "POST",
		   url: "updateVideos.php",
		   data: {
			 "myContent" : data_encoded
		   },
		   success: function(response) {
				window.location.href = response;
		   },
		   error: function(){
			 alert('failure');
		   }
		});
		return false;

	});

	// HANDLE EMBED VIDEO
	$(".add-embed").click(function() {

		var catID = $("#catID").val();
		var embedCode = $("#embedCode").val();
		var title = $("#title").val();

		var data = {"catID" : catID, "embedCode" : embedCode, "title" : title};
		var data_encoded = JSON.stringify(data);

		$.ajax({
		  type: "POST",
		  url: "addVideo.php",
		  data: {
		  	"myContent" : data_encoded
		  },
		  success: function(response) {
			$("body").animate({scrollTop : 0},'slow');

			$('#pageContent').html('<div style="text-align: center; width: 100%; margin-top: 25%; color: #ccc;"><img src="images/ajax-loader.gif"><p>Loading...</p></div>');

			var thePage = "manage_video.php?category=<?=$catID?>";
			window.location.href = thePage;
		  },
		  error: function(){
		   	alert('failure');
  		  }
		});

		return false;

	});

</script>

<script type="text/javascript">
$(document).ready(function(){
	
	/* JS OVERRIDES TO SHOW CORRECT DEFAULT MENU */
	
	$("#contentTopMenu").css('display', 'block');
	$("#catList").css('display', 'block');

	//Examples of how to assign the ColorBox event to elements
	$(".delete").colorbox({
		width:"60%",
		height:"60%",
		iframe:true});

	$("A.ajax").click(function() {

		var thePage = $(this).attr("id");

		loadPage(thePage);

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

});
</script>
<?php /*
<script type="text/javascript">
	imageLoad();
</script>
*/ ?>