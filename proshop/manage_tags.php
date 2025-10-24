<?php

	session_start();
	include 'includes/secure.php';
	include '../includes/connect.php';
	include 'includes/functions.php';
	
	$tagNameArray = array("Special Notes");
	$tagSectionArray = array("tags");

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

			<p style="float: left;"><span class="head">MANAGE TAGS</span> <span id="alertDivPic"></span></p>

			<div id="breadcrumb">
				<a href="main.php">DASHBOARD</a> > <span class="pageOn">TAGS</span>
			</div>

			<div id="line" style="width: 99%; margin-bottom: 2px; clear: both;"></div>
			<div id="line" style="width: 99%; margin-bottom: 25px;"></div><br />
			
			<?php 
				$arrayCount = 0;
				foreach($tagSectionArray as $tagSet) {
			?>
			
				<div style="display: inline-block; margin-right: 50px; margin-bottom: 50px; width: 200px; vertical-align: top;">
				
					<b><?=$tagNameArray[$arrayCount]?></b><br />
					Click on an existing tag to edit.<br /><br />

					<a href="" class="addTag">Add New Tag(s)</a><br /><br />

					<div style="display: none;" class="newTag">

						<span style="font-size: 10px;">Separate each new tag by a comma (,)</span><br />
						<form name="tagEditor" method="post">
						<input type="hidden" name="section" class="section" value="<?=$tagSet?>">
						<input type="text" name="tag" class="tag"><br />
						<br /><button id="btn" class="addTags" style="float: left; margin-right: 5px;">Add Tag(s)</button><br /><br />
						</form>

					</div>

					<div id="line" style="width: 100%; margin-bottom: 15px;"></div>

					<form name="tagEditor" method="post">
					<input type="hidden" name="section" class="section" value="<?=$tagSet?>">

					<div style="line-height: 14px;" class="manageTags">

						<ul>

							<?php 
							
								$tags = getTags($tagSet);
							
								for ($count = 1; $tag = mysqli_fetch_array($tags); ++$count) { 
								
							?>

								<li id="recordsArray_<?php echo $tag['id']; ?>">
									<a href="delete_tag.php?id=<?=$tag['id']?>&tag=<?=$tag['tag']?>" class="delete"><img src="images/icons/delete.png" style="border: 0px; float: left; margin-right: 5px; position: relative; top: -5px;"></a>
									<span style="cursor: pointer;" class="editTag"><?=$tag['tag']?></span><br />
									<div style="display: none;" class="tagField">
										<input type="text" name="tag" class="tag" value="<?=$tag['tag']?>">
										<input type="hidden" name="tagID" class="tagID" value="<?=$tag['id']?>">
										<input type="hidden" name="oldTag" class="oldTag" value="<?=$tag['slug']?>">
									</div>
									<br />
								</li>

							<?php } ?>

						</ul>

					</div>

					<br /><button id="btn" class="updateTags" style="float: left; margin-right: 5px;">Update Tag(s)</button><br />

					</form>
					
				</div>
			
				<?php ++$arrayCount; ?>
				
			<?php } ?>

		</div>
		
	</div>
	
</div>

<script type="text/javascript">
$(document).ready(function() {

	$(".addTag").click(function(e) {
	
		e.preventDefault();

		$(this).parent().find(".newTag").slideToggle("slow");

	});
	
	$(".editTag").click(function(e) {
	
		e.preventDefault();
	
		$(this).parent().find(".tagField").slideToggle();
	});

	// HANDLE ADDING TAGS
	$(".addTags").click(function(e) {
	
		e.preventDefault();

		var tag = $(this).parent().find("input.tag").val();
		var section = $(this).parent().find("input.section").val();

		var data = {"tag" : tag, "section" : section};
		var data_encoded = JSON.stringify(data);

		$.ajax({
		  type: "POST",
		  url: "addTags.php",
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

	// HANDLE UPDATING TAGS
	$(".updateTags").click(function() {

		var tagIDArray = [];
		$(this).parent().find('.tagID').each(function() {
			var tagID = $(this).val();
			tagIDArray.push(tagID);
		});
		
		var oldtagArray = [];
		$(this).parent().find('.oldTag').each(function() {
			var oldTag = $(this).val();
			oldtagArray.push(oldTag);
		});

		var tagArray = [];
		$(this).parent().find('.tag').each(function() {
			var tag = $(this).val();
			tagArray.push(tag);
		});
		
		var section = $(this).parent().find("input.section").val();

		var data = {"tagID" : tagIDArray, "tag" : tagArray, "oldTag" : oldtagArray, "section" : section};
		var data_encoded = JSON.stringify(data);

		$.ajax({
		  type: "POST",
		  url: "editTags.php",
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


	// colorbox delete link
	$(".delete").colorbox({
		width:"60%",
		height:"60%",
		iframe:true});

	// tag order
	$(function() {
		$(".manageTags ul").sortable({ opacity: 0.6, cursor: 'move', update: function() {
			var order = $(this).sortable("serialize") + '&action=updateRecordsListings';
			$.post("updateTagOrder.php", order, function(theResponse){
				$("#alertDivPic").show();
				$("#alertDivPic").html(theResponse);

				// hide the element
				$("#alertDivPic").fadeOut(3000);
			});
		}
		});
	});

});
</script>

</body>
</html>