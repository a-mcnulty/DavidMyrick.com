<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	$catID = $_GET['catID'];

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

		<div id="alertDivPic" style="float: right; margin: 9px 5px 0 0;"></div>
		<p style="float: left;"><span class="head">Manage Categories:</span></p>
		<p style="float: left; margin: 8px 0 0 10px;"><span class="addCat" style="cursor: pointer;">Add New Category</span></p> <img src="images/icons/add.png" class="addCat" style="float: left; margin: 6px 0 0 3px;">

		<div id="line" style="width: width: 100%; margin-bottom: 2px; clear: both;"></div>
		<div id="line" style="width: width: 100%;"></div><br />

		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td align="left" valign="top">

					<div style="display: none; float: left; height: 60px;" id="addCat">

						<form name="addCatForm" method="post">

						<div style="float: left; margin-right: 10px;">
							<input type="text" name="catName" id="addCatName" value="Category Name" onClick="this.value=''" class="smallField" style="width: 165px; height: 17px;">
						</div>

						<div style="float: left; margin-right: 10px;">
							<select name="parent" id="addCatParent" class="smallField" style="width: 165px; height: 24px;">
								<option value="0">Parent</option>

								<?php echo getShopCategories("select", "yes"); ?>

							</select>
						</div>

						<div style="float: left; margin-right: 10px;">
							<select name="status" id="addCatStatus" class="smallField" style="width: 165px; height: 24px;">
								<option value="">Status</option>
								<option value="live">Live</option>
								<option value="preview">Preview</option>
							</select>
						</div>

						<div style="float: left;">
							<button id="btn" class="add_new_cat">Add</button>
						</div>
						</form>

					</div>

					<div style="clear: both;"></div>

					<div id="manageCats">

						<ul style="list-style-type: none; width: 900px;">

							<?php $categories = getShopCategories("list"); ?>

							<?php

								for ($count = 1; $cat = mysql_fetch_array($categories); ++$count) {

									$thisCat = $cat['id'];
							?>

									<li class="dataRow" id="recordsArray_<?php echo $cat['id']; ?>">
										<div class="dataRowInner">


											<span class="catName"><?php echo strtoupper($cat['name']); ?></span>

								<?php

											// check for sub-categories

											$sql2 = "SELECT * FROM cat_list WHERE parentID = '$thisCat' ORDER BY sortBy ASC";

											$result2 = mysql_query($sql2);

								?>

											<ul id="sub_<?=$thisCat?>" class="subCat" style="display: none;">

								<?php

												for ($subcount = 1; $subcat = mysql_fetch_array($result2); ++$subcount) {

								?>

													<li id="recordsArray_<?php echo $subcat['id']; ?>">
														<div style="float: left;">
															<img src="images/icons/sortSmall.png" class="sortme" style="position: relative; top: 5px;"> <?php echo strtolower($subcat['subName']); ?><br />
														</div>

														<div style="position: relative; z-index: 300; float: right; margin-right: 75px;">
															<img src="images/icons/edit.png" class="editSubCat" id="<?php echo $subcat['id']; ?>">
															<a href="delete_category.php?id=<?=$subcat['id']?>&pageName=<?=$subcat['subName']?>" class="delete"><img src="images/icons/delete.png" style="position: relative; z-index: 500;"></a>
														</div>
														<div style="clear: both;"></div>

													</li>

								<?php

												}

								?>

											</ul>

											<div class="catClick" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; background: transparent; z-index: 200;">&nbsp;</div>

										</div>

										<div style="position: absolute; top: 21px; right: 27px; z-index: 300;">
											<img src="images/icons/edit.png" class="editCat" id="<?php echo $cat['id']; ?>">
											<a href="delete_category.php?id=<?=$cat['id']?>&pageName=<?=$cat['name']?>" class="delete"><img src="images/icons/delete.png" style="position: relative; z-index: 500;"></a>
										</div>

										<div class="sortHolder" style="position: absolute; top:14px; right: 0px;">
											<img src="images/icons/sort.png" class="sort" style="display: none;">
										</div>

									</li>

							<?php

								}

							?>

						</ul>

					</div>

				</td>
			</tr>
		</table>
		
	</div>
	
