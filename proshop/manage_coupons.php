<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	$catID = $_GET['catID'];
	$coupons = getCoupons();

?>

<script type="text/javascript">
$(document).ready(function(){

	// add new coupon
	$(".add").click(function() {

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
			 var data = {"code" : code, "numuses" : numuses, "startDate" : startDate, "endDate" : endDate, "discount" : discount, "discountType" : discountType, "custType" : custType};
			 var data_encoded = JSON.stringify(data);

			 $.ajax({
			   type: "POST",
			   url: "addCoupon.php",
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

	$(".addLink").click(function() {

		$("#addCoupon").slideToggle();

	});

});
</script>

<div class="infoPage">

		<div id="alertDivPic" style="float: right; margin: 9px 5px 0 0;"></div>
		<p style="float: left;"><span class="head">Discount Coupons:</span></p>
		<div style="float: left; padding-left: 10px; padding-top: 10px;">
			<a href="#" class="addLink">Add New</a>
		</div>

		<div id="breadcrumb">
			<a href="main.php">DASHBOARD</a> > <span class="pageOn">DISCOUNT COUPONS</span>
		</div>

		<div id="line" style="width: 90%; margin-bottom: 2px; clear: both;"></div>
		<div id="line" style="width: 90%;"></div><br />

		<div id="editCoupon" style="display: none;">



		</div>
		
		<div id="addCoupon" style="display: none;">

			<form name="coupon">

				<input style="float: left; margin-right: 25px;" type="text" name="code" id="code" value="Discount Code" onfocus="if (this.value == 'Discount Code') this.value='';" onblur="if (this.value == '') this.value='Discount Code';">
				<input style="float: left;" type="text" name="numuses" id="numuses" value="Number of Uses" onfocus="if (this.value == 'Number of Uses') this.value='';" onblur="if (this.value == '') this.value='Number of Uses';">
				<div style="clear: both; height: 15px;"></div>

				<input style="float: left; margin-right: 25px;" type="text" name="startDate" id="startDate" value="Start Date" onfocus="if (this.value == 'Start Date') this.value='';" onblur="if (this.value == '') this.value='Start Date';">
				<input style="float: left;" type="text" name="endDate" id="endDate" value="End Date" onfocus="if (this.value == 'End Date') this.value='';" onblur="if (this.value == '') this.value='End Date';">
				<div style="clear: both; height: 15px;"></div>

				<input style="float: left; margin-right: 25px;" type="text" name="discount" id="discount" value="Discount Amount" onfocus="if (this.value == 'Discount Amount') this.value='';" onblur="if (this.value == '') this.value='Discount Amount';">
				<select name="discountType" id="discountType">
					<option value="percentage">percentage</option>
					<option value="dollar">dollar</option>
					<option value="shipping">free shipping (ground only)</option>
				</select>
				<div style="clear: both; height: 15px;"></div>

				<select name="custType" id="custType">
					<option value="retail">retail</option>
					<option value="wholesale">wholesale</option>
				</select>
				<div style="clear: both; height: 15px;"></div>

				<button id="btn" class="add" style="margin-bottom: 15px; float: left; margin-right: 15px;">Create Discount Code</button>

				<div style="clear: both;"></div>

			</form>

		</div>

		<div id="manageOrders" style="margin-top: 20px; margin-bottom: 20px;">

			<table border="0" cellpadding="0" cellspacing="0" style="width: 90%;">
				<tr>
					<td align="left" valign="top" class="dataHeader cufon">
						Discount Code
					</td>
					<td align="left" valign="top" class="dataHeader cufon">
						Discount
					</td>
					<td align="left" valign="top" class="dataHeader cufon">
						Type
					</td>
					<td align="left" valign="top" class="dataHeader cufon">
						Start Date
					</td>
					<td align="left" valign="top" class="dataHeader cufon">
						End Date
					</td>
					<td align="left" valign="top" class="dataHeader cufon">
						Max Uses
					</td>
					<td align="left" valign="top" class="dataHeader cufon">
						# of Uses
					</td>
				</tr>

				<?php for ($count = 1; $coupon = mysql_fetch_array($coupons); ++$count) { ?>

					<?php

						if ($count % 2) {
							$class = "oddRow";
						} else {
							$class = "evenRow";
						}

					?>

					<tr>
						<td align="left" valign="top" class="<?=$class?> cufon">
							<b><a href="#" id="editCoupon.php?id=<?=$coupon['id']?>" class="breadcrumb"><?php echo $coupon['code']; ?></a></b>
						</td>
						<td align="left" valign="top" class="<?=$class?> cufon">
							<?php if ($coupon['discountType'] === "percentage") { ?>

								<b><?php echo $coupon['discount']; ?>%</b>

								<?php } elseif ($coupon['discountType'] === "dollar") { ?>

									<b>$<?php echo $coupon['discount']; ?></b>

									<?php } else { ?>

										<b>Free Shipping</b>

							<?php } ?>
						</td>
						<td align="left" valign="top" class="<?=$class?> cufon">
							<?php echo $coupon['custType']; ?>
						</td>
						<td align="left" valign="top" class="<?=$class?> cufon">
							<?php echo timeDate($coupon['startDate']); ?>
						</td>
						<td align="left" valign="top" class="<?=$class?> cufon">
							<?php echo timeDate($coupon['endDate']); ?>
						</td>
						<td align="left" valign="top" class="<?=$class?> cufon">
							<?php echo $coupon['uses']; ?>
						</td>
						<td align="left" valign="top" class="<?=$class?> cufon">
							<?php echo countCoupon($coupon['code']); ?>
						</td>
					</tr>

				<?php } ?>

			</table>

		</div>

	</div>

</div>