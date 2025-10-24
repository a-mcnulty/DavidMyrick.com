<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	$catID = $_GET['catID'];
	$keyword = $_GET['keyword'];
	$sort = $_GET['sort'];
	if (stristr($sort, "ASC")) {
		$theDir = "DESC";
	} else {
		$theDir = "ASC";
	}
	if (!isset($sort)) {
		$sort = "id DESC";
	}
	$orders = getOrders($keyword, $sort);

?>

<script type="text/javascript">
$(document).ready(function(){

	$(".search").click(function() {

		var keyword = $("input#keyword").val();

		loadPage("manage_orders.php?keyword="+keyword);

		return false;

	});

	$(".sortBy").click(function() {

		var sort = $(this).attr("id");

		loadPage("manage_orders.php?sort="+sort);

		return false;

	});
	
	// handle tracking numbers
	
	$('.trackingNumber').focus(function() {
	
		$('.updateTracking').css('background', '#3e4e69');
	
	});
	
	$(".updateTracking").click(function() {
		 
		 var orderIDArray = [];
		 $('.orderID').each(function() {
			var order = $(this).val();
			orderIDArray.push(order);
		 });

		var trackingArray = [];
		 $('.trackingNumber').each(function() {
			var trackNum = $(this).val();
			trackingArray.push(trackNum);
		 });

		// handle ajax post here
		 var data = {"orderIDArray" : orderIDArray, "trackingArray" : trackingArray};
		 var data_encoded = JSON.stringify(data);
		 
		 $('#pageContent').html('<div style="text-align: center; width: 100%; margin-top: 25%; color: #ccc;"><img src="images/ajax-loader.gif"><p>Loading...</p></div>');

		 $.ajax({
		   type: "POST",
		   url: "updateTracking.php",
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
	
	// update order status
	$('.statusUpdate').change(function() {
	
		var orderID = $(this).data('orderid');
		var statusID = $(this).val();
		
		$.post( "updateOrderStatus.php", { orderID: orderID, statusID: statusID })
			.done(function( data ) {
				
			});
	
	});
	
	// bulk printing
	$('.print').click(function() {
	
		$('.printSelect').css('background', '#3e4e69');
	
	});
	
	$('.printSelect').click(function() {
	
		var orderIDStr = "";
		$('.print').each(function() {
			if ( $(this).attr('checked') ) {
			
				var orderID = $(this).data('orderid');
				orderIDStr += orderID+",";
				
			}
			
		});
		
		window.open('printDocs.php?id='+orderIDStr,'_blank');
		
		/*
		
		// handle ajax post here
		var data = {"orderIDArray" : orderIDArray};
		var data_encoded = JSON.stringify(data);
		 
		//$('#pageContent').html('<div style="text-align: center; width: 100%; margin-top: 25%; color: #ccc;"><img src="images/ajax-loader.gif"><p>Loading...</p></div>');

		$.ajax({
		   type: "POST",
		   url: "printDocs.php",
		   data: {
			 "myContent" : data_encoded
		   },
		   success: function(response) {

				var win = window.open();
				win.document.write(response);

		   },
		   error: function(){
			 alert('failure');
		   }
		});
		
		*/
		return false;
	
	});

});
</script>

<div class="infoPage">

	<div id="alertDivPic" style="float: right; margin: 9px 5px 0 0;"></div>
	<p style="float: left;"><span class="head">Orders:</span></p>

		<div id="breadcrumb">
			<a href="main.php">DASHBOARD</a> > <span class="pageOn">ORDERS</span>
		</div>

		<div id="line" style="width: 90%; margin-bottom: 2px; clear: both;"></div>
		<div id="line" style="width: 90%;"></div><br />
		
		<div id="tracking" style="margin-top: 20px; float: left;">
			<button id="btn" class="updateTracking" style="float: left; background: #dfdfdf; padding: 10px; height: auto;">UPDATE TRACKING NUMBERS</button>
		</div>
		
		<div id="printer" style="margin-top: 20px; margin-left: 5px; float: left;">
			<button id="btn" class="printSelect" style="float: left; background: #dfdfdf; padding: 10px; height: auto;">PRINT SELECTED INVOICES & PACKING SLIPS</button>
		</div>

		<div id="search" style="margin-top: 20px; margin-bottom: 20px; float: right; margin-right: 10%;">

			<form name="search" method="post">
			<input type="text" name="keyword" id="keyword" <?php if ($_GET['keyword'] != "") { ?>value="<?=$_GET['keyword']?>"<?php } else { ?>value="SEARCH BY NAME"<?php } ?> style="float: left; margin-right: 5px; height: 13px;" onfocus="if (this.value == 'SEARCH BY NAME') this.value='';" onblur="if (this.value == '') this.value='SEARCH BY NAME';">
			<button id="btn" class="search" style="float: left">SEARCH</button>

			</form>

		</div>

		<div id="manageOrders" style="margin-top: 20px; margin-bottom: 20px;">

			<table border="0" cellpadding="0" cellspacing="0" style="width: 90%;">
				<tr>
					<td align="left" valign="top" class="dataHeader cufon">
						<a href="#" class="sortBy" id="id <?=$theDir?>">Order #</a>
					</td>
					<td align="left" valign="top" class="dataHeader cufon">
						<a href="#" class="sortBy" id="subtotal <?=$theDir?>">Amount</a>
					</td>
					<td align="left" valign="top" width="135" class="dataHeader cufon">
						<a href="#" class="sortBy" id="billlname <?=$theDir?>">Customer</a>
					</td>
					<td align="left" valign="top" class="dataHeader cufon">
						<a href="#" class="sortBy" id="dateAdded <?=$theDir?>">Date</a>
					</td>
					<td align="left" valign="top" class="dataHeader cufon">
						<a href="#" class="sortBy" id="shipDate <?=$theDir?>">Shipped</a>
					</td>
					<td align="left" valign="top" class="dataHeader cufon">
						Tracking #
					</td>
					<td align="left" valign="top" class="dataHeader cufon">
						<a href="#" class="sortBy" id="status <?=$theDir?>">Status</a>
					</td>
					<td align="left" valign="top" class="dataHeader cufon">
						Print
					</td>
					<td></td>
				</tr>

				<?php for ($count = 1; $order = mysql_fetch_array($orders); ++$count) { ?>

					<?php

						if ($count % 2) {
							$class = "oddRow";
						} else {
							$class = "evenRow";
						}

						$customer = getCustomer($order['customerID']);

					?>

					<tr>
						<td align="left" valign="top" class="<?=$class?> cufon">
							<input type="hidden" name="orderID" class="orderID" value="<?=$order['id']?>">
							<b><a href="#" id="order_detail.php?id=<?=$order['id']?>" class="breadcrumb"><?php echo $order['id']; ?></a></b>
						</td>
						<td align="left" valign="top" class="<?=$class?> cufon">
							<b>$<?php echo getOrderTotal($order['id']); ?></b>
						</td>
						<td align="left" valign="top" class="<?=$class?> cufon">
							<?php echo $customer['fname']; ?> <?php echo $customer['lname']; ?>
						</td>
						<td align="left" valign="top" class="<?=$class?> cufon">
							<?php echo displayDate($order['dateAdded']); ?>
						</td>
						<td align="left" valign="top" class="<?=$class?> cufon">
							<?php 
							
								if (displayDate($order['shipDate']) != "11/30/99") {
									echo displayDate($order['shipDate']);
								}
								
							?>
						</td>
						<td align="left" valign="top" class="<?=$class?> cufon">
							<?php if ($order['trackingNumber'] != "") { ?>
								<?=$order['trackingNumber']?>
								<input type="hidden" name="trackingNumber" class="trackingNumber" value="ignore">
							<?php } else { ?>
								<input type="text" name="trackingNumber" class="trackingNumber"><br />
							<?php } ?>
						</td>
						<td align="left" valign="top" class="<?=$class?> cufon">
							<select name="statusUpdate" class="statusUpdate" data-orderid="<?=$order['id']?>">
								<option value="<?=$order['status']?>"><?php echo getStatus($order['status']); ?></option>
							
								<?php

									$status = getStatuses();
									for ($statcount = 1; $row = mysql_fetch_array($status); ++$statcount) {

								?>

										<option value="<?=$row['id']?>"><?=ucwords($row['name'])?></option>

								<?php

									}

								?>
							
							</select>
						</td>
						<td align="left" valign="top" class="<?=$class?> cufon">
							<a href="printInvoice.php?id=<?=$order['id']?>" target="_blank">Invoice</a> | <a href="printPacking.php?id=<?=$order['id']?>" target="_blank">Packing Slip</a>
						</td>
						<td align="left" valign="top" class="<?=$class?> cufon">
							<input type="checkbox" class="print" data-orderid="<?=$order['id']?>">
						</td>
					</tr>

				<?php } ?>

			</table>

		</div>

	</div>

</div>