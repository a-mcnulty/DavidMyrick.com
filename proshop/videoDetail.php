<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	$vidID = $_GET['vidID'];
	$pageStatus = checkStatus($catID);
	$myText = getVideoDetailText($vidID);
	$parentID = getParentID($catID);
	$type = $_GET['type'];
	$tags = getTags();

?>

<script type="text/javascript">
	
	tinymce.init({
		mode: "none",
		theme: "modern",
		plugins: [
			 "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
			 "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
			 "save table contextmenu directionality emoticons template paste textcolor"
	   ],
	   toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image | print preview media fullpage | forecolor backcolor emoticons | link", 
       style_formats: [
		   {title: 'Heading 1', block: 'h1'},
		   {title: 'Heading 2', block: 'h2'},
		   {title: 'Paragraph', block: 'p'},
	   ]
	 });

</script>

<script type="text/javascript">

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
		var groupID = $(this).attr("lang");
		$("#option_"+groupID+"_"+openID).slideToggle();

	});

	// product options

	$(".optionsTitle").click(function() {

		$(".prodOptions").slideToggle();

	});

	// update main image

	$('#img form').iframePostForm
	({
		json : false,
		post : function ()
		{
			var message;

			if (!$('.message').length)
			{
				$('#img').before('<div class="message" style="display:none; padding:10px; text-align:center" />');
			}


			if ($('input[type=file]').val().length)
			{
				$('.message')
					.html('Uploading file&hellip;')
					.css({
						width: '300px',
						color : '#000',
						background : '#ededed',
						border : '1px solid #e7e7e6'
					})
					.slideDown();
			}

			else
			{
				$('.message')
					.html('Please select an image for uploading.')
					.css({
						width: '300px',
						color : '#9c0006',
						background : '#ffc7ce',
						border : '1px solid #9c0006'
					})
					.slideDown();

				return false;
			}
		},
		complete : function (response)
		{
			var style,
				width,
				html = '';

			if (response != "success")
			{
				$('.message').slideUp(function ()
				{
					$(this)
						.html('There was a problem with the image you uploaded')
						.css({
							color : '#9c0006',
							background : '#ffc7ce',
							borderColor : '#9c0006'
						})
						.slideDown();
				});
			}

			else
			{

				// success reload the detail page

				$.ajax({
				  url: 'videoDetail.php?vidID=<?=$vidID?>&category=<?=$catID?>&type=video',
				  cache: false,
				  success: function(data) {
					$('#pageContent').html(data);
				  }
				});

			}
		}
	});

	$(".update").click(function() {

		var ed = tinymce.activeEditor; // get editor instance
		var myText = ed.save(); // retrieve the content save to variable

		var catID = $("input#catID").val();
		var vidID = $("input#vidID").val();
		var title = $("input#title").val();
		var caption = $("input#caption").val();
		var embedCode = $("#embedCode").val();

		var data = {"content" : myText, "embedCode" : embedCode, "title" : title, "caption" : caption, "catID" : catID, "vidID" : vidID};
		var data_encoded = JSON.stringify(data);

		$.ajax({
		  type: "POST",
		  url: "updateVideoDetail.php",
		  data: {
			"myContent" : data_encoded
		  },
		  success: function(response) {
			$('.theResponse').html(response);
		  },
		  error: function(){
			alert('failure');
		  }
		});
		return false;

	});
	
	// update tags

	$(".updateTags").click(function() {

		var catID = $("input#catID").val();
		var picID = $("input#vidID").val();

		var tagArray = [];
		$('.tag').each(function() {
			if ($(this).is(':checked')) {
				var theTag = $(this).val();
				tagArray.push(theTag);
			}
		});

		var data = {"catID" : catID, "picID" : picID, "tags" : tagArray, "table" : "videos"};
		var data_encoded = JSON.stringify(data);

		$.ajax({
		  type: "POST",
		  url: "updateTags.php",
		  data: {
			"myContent" : data_encoded
		  },
		  success: function(response) {
			loadPage(response);
		  },
		  error: function(){
			alert('failure');
		  }
		});
		return false;

	});
	
	$(document).ready(function() {
	
		tinyMCE.execCommand('mceRemoveEditor', true, 'myText');
		tinyMCE.execCommand('mceAddEditor', false, 'myText');	
	
	});

</script>

