<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	$id = $_GET['id'];
	$order = getOrder($id);
	$products = getOrderProducts($id);
	$customer = getCustomer($order['customerID']);
	$customerType = $customer['type'];
	
	// customer email copy with shortCode check
	
	$emailID = 518;
	$emailText = getPageText($emailID);
	$emailText = str_replace("<p>", "", $emailText);
	$emailText = str_replace("</p>", "\n", $emailText);
	$emailText = str_replace("<br />", "\n", $emailText);
	$emailText = str_replace("xORDERIDx", $id, $emailText);
	$emailText = str_replace("xFNAMEx", $customer['fname'], $emailText);
	$emailText = str_replace("xTRACKINGNUMBERx", $order['trackingNumber'], $emailText);

?>

<script type="text/javascript">
$(document).ready(function(){

	$(".openerTitle").click(function() {

		$(".contents").slideToggle();

	});

	//Examples of how to assign the ColorBox event to elements
	$(".delete").colorbox({
		width:"60%",
		height:"60%",
		iframe:true});

	$(".update").click(function() {

		var orderID = $("input#orderID").val();
		var status = $("#status option:selected").val();
		var comments = $("#comments").val();
		var notify = $('input[name=notify]:checked').val();
		//var addComments = $('input[name=addComments]:checked').val();

		// handle ajax post here
		 var data = {"orderID" : orderID, "status" : status, "comments" : comments, "notify" : notify};
		 var data_encoded = JSON.stringify(data);

		 $.ajax({
		   type: "POST",
		   url: "addOrderStatus.php",
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


});
</script>

<div class="infoPage">

	<div id="alertDivPic" style="float: right; margin: 9px 5px 0 0;"></div>
	<p style="float: left;"><span class="head">Order Number: <?=$id?></span></p>

		<div id="breadcrumb">
			<a href="main.php">DASHBOARD</a> > <a href="#" id="manage_orders.php" class="breadcrumb">ORDERS</a> > <span class="pageOn">ORDER DETAIL</span>
		</div>

		<div id="line" style="width: 90%; margin-bottom: 2px; clear: both;"></div>
		<div id="line" style="width: 90%;"></div><br />

		<div id="manageOrders" style="margin-top: 20px; margin-bottom: 20px;">

			<div style="width: 23%; display: inline-block;">
				<div class="dataBox" style="margin-left: 0px;">
					<img src="images/icons/customer.png" style="position: absolute; top: 10px; right: 8px;">
					<span class="heading cufon">CUSTOMER INFO</span><br />
					<div class="grayLine"></div>
					<div class="cufon">
						<?php if ($customer['company'] != "") { ?>Company: <?=$customer['company']?><br /><?php } ?>
						Name: <?=$customer['fname']?> <?=$customer['lname']?><br />
						<a href="mailto:<?=$customer['email']?>"><?=$customer['email']?></a><br />
						<?=$customer['phone']?><br />
						Payment Method: <?=$order['paymentMethod']?><br />
					</div>
				</div>
			</div>

			<div style="width: 23%; display: inline-block;">
				<div class="dataBox">
					<span class="heading cufon">SHIPPING ADDRESS</span><br />
					<div class="grayLine"></div>
					<div class="cufon">
						Name: <?=$order['shipfname']?> <?=$order['shiplname']?><br />
						<?=$order['shipaddress']?> <?php if ($order['shipaddress2'] != "APT. / SUITE / UNIT") { ?><?=$order['shipaddress2']?><?php } ?><br />
						<?=$order['shipcity']?>, <?=$order['shipstate']?> <?=$order['shipzip']?>
						<?=$order['shipcountry']?><br />
					</div>
				</div>
			</div>

			<div style="width: 23%; display: inline-block;">
				<div class="dataBox">
					<span class="heading cufon">BILLING ADDRESS</span><br />
					<div class="grayLine"></div>
					<div class="cufon">
						Name: <?=$order['billfname']?> <?=$order['billlname']?><br />
						<?=$order['billaddress']?> <?php if ($order['billaddress2'] != "APT. / SUITE / UNIT") { ?><?=$order['billaddress2']?><?php } ?><br />
						<?=$order['billcity']?>, <?=$order['billstate']?> <?=$order['billzip']?>
						<?=$order['billcountry']?><br />
					</div>
				</div>
			</div>

			<div style="width: 29%; display: inline-block; float: right; text-align: right;">
				<div class="dataBox" style="background: transparent; font-size: 12px; width: 175px;">
					<span class="heading cufon">ORDER STATUS</span><br />
					<div class="grayLine"></div>
					<div class="cufon">

						<b><?php echo strtoupper(getStatus($order['status'])); ?></b><br />

						<a href="printPacking.php?id=<?=$order['id']?>" target="_blank">PRINT PACKING SLIP</a><br />
						<a href="printInvoice.php?id=<?=$order['id']?>" target="_blank">PRINT INVOICE</a><br />

					</div>
				</div>
			</div>

			<div style="clear: both; height: 40px;"></div>

			<table border="0" cellpadding="0" cellspacing="0" style="width: 90%;">
				<tr>
					<td align="left" valign="top" class="dataHeader cufon">
						PRODUCT
					</td>
					<!--
					<td align="left" valign="top" class="dataHeader cufon">
						STYLE NO.
					</td>
					-->
					<td align="left" valign="top" class="dataHeader cufon">
						PRICE
					</td>
					<td align="left" valign="top" class="dataHeader cufon">
						QTY
					</td>
					<td align="left" valign="top" class="dataHeader cufon">
						SUBTOTAL
					</td>
				</tr>

				<?php for ($count = 1; $product = mysql_fetch_array($products); ++$count) { ?>

					<?php

						if ($count % 2) {
							$class = "oddRowSmall";
						} else {
							$class = "evenRowSmall";
						}

						$prodDetail = getProductDetail($product['prodID']);

					?>

					<tr>
						<td align="left" valign="top" class="<?=$class?>">
							<b><a href="lightbox_image.php?img=<?=$prodDetail['img']?>" class="delete"><?php echo $prodDetail['title']; ?></a></b><br />

							<?php

								// retrieve option values from cart
								
								$optionStr = $product['optionStr'];
								$optionArray = explode(",", $optionStr);
									
								// FIRST ITEM IN ARRAY IS SIZE, SECOND IS COLOR. NEW FORMAT IS THE SET:OPTION STRING
								
								$sizeArray = explode(":", $optionArray[1]);
								$colorArray = explode(":", $optionArray[2]);
								
								echo '<em style="font-size: 12px;">color: ' . strtolower(itemName($colorArray[1])) . ' | size: ' . strtolower(itemName($sizeArray[1])) . '</em><br />';

							?>

						</td>
						<!--
						<td align="left" valign="top" class="<?=$class?>">
							<?php echo $prodDetail['caption']; ?>
						</td>
						-->
						<td align="left" valign="top" class="<?=$class?>">							
							<b>$<?php echo $product['price']; ?></b>
						</td>
						<td align="left" valign="top" class="<?=$class?>">
							<b><?php echo $product['qty']; ?></b>
						</td>
						<td align="left" valign="top" class="<?=$class?>">
							<b>$<?php echo number_format(($product['price'] * $product['qty']), 2, '.', ','); ?></b>
						</td>
					</tr>

				<?php } ?>

			</table>

			<div style="text-align: left; width: 49%; margin-top: 20px; float: left; line-height: 22px;">
				<div style="margin-left: 10px;">
					<b>ORDER COMMENTS:</b><br />
					<?=$order['comments']?><br /><br />
				</div>
			</div>

			<div style="text-align: right; float: left; width: 40%; margin-top: 20px; line-height: 22px;">
				<span class="dataHeader">subtotal: $<?php echo getOrderSubTotal($order['id']); ?></span><br />
				<span class="dataHeader">tax: $<?php echo number_format($order['tax'], 2, '.', ','); ?></span><br />
				<span class="dataHeader">shipping: (<?php echo $order['shippingMethod']; ?>) $<?php echo number_format($order['shippingCost'], 2, '.', ','); ?></span><br />
				<?php if ($order['discountAmount'] > 0) { ?>
					<span class="dataHeader">discount: (<?php echo $order['discountCode']; ?>) -$<?php echo number_format($order['discountAmount'], 2, '.', ','); ?></span><br />
				<?php } ?>
				<br /><span class="dataHeader"><b>TOTAL: $<?php echo getOrderTotal($order['id']); ?></b></span><br />
			</div>

			<div style="clear: both;"></div>

			<div class="opener" style="width: 90%; margin-top: 20px;">
				<span class="openerTitle" style="cursor: pointer;">ORDER HISTORY / COMMENTS</span>
				<span class="dirArrow"><img src="images/icons/openArrow.png" style="border: 0px;"></span>
			</div>
			<div class="contents" style="display: none;">
				<div class="contentsInner">
					<table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
						<tr>
							<td align="center" width="150" valign="top" class="openerHeader cufon">
								DATE
							</td>
							<td align="left" width="200" valign="top" class="openerHeader cufon">
								CUSTOMER NOTIFIED
							</td>
							<td align="left" width="100" valign="top" class="openerHeader cufon">
								STATUS
							</td>
							<td align="left" valign="top" class="openerHeader cufon">
								COMMENTS
							</td>
						</tr>

						<?php

							$history = getOrderHistory($id);
							for ($count = 1; $row = mysql_fetch_array($history); ++$count) {

						?>

							<tr>
								<td align="center" valign="top" class="openerRow">
									<?=displayDateTime($row['dateAdded'])?>
								</td>
								<td align="left" valign="top" class="openerRow">
									<?php if ($row['notified'] === "yes") { ?>
										<img src="images/icons/check.png" style="margin-left: 74px;">
									<?php } else { ?>
										<img src="images/icons/x.png" style="margin-left: 74px;">
									<?php } ?>
								</td>
								<td align="left" valign="top" class="openerRow">
									<?php echo ucwords(getStatus($row['status'])); ?>
								</td>
								<td align="left" valign="top" class="openerRow">
									<?=nl2br($row['comments'])?>
								</td>
							</tr>

						<?php

							}

						?>

					</table><br /><br />

					<span class="openerHeader">ADD COMMENTS</span><br /><br />

					<form name="commentForm">
					<input type="hidden" name="orderID" id="orderID" value="<?=$id?>">
					<div style="float: left">
					
						<?php
						
							$shipping = $order['shippingMethod'];
							if (stristr("UPS", $shipping)) {
								$provider = "UPS";
								$providerLink = "http://ups.com";
							} else {
								$provider = "FEDEX";
								$providerLink = "http://fedex.com";
							}
						
						?>

<textarea name="comments" id="comments" style="width: 380px; height: 70px; font-size: 10px; font-family: Helvetica;">
<?=$emailText?>
</textarea>

					</div>

					<div style="float: left; margin-left: 37px; color: #767676; font-size: 10px; line-height: 25px;">

						STATUS:
						<select name="status" id="status" style="font-size: 10px; margin-bottom:8px;">
							<option value="<?=$order['status']?>"><?=ucwords(getStatus($order['status']))?></option>

							<?php

								$status = getStatuses();
								for ($count = 1; $row = mysql_fetch_array($status); ++$count) {

							?>

									<option value="<?=$row['id']?>"><?=ucwords($row['name'])?></option>

							<?php

								}

							?>

						</select><br />
						NOTIFY CUSTOMER: <input type="checkbox" name="notify" id="notify" value="yes"><br />
						<!--ADD COMMENTS: <input type="checkbox" name="addComments" id="addComments" value="yes"><br />-->
						<button id="btn" class="update" style="margin-top: 12px;">ADD COMMENT</button>

					</div>

					<div style="clear: both;"></div>


					</form>
				</div>
			</div>

			<div style="clear: both; height: 20px;"></div>

		</div>

	</div>

</div>