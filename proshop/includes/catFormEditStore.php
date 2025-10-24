<?php

	include 'secure.php';
	include 'functions.php';
	include 'connect.php';

	$thisID = $_GET['catID'];
	$sub = $_GET['sub'];

	$cat = catDetails($thisID);

	if ($sub === "yes") {
		$theName = $cat['subName'];
	} else {
		$theName = $cat['name'];
	}

?>

<script type="text/javascript"><!--

$(".update_<?=$thisID?>").click(function() {

	var thisCatID = $("input#thisCatID_<?=$thisID?>").val();
	var catID = $("input#catID_<?=$thisID?>").val();
	var parentID = $("#parentIDChange_<?=$thisID?> option:selected").val();
	var catName = $("input#catChangeName_<?=$thisID?>").val();
	var status = $("#status_<?=$thisID?> option:selected").text();

	var alertMsg = "The following REQUIRED fields\nhave been left empty:\n";
	if (catName === "" || catName === "Category/Page Name") alertMsg += "\nName of Category";
	if (status === "") alertMsg += "\nPage Status";
	if (alertMsg != "The following REQUIRED fields\nhave been left empty:\n") {
		alert(alertMsg);
		return false;
	} else {

		// handle ajax post here
		 var data = {"thisCatID" : thisCatID, "catID" : catID, "parentID" : parentID, "catName" : catName, "pageType" : "images", "status" : status};
		 var data_encoded = JSON.stringify(data);

		 $.ajax({
		   type: "POST",
		   url: "editCategoryStore.php",
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
	}
});

// update main image

$('#img form[name="editMain"]').iframePostForm
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
			  url: 'manage_categories.php',
			  cache: false,
			  success: function(data) {
				$('#pageContent').html(data);
				Cufon.refresh();
			  }
			});

		}
	}
});

// --></script>

<form name="catForm<?=$thisID?>" method="post">
<input type="hidden" name="thisCatID" id="thisCatID_<?=$thisID?>" value="<?=$thisID?>">
<input type="hidden" name="catID" id="catID_<?=$thisID?>" value="<?=$thisID?>">

<input type="text" name="catName" id="catChangeName_<?=$thisID?>" value="<?=$theName?>" class="smallField" style="margin-right: 10px;">

<?php if ($sub === "yes") { ?>

	<select name="parent" id="parentIDChange_<?=$thisID?>" class="smallField" style="width: 165px; height: 24px; margin-right: 10px;">
		<option value="<?=$cat['parentID']?>"><?=catName($cat['parentID'])?></option>

		<?php echo getShopCategories("select", "yes"); ?>

	</select>

<?php } ?>

<select name="status" id="status_<?=$thisID?>" class="smallField" style="margin-right: 10px;">
	<option value="<?=$cat['status']?>"><?=$cat['status']?></option>
	<option value="live">Live</option>
	<option value="preview">Preview</option>
</select>

<button id="btn" class="update_<?=$thisID?>">Update</button>

</form>

<div style="clear: both;"></div>

<span style="color: #636363; font-size: 11px;">CATEGORY IMAGE</span><br />

<div id="img">

	<?php if ($cat['imgON'] != "") { ?>
		<img src="<?=getOption("imagePath")?><?=$cat['imgON']?>" width="150"><br />
	<?php } ?>
	<form name="editMain" action="updateCategoryImage.php" enctype="multipart/form-data" method="post" target="iframe-post-form">
	<input type="hidden" name="catID" value="<?=$thisID?>">
	<input type="file" name="mainImg"><br />
	<input type="submit" value="Update Image" id="btn"><br /><br />
	</form>

</div>