<div class="imagePage">

		<?php

			$query1 = "SELECT * FROM videos WHERE id = '$vidID' ORDER BY sortBy ASC";

			$result1 = mysql_query($query1);

			if (!$result1) {
					die('Could not query:' . mysql_error());
					exit;
			}

			$mainPic = mysql_fetch_array($result1);

		?>

	<div id="alertDivPic" style="float: right; margin: 9px 5px 0 0;"></div>
	<p style="float: left;"><span class="head">Video Detail: <?=$mainPic['title']?> - <?=$mainPic['caption']?></span></p>

	<div id="breadcrumb">
		<?php if ($type === "product") { ?>
			<a href="#" id="manage_products.php?catID=<?=$catID?>" class="ajax" onclick="return false;">Back to Products</a>
		<?php } else { ?>
			<a href="main.php">DASHBOARD</a> > <a href="manage_video.php?category=<?=$catID?>" class="breadcrumb">VIDEOS</a> > <span class="pageOn">VIDEO DETAIL</span>
		<?php } ?>
	</div>

	<div id="line" style="width: 99%; margin-bottom: 2px; clear: both;"></div>
	<div id="line" style="width: 99%; margin-bottom: 25px;"></div><br />

	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td align="left" valign="top">

				<div style="float: left; margin-right: 50px;" id="img">

					<span class="head">Poster Image</span><br />

					<?php if ($mainPic['thumbnail'] != "") { ?>
						<img src="<?php echo getOption("imagePath"); ?><?=$mainPic['thumbnail']?>?fit=crop&w=150" width="150"><br />
					<?php } else { ?>
						<img src="images/no-poster.png" width="150"><br />
					<?php } ?>
					<form name="editMain" action="updateVideoImg.php" enctype="multipart/form-data" method="post" target="iframe-post-form">
					<input type="hidden" name="vidID" value="<?=$vidID?>">
					<input type="hidden" name="catID" value="<?=$catID?>">
					<input type="file" name="mainImg"><br />
					<input type="submit" value="Update Image" id="btn"><br /><br />
					</form><br /><br />
					
					<form name="editDetails">
					<span class="head">TITLE</span><br />
					<input type="text" name="title" id="title" value="<?=htmlentities($mainPic['title'])?>" style="width: 315px;"><br /><br />
					
					<span class="head">CAPTION</span><br />
					<input type="text" name="caption" id="caption" value="<?=htmlentities($mainPic['caption'])?>" style="width: 315px;"><br /><br />
					
					<button id="btn" class="update" style="float: left;">Update Video</button>

					<div class="theResponse" style="float: left; margin-left: 10px;"></div>
					</form>
					
					<?php /*
					
					<span class="head">Content Tag(s)</span><br /><br />
					<form name="tags" method="post">
					<input type="hidden" name="picID" id="picID" value="<?=$picID?>">
					<input type="hidden" name="catID" id="catID" value="<?=$catID?>">

					<?php

						$projectTags = $mainPic['tags'];
						$tagArray = explode(",", $projectTags);

					?>

					<?php for ($count = 1; $tag = mysql_fetch_array($tags); ++$count) { ?>

						<?php if (in_array($tag['tag'], $tagArray)) { ?>
							<input type="checkbox" name="tag" class="tag" value="<?=$tag['tag']?>" checked> <?=$tag['tag']?>&nbsp;&nbsp;
						<?php } else { ?>
							<input type="checkbox" name="tag" class="tag" value="<?=$tag['tag']?>"> <?=$tag['tag']?>&nbsp;&nbsp;
						<?php } ?>

					<?php } ?>

					<br /><button id="btn" class="updateTags" style="float: left; margin-top: 10px;">Update Tags</button>

					</form>
					
					*/ ?>

					<?php /*
					
					<div style="clear: both; height: 25px;"></div>

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
					
					*/ ?>

				</div>

			</td>
			<td align="left" valign="top">

				<div style="float: left;">

					<form name="textEditor" method="post">
					<span class="head">Embed Code:</span><br />
					<textarea name="embedCode" style="width: 500px; height: 100px;" class="mceNoEditor" id="embedCode"><?=$mainPic['embed']?></textarea><br /><br />

					<span class="head">Text Editor:</span><br />
					<b>Editor Tip</b> - To create a single line break hold down the SHIFT + ENTER Key.<br />

					<input type="hidden" id="vidID" name="vidID" value="<?=$vidID?>">
					<input type="hidden" id="catID" name="catID" value="<?=$catID?>">

					<textarea name="myText" id="myText" class="myText mceEditor" style="width: 500px; height: 300px;"><?=stripslashes($myText)?></textarea><br>

					<button id="btn" class="update" style="float: left;">Update Video</button>

					<div class="theResponse" style="float: left; margin-left: 10px;"></div>

					</form>

				</div>

			</td>
		</tr>
	</table>
	
	<?php /*

	<span class="head">Arrange / Edit Photos:</span> (Click and drag images to rearrange the order)<br />

	<div id="line" style="width: width: 100%; margin-bottom: 2px;"></div>
	<div id="line" style="width: width: 100%;"></div><br />

	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td align="left" valign="top">

				<div id="managePics">

					<form name="content" action="updateImages.php" method="post">
					<input type="hidden" name="category" value="0">
					<input type="hidden" name="detailID" value="<?=$vidID?>">

					<input type="submit" value="Save Changes" id="btn" style="margin-bottom: 15px; float: left; margin-right: 15px;">

					<div id="alertDivPic" style="float: left;">

					</div>

					<div style="clear: both;"></div>

					<ul>

						<?php

							$query2 = "SELECT * FROM pics WHERE picid = '$vidID' ORDER BY sortBy ASC";

							$result2 = mysql_query($query2);

							if (!$result2) {
									die('Could not query:' . mysql_error());
									exit;
							}

							for ($count = 1; $pic = mysql_fetch_array($result2); ++$count) {

						?>

								<li id="recordsArray_<?php echo $pic['id']; ?>">
									<img src="<?php echo getOption("imagePath"); ?>thumbs/<?=$pic['img']?>" width="200" height="200"><br />
									<input type="hidden" name="picID[]" value="<?=$pic['id']?>">

									<a href="delete_image.php?id=<?=$pic['id']?>&category=<?=$catID?>&imgName=<?=$pic['img']?>&type=product&parentID=<?=$picID?>" class="delete" style="float: right;">delete</a>
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
	
	*/ ?>
	
</div>