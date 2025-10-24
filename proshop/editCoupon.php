<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';
	
	$couponID = $_GET['id'];
	$coupon = getCoupon($couponID);

?>

<script>

	// add new coupon
	$(".edit").click(function() {

		var theID = $("input#id").val();
		var code = $("input#code").val();
		var numuses = $("input#numuses").val();
		var startDate = $("input#startDate").val();
		var endDate = $("input#endDate").val();
		var discount = $("input#discount").val();
		var discountType = $("#discountType option:selected").text();
		var custType = $("#custType option:selected").text();

		var alertMsg = "The following REQUIRED fields\nhave been left empty:\n";
		if (code === "" || code === "Discount Code") alertMsg += "\nDiscount Code";
		if (discount === "" || discount === "Discount Amount") alertMsg += "\nDiscount Amount";
		if (alertMsg != "The following REQUIRED fields\nhave been left empty:\n") {
			alert(alertMsg);
			return false;
		} else {

			// handle ajax post here
			 var data = {"id" : theID, "code" : code, "numuses" : numuses, "startDate" : startDate, "endDate" : endDate, "discount" : discount, "discountType" : discountType, "custType" : custType};
			 var data_encoded = JSON.stringify(data);

			 $.ajax({
			   type: "POST",
			   url: "editCoupon2.php",
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
	
	//Examples of how to assign the ColorBox event to elements
	$(".delete").colorbox({
		width:"60%",
		height:"60%",
		iframe:true});

</script>

<div class="infoPage">

	<div id="alertDivPic" style="float: right; margin: 9px 5px 0 0;"></div>
	<p style="float: left;"><span class="head">Update Discount Coupon:</span></p>

	<div id="breadcrumb">
		<a href="main.php">DASHBOARD</a> > <a href="#" id="manage_coupons.php" class="breadcrumb">DISCOUNT COUPONS</a> > <span class="pageOn">EDIT DISCOUNT COUPON</span>
	</div>

	<div id="line" style="width: 90%; margin-bottom: 2px; clear: both;"></div>
	<div id="line" style="width: 90%;"></div><br />

	<form name="edit-coupon">
	<input type="hidden" name="id" id="id" value="<?=$couponID?>">
	
	<input style="float: left; margin-right: 25px;" type="text" name="code" id="code" value="<?=$coupon['code']?>" onfocus="if (this.value == 'Discount Code') this.value='';" onblur="if (this.value == '') this.value='Discount Code';">
	<input style="float: left;" type="text" name="numuses" id="numuses" value="<?=$coupon['uses']?>" onfocus="if (this.value == 'Number of Uses') this.value='';" onblur="if (this.value == '') this.value='Number of Uses';">
	<div style="clear: both; height: 15px;"></div>

	<input style="float: left; margin-right: 25px;" type="text" name="startDate" id="startDate" value="<?=strftime("%m/%d/%Y", $coupon['startDate'])?>" onfocus="if (this.value == 'Start Date') this.value='';" onblur="if (this.value == '') this.value='Start Date';">
	<input style="float: left;" type="text" name="endDate" id="endDate" value="<?=strftime("%m/%d/%Y", $coupon['endDate'])?>" onfocus="if (this.value == 'End Date') this.value='';" onblur="if (this.value == '') this.value='End Date';">
	<div style="clear: both; height: 15px;"></div>

	<input style="float: left; margin-right: 25px;" type="text" name="discount" id="discount" value="<?=$coupon['discount']?>" onfocus="if (this.value == 'Discount Amount') this.value='';" onblur="if (this.value == '') this.value='Discount Amount';">
	<select name="discountType" id="discountType">
		<option value="<?=$coupon['discountType']?>"><?=$coupon['discountType']?></option>
		<option value="percentage">percentage</option>
		<option value="dollar">dollar</option>
		<option value="shipping">free shipping (ground only)</option>
	</select>
	<div style="clear: both; height: 15px;"></div>

	<select name="custType" id="custType">
		<option value="<?=$coupon['custType']?>"><?=$coupon['custType']?></option>
		<option value="retail">retail</option>
		<option value="wholesale">wholesale</option>
	</select>
	<div style="clear: both; height: 15px;"></div>

	<button id="btn" class="edit" style="margin-bottom: 15px; float: left; margin-right: 15px;">Update Discount Code</button>
	<span style="float: left; position: relative; top: 3px; left: 5px;"><a href="delete_coupon.php?id=<?=$couponID?>&codeName=<?=$coupon['code']?>" class="delete">Delete Code</a></span>

	<div style="clear: both;"></div>

	</form>
	
</div>