</div>

<script type="text/javascript">
$(document).ready(function(){

	$("#storeTopMenu").css('display', 'block');
	$(".topSub").css('display', 'block');
	$("#storeList").css('display', 'block');
	
	$(".dataRow").hoverIntent(
	  function () {
		$(this).children().children(".sort").fadeTo(100, 1);
	  },
	  function () {
		$(this).children().children(".sort").fadeTo(100, 0);
	  }
	);

	$(".catClick, .catName").click(function() {

		$(this).parent().children(".subCat").slideToggle();

	});

	// sort parent categories
	$(function() {
		$("#manageCats ul").sortable({ opacity: 0.6, handle: '.sort', cursor: 'move', update: function() {
			var order = $(this).sortable("serialize") + '&action=updateRecordsListings';
			$.post("updateCatOrder.php", order, function(theResponse){
				$("#alertDivPic").show();
				$("#alertDivPic").html(theResponse);

				// hide the element
				$("#alertDivPic").fadeOut(3000);
			});
		}
		});
	});

	// sort sub categories
	$(function() {
		$(".dataRowInner ul").sortable({ opacity: 0.6, handle: '.sortme', cursor: 'move', update: function() {
			var order = $(this).sortable("serialize") + '&action=updateRecordsListings';
			$.post("updateCatOrder.php", order, function(theResponse){
				$("#alertDivPic").show();
				$("#alertDivPic").html(theResponse);

				// hide the element
				$("#alertDivPic").fadeOut(3000);
			});
		}
		});
	});

	//Examples of how to assign the ColorBox event to elements
	$(".delete").colorbox({
		width:"60%",
		height:"60%",
		iframe:true});

	// add new category
	$(".addCat").click(function() {

		$("#addCat").slideToggle();

	});

	// add new category

	$(".add_new_cat").click(function() {

		var catName = $("input#addCatName").val();
		var status = $("#addCatStatus option:selected").text();
		var parentID = $("#addCatParent option:selected").val();

		var alertMsg = "The following REQUIRED fields\nhave been left empty:\n";
		if (catName === "" || catName === "Category Name") alertMsg += "\nName of Category";
		if (status === "") alertMsg += "\nPage Status";
		if (alertMsg != "The following REQUIRED fields\nhave been left empty:\n") {
			alert(alertMsg);
			return false;
		} else {

			// handle ajax post here
			 var data = {"catName" : catName, "parentID" : parentID, "pageType" : "images", "status" : status, "contentType" : "category"};
			 var data_encoded = JSON.stringify(data);

			 $.ajax({
			   type: "POST",
			   url: "addCategory.php",
			   data: {
				 "myContent" : data_encoded
			   },
			   success: function(response) {

					loadPage(response);
					$('#topMenu').load("includes/topMenu.php?showTop=store&showSub=products", function() {
						parent.Cufon.refresh();
					});

			   },
			   error: function(){
				 alert('failure');
			   }
			});
			return false;
		}

	});

	// edit category

	$(".editCat").click(function() {

		var catID = $(this).attr("id");

		$(this).parent().parent().append('<div id="catEdit_'+catID+'" style="margin-top: 16px;"></div>');
		$.ajax({
		  url: "includes/catFormEditStore.php?catID="+catID,
		  success: function(data) {
			$('#catEdit_'+catID).html(data);
			//Cufon.refresh();
		  }
		});

	});

	$(".editSubCat").click(function() {

		var catID = $(this).attr("id");

		$(this).parent().parent().append('<div id="catEdit_'+catID+'" style="margin-top: 16px;"></div>');
		$.ajax({
		  url: "includes/catFormEditStore.php?catID="+catID+"&sub=yes",
		  success: function(data) {
			$('#catEdit_'+catID).html(data);
			//Cufon.refresh();
		  }
		});

	});

	// in page ajax links

	$("A.ajax").click(function() {

		var thePage = $(this).attr("id");

		loadPage(thePage);

	});

});
</script>

</body>
</html>