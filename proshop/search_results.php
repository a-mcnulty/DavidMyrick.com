<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';
	
	$keyword = $_GET['keyword'];

?>

<script type="text/javascript">

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

	 var data = {"category" : category, "parentID" : parentID, "picID" : picIDArray, "img" : imgArray, "title" : titleArray, "captions" : captionsArray, "picURL" : picURLArray, "shortDesc" : shortDescArray, "price" : priceArray, "wprice" : wpriceArray, "moveCat" : moveCatArray, "copyCat" : copyCatArray, "artists" : artistArray, "show" : showArray};
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

$(document).ready(function() {

	$(".uploaderLink").click(function() {

		$(".uploadHolder").slideToggle();

	});

	$("A.ajax").click(function() {

		var thePage = $(this).attr("id");

		loadPage(thePage);

	});

	//Examples of how to assign the ColorBox event to elements
	$(".delete").colorbox({
		width:"60%",
		height:"60%",
		iframe:true});

});
</script>

<p><span class="head">Search Results: <?=$keyword?></span> <span class="detailLink" style="float: right; padding-right: 5px; padding-top: 6px; cursor: pointer; font-weight: normal;">DETAIL VIEW</span></p>

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

						if ($_GET['show'] === "all") {
							$limit = 5000;
						} else {
							$limit = 5000;
						}

						$query2 = "SELECT pics.* FROM pics JOIN content ON content.picid = pics.id WHERE (title LIKE '%"  . $keyword . "%' OR content LIKE '%"  . $keyword . "%' OR caption LIKE '%"  . $keyword . "%') ORDER BY sortBy ASC LIMIT 0,$limit";

						$result2 = mysql_query($query2);

						if (!$result2) {
								die('Could not query:' . mysql_error());
								exit;
						}

						for ($count = 1; $pic = mysql_fetch_array($result2); ++$count) {

					?>

							<li class="picHolder" id="recordsArray_<?php echo $pic['id']; ?>">
								<?php if ($pic['img'] != "no-image.jpg") { ?>
									<img src="<?php echo getOption("imagePath"); ?>thumbs/<?=$pic['img']?>" width="200" height="200" border="0" style="position: absolute; top: 0px; left: 0px; z-index: 1;"><br />
								<?php } else { ?>
									<div style="width: 200px; height: 200px; position: absolute; top: 0px; left: 0px; text-align: center; border: 1px solid #ccc;">
										<div style="padding-top: 44%;"><?=$pic['title']?></div>
									</div>
								<?php } ?>
								<input type="hidden" class="picID" name="picID[]" value="<?=$pic['id']?>">
								<input type="hidden" class="img" name="img[]" value="<?=$pic['img']?>">

								<div class="picCover" style="display: none;"></div>

								<div class="picInfo" style="display: none;">

									<?php if (getOption("title") === "yes") { ?>
										<input type="text" name="title[]" class="titleField" <?php if ($pic['title'] != "") { ?>value="<?=$pic['title']?>"<?php } else { ?>value="title:"<?php } ?> onfocus="if (this.value == 'title:') this.value='';" onblur="if (this.value == '') this.value='title:';"><br />
									<?php } ?>

									<?php if ($catID === "417" OR $catID === "420") { ?>

										<?php if (getOption("caption") === "yes") { ?>
											<input type="text" name="captions[]" class="captions" <?php if ($pic['caption'] != "") { ?>value="<?=$pic['caption']?>"<?php } else { ?>value="press date:"<?php } ?> onfocus="if (this.value == 'press date:') this.value='';" onblur="if (this.value == '') this.value='press date:';"><br />
										<?php } ?>

									<?php } else { ?>

										<?php if (getOption("caption") === "yes") { ?>
											<input type="text" name="captions[]" class="captions" <?php if ($pic['caption'] != "") { ?>value="<?=$pic['caption']?>"<?php } else { ?>value="caption:"<?php } ?> onfocus="if (this.value == 'caption:') this.value='';" onblur="if (this.value == '') this.value='caption:';"><br />
										<?php } ?>

									<?php } ?>

									<?php if (getOption("picURL") === "yes") { ?>
										<input type="text" name="picURL[]" class="picURL" <?php if ($pic['picURL'] != "") { ?>value="<?=$pic['picURL']?>"<?php } else { ?>value="url:"<?php } ?> onfocus="if (this.value == 'url:') this.value='';" onblur="if (this.value == '') this.value='url:';"><br />
									<?php } ?>

									<?php if (getOption("shortDesc") === "yes") { ?>
										<?php if ($catID == 324) { ?>
											<textarea name="shortDesc[]" class="shortDesc" style="width: 170px; height: 15px; font-size: 11px; font-family: Helvetica, Arial;"><?php if ($pic['shortDesc'] != "") {?><?=$pic['shortDesc']?><?php } else { ?>short description:<?php } ?></textarea><br />
										<?php } ?>
									<?php } ?>

									<?php if ($catID == 417 OR $catID == 420) { ?>

										<span class="smallGray">show:</span>
										<select name="show[]" class="show" style="font-size: 10px; width: 122px;">

											<?php if ($pic['showid'] != "") { ?>
												<option value="<?=$pic['showid']?>" selected><?=itemName($pic['showid'])?></option>
											<?php } else { ?>
												<option value="" selected>--select--</option>
											<?php } ?>

											<?php


												$sql4 = "SELECT * FROM pics WHERE (catid = '413' OR catid = '414') ORDER BY sortBy ASC";

												$result4 = mysql_query($sql4);

												if (!$result4) {
														die('Could not query:' . mysql_error());
														exit;
												}

												for ($showCount = 1; $show = mysql_fetch_array($result4); ++$showCount) {

													$thisID = $show['id'];

											?>

												<option value="<?=$show['id']?>"><?=$show['title']?></option>

											<?php } ?>

										</select><br />

									<?php } ?>

									<?php if ($catID != 417 AND $catID != 420) { ?>

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

									<?php } else { ?>

										<input type="hidden" name="moveCat[]" class="moveCat" value="<?=$pic['catid']?>">

									<?php } ?>

									<div style="position: absolute; bottom: -24px; left: 0px; font-size: 10px; width: 100%;">
										<a href="#" class="ajax" id="detail.php?picID=<?=$pic['id']?>&category=<?=$catID?>&imgName=<?=$pic['img']?>" onclick="return false;" style="float: left; color: #000; text-decoration: none;"><img src="images/icons/edit.png"> <span style="position: relative; top: -5px;">edit</span></a>
										<a href="delete_image.php?id=<?=$pic['id']?>&category=<?=$catID?>&imgName=<?=$pic['img']?>" class="delete" style="float: right; color: #000; text-decoration: none;"><img src="images/icons/delete.png"> <span style="position: relative; top: -5px;">delete</span></a>
									</div>

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

			<!--
			<?php if ($_GET['show'] != "all") { ?>
				<br /><br /><a href="#" id="manage_images.php?category=<?=$catID?>&show=all" class="ajax" onclick="return false">View All</a><br /><br />
			<?php } ?>
			-->

		</td>
	</tr>
</table>

<script type="text/javascript">
	imageLoad();
</script>