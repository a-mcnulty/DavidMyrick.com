<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	$catID = $_GET['catID'];
	$type = "product";
	$keyword = $_GET['keyword'];

?>

<script type="text/javascript">
$(document).ready(function(){

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

		 var data = {"category" : category, "parentID" : parentID, "picID" : picIDArray, "img" : imgArray, "title" : titleArray, "captions" : captionsArray, "picURL" : picURLArray, "shortDesc" : shortDescArray, "price" : priceArray, "wprice" : wpriceArray, "moveCat" : moveCatArray, "copyCat" : copyCatArray, "product" : "yes"};
		 var data_encoded = JSON.stringify(data);

		 $.ajax({
		   type: "POST",
		   url: "updateImages.php",
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
			var order = $(this).sortable("serialize") + '&action=updateRecordsListings&catID=<?=$catID?>';
			$.post("updateImgOrder.php", order, function(theResponse){
				$("#alertDivPic").show();
				$("#alertDivPic").html(theResponse);

				// hide the element
				$("#alertDivPic").fadeOut(3000);
			});
		}
		});
	});

	$(".delete").colorbox({
		width:"402px",
		height:"227px",
		iframe: true,
		opacity: ".95"
	});

	// in page ajax links

	$("A.ajax").click(function() {

		var thePage = $(this).attr("id");

		loadPage(thePage);

	});

});
</script>

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

						if (isset($keyword)) {
						
							$query2 = "SELECT pics.* FROM pics LEFT JOIN content ON content.picid = pics.id JOIN cat_list ON cat_list.id = pics.catid JOIN cat_pics ON cat_pics.picid = pics.id WHERE (title LIKE '%"  . $keyword . "%' OR content LIKE '%"  . $keyword . "%' OR caption LIKE '%"  . $keyword . "%') AND contentType = 'category' ORDER BY cat_pics.sortBy ASC";
							
						} else {
						
							if ($catID != "") {

								$query2 = "SELECT pics.* FROM pics JOIN cat_pics ON pics.id = cat_pics.picid WHERE cat_pics.catid = '$catID' ORDER BY cat_pics.sortBy ASC";

							} else {

								$query2 = "SELECT pics.* FROM pics LEFT JOIN cat_list ON cat_list.id = pics.catid WHERE cat_list.contentType = 'category' ORDER BY cat_list.sortBy ASC, pics.sortBy ASC";

							}
							
						}

						$result2 = mysql_query($query2);

						if (!$result2) {
								die('Could not query:' . mysql_error());
								exit;
						}

						for ($count = 1; $pic = mysql_fetch_array($result2); ++$count) {
						

					?>

							<li class="picHolder" id="recordsArray_<?php echo $pic['id']; ?>">
								<a href="detail.php?picID=<?=$pic['id']?>&category=<?=$catID?>&imgName=<?=$pic['img']?>&type=product">
									<img src="<?php echo getOption("imagePath"); ?><?=$pic['img']?>?fit=crop&w=200" width="200" height="200" border="0" style="position: absolute; top: 0px; left: 0px; z-index: 1;"><br />
								</a>
								<input type="hidden" class="picID" name="picID[]" value="<?=$pic['id']?>">
								<input type="hidden" class="img" name="img[]" value="<?=$pic['img']?>">

								<div class="picCover" style="display: none;"></div>

								<div class="picInfo" style="display: none;">

									<?php if (getOption("title") === "yes") { ?>
										<input type="text" name="title[]" class="titleField" value="<?=$pic['title']?>"><br />
									<?php } ?>

									<?php if (getOption("caption") === "yes") { ?>
										<input type="text" name="captions[]" class="captions" value="<?=$pic['caption']?>"><br />
									<?php } ?>

									<!--
									<?php if (getOption("picURL") === "yes") { ?>
										<input type="text" name="picURL[]" class="picURL" value="<?=$pic['picURL']?>"><br />
									<?php } ?>
									-->

									<?php if (getOption("shortDesc") === "yes") { ?>
										<?php if ($catID == 324) { ?>
											<textarea name="shortDesc[]" class="shortDesc" style="width: 150px; height: 30px; font-size: 12px; font-family: Helvetica, Arial;"><?=$pic['shortDesc']?></textarea><br />
										<?php } ?>
									<?php } ?>

									<?php if (getOption("price") === "yes") { ?>
										<span class="smallGray">retail price:</span>
										<input type="text" name="price[]" class="price" style="width: 50px; margin-left: 49px;" value="<?=$pic['price']?>"><br />

										<span class="smallGray">wholesale price:</span>
										<input type="text" name="wPrice[]" class="wPrice" style="width: 50px; margin-left: 19px;" value="<?=$pic['wPrice']?>"><br />
									<?php } ?>

									<span class="smallGray">move to:</span>
									<select name="moveCat[]" class="moveCat" style="font-size: 10px; width: 122px;">
										<option value="<?=$catID?>" selected>--select--</option>

										<?php

											$myCats = getShopCategories();
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
									<select name="copyCat[]" class="copyCat" style="font-size: 10px; width: 122px; margin-left: 2px;">
										<option value="" selected>--select--</option>

										<?php

											$myCats = getShopCategories();
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

									<div style="position: absolute; bottom: -24px; left: 0px; font-size: 10px; width: 100%;">
										<a href="#" class="ajax" id="detail.php?picID=<?=$pic['id']?>&category=<?=$catID?>&imgName=<?=$pic['img']?>&type=product" onclick="return false;" style="float: left; color: #000; text-decoration: none;"><img src="images/icons/edit.png"> <span style="position: relative; top: -5px;">edit</span></a>
										<a href="delete_image.php?id=<?=$pic['id']?>&category=<?=$catID?>&imgName=<?=$pic['img']?>&type=<?=$type?>" class="delete" style="float: right; color: #000; text-decoration: none;"><img src="images/icons/delete.png"> <span style="position: relative; top: -5px;">delete</span></a>
									</div>

								</div>

							</li>

					<?php

						}

					?>

				</ul>

			</div>

			<div id="clear"></div>

			<button id="btn" class="update" style="margin-top: 10px;">Save Changes</button>
			</form>

		</td>
	</tr>
</table>