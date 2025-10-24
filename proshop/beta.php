<?php
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';		
	
	$sort = "id DESC";		
	$orders = getOrders($keyword, $sort, 10);		
	$custsort = "dateAdded DESC";	
	$customers = getCustomers($keyword, $custsort, 10);		
	$coupons = getCoupons(10);	
	
	if ($_GET['showdays'] != "") {
		$showdays = $_GET['showdays'];
	} else {
		$showdays = 30;
	}
	
	//graph files
	include 'salesGraph.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML>
<HEAD>
<?php include 'includes/pageHead.php'; ?>
</HEAD>
<BODY>
<div id="topMenu"></div><div id="leftCol">	<div class="inner"></div></div><div id="contentArea">	<div id="pageContent">
		<span class="head">Admin Dashboard:</span><br><br>				<div style="width: 95%;" class="summary">			<div class="opener" style="width: 100%;">				<span class="openerTitle">SALES SUMMARY</span> (<a class="ajax" rel="manage_reports.php">view all reports</a>)			</div>
			<div class="contents" style="width: 98%;">				<?php include('salesGraph.php'); ?>				<div id="salesGraph" style="height: 300px; width: 75%; display: inline-block; margin-left: 10px; margin-bottom: 10px;"></div>				
				<div id="graphSummary" style="display: inline-block; width: 20%; margin-left: 3%; vertical-align: top;">					
					<form name="dashboard" action="beta.php" method="get">
					<span style="position: relative; top: 1px;" class="cufon">FILTER:</span>					
					<select name="showdays" style="background: #ededed; border: 1px solid #e6e6e6;" onchange="this.form.submit()" id="<?=$showdays?>">						
						<option value="7" <?php if ($showdays == 7) { ?>selected<?php } ?>>LAST 7 DAYS</option>
						<option value="30" <?php if ($showdays == 30) { ?>selected<?php } ?>>LAST 30 DAYS</option>
						<option value="60" <?php if ($showdays == 60) { ?>selected<?php } ?>>LAST 60 DAYS</option>
						<option value="90" <?php if ($showdays == 90) { ?>selected<?php } ?>>LAST 90 DAYS</option>
					</select><br /><br />
					</form>

					<span style="padding-bottom: 3px;" class="cufon">TOTAL SALES FOR PERIOD:</span><br />					
					<span style="border-bottom: 1px solid #e6e6e6; display: block; margin-bottom: 5px; margin-top: 5px; width: 200px;"></span>					
					<span class="reportTotal cufon">$<?=number_format($totalSales, 2, '.', ',')?></span>				
				</div>			
			</div>		
			
			</div>				<div style="width: 45%; display: inline-block; margin-right: 5%;" class="summary">			<div class="opener" style="width: 100%;">				<span class="openerTitle">RECENT ORDERS</span> (<a class="ajax" rel="manage_orders.php">view all</a>)			</div>			<div class="contents" style="width: 98%;">				<table border="0" cellpadding="0" cellspacing="0" style="width: 98%; margin-left: 1%;">					<tr>						<td align="left" valign="top" class="dataHeader cufon">							Order #						</td>						<td align="left" valign="top" class="dataHeader cufon">							Amount						</td>						<td align="left" valign="top" width="135" class="dataHeader cufon">							Customer						</td>						<td align="left" valign="top" class="dataHeader cufon">							Date						</td>						<td align="left" valign="top" class="dataHeader cufon">							Status						</td>					</tr>					<?php for ($count = 1; $order = mysql_fetch_array($orders); ++$count) { ?>						<?php							if ($count % 2) {								$class = "oddRowSmall";							} else {								$class = "evenRowSmall";							}							$customer = getCustomer($order['customerID']);						?>						<tr>							<td align="left" valign="top" class="<?=$class?> cufon" style="width: 15%;">								<a href="#" id="order_detail.php?id=<?=$order['id']?>" class="breadcrumb"><?php echo $order['id']; ?></a>							</td>							<td align="left" valign="top" class="<?=$class?> cufon" style="width: 20%;">								$<?php echo getOrderTotal($order['id']); ?>							</td>							<td align="left" valign="top" class="<?=$class?> cufon" style="width: 35%;">								<?php echo $customer['fname']; ?> <?php echo $customer['lname']; ?>							</td>							<td align="left" valign="top" class="<?=$class?> cufon">								<?php echo displayDate($order['dateAdded']); ?>							</td>							<td align="left" valign="top" class="<?=$class?> cufon">								<?php echo getStatus($order['status']); ?>							</td>						</tr>					<?php } ?>				</table>			</div>		</div>				<div style="width: 45%; display: inline-block;" class="summary">			<div class="opener" style="width: 100%;">				<span class="openerTitle">RECENT CUSTOMERS</span> (<a class="ajax" rel="manage_customers.php">view all</a>)			</div>			<div class="contents" style="width: 98%;">							<table border="0" cellpadding="0" cellspacing="0" style="width: 98%; margin-left: 1%;">					<tr>						<td align="left" valign="top" class="dataHeader cufon">							First Name						</td>						<td align="left" valign="top" class="dataHeader cufon">							Last Name						</td>						<td align="left" valign="top" class="dataHeader cufon">							Date Created						</td>						<td align="left" valign="top" class="dataHeader cufon">							Type						</td>					</tr>					<?php for ($count = 1; $cust = mysql_fetch_array($customers); ++$count) { ?>						<?php							if ($count % 2) {								$class = "oddRowSmall";							} else {								$class = "evenRowSmall";							}						?>						<tr>							<td align="left" valign="top" class="<?=$class?> cufon">								<a href="#" id="customer_detail.php?id=<?=$cust['id']?>" class="breadcrumb"><?php echo $cust['fname']; ?></a>							</td>							<td align="left" valign="top" class="<?=$class?> cufon">								<a href="#" id="customer_detail.php?id=<?=$cust['id']?>" class="breadcrumb"><?php echo $cust['lname']; ?></a>							</td>							<td align="left" valign="top" class="<?=$class?> cufon">								<?php echo displayDate($cust['dateAdded']); ?>							</td>							<td align="left" valign="top" class="<?=$class?> cufon">								<?php echo $cust['type']; ?>							</td>						</tr>					<?php } ?>				</table>						</div>		</div>				<div style="width: 45%; display: inline-block; margin-right: 5%;" class="summary">			<div class="opener" style="width: 100%;">				<span class="openerTitle">ADD PRODUCT(S)</span>			</div>			
			
			<div class="contents" style="width: 98%;">

				<div class="uploader" style="width: 98%; margin-left: 1%;">
					<form id="form1" action="index.php" method="post" enctype="multipart/form-data">
					<p>You can upload .jpg, .gif, or .png files.  Images should be compressed for Web Resolution (72 DPI)<br />
					<span id="blue">For best results images should be no taller or wider than 2000 pixels.</span></p><br />

					<b>Choose a Category for Product(s)</b><br />
					<select name="prodcatid" id="prodcatid" class="catFilterOff" style="margin-top: 5px;">
						<option value="">CATEGORY FILTER</option>

						<?php getShopCategories(); ?>

					</select><br /><br />

					<div class="fieldset flash" id="fsUploadProgress">
					<span class="legend">Upload Queue</span>
					</div>
					<div id="divStatus">0 Files Uploaded</div>
					<div>
						<span id="spanButtonPlaceHolder" style="text-align: center;"></span>
						<input id="btnCancel" type="button" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px;" />
					</div>

					</form><br /><br />
				</div>

			</div>		
			
			</div>				<div style="width: 45%; display: inline-block;" class="summary">			<div class="opener" style="width: 100%;">				<span class="openerTitle">DISCOUNT CODES</span> (<a class="ajax" rel="manage_coupons.php">view all</a>)			</div>			<div class="contents" style="width: 98%;">							<table border="0" cellpadding="0" cellspacing="0" style="width: 98%; margin-left: 1%;">					<tr>						<td align="left" valign="top" class="dataHeader cufon">							Discount Code						</td>						<td align="left" valign="top" class="dataHeader cufon">							Discount						</td>						<td align="left" valign="top" class="dataHeader cufon">							Type						</td>						<td align="left" valign="top" class="dataHeader cufon">							Start						</td>						<td align="left" valign="top" class="dataHeader cufon">							End						</td>						<td align="left" valign="top" class="dataHeader cufon">							Max						</td>						<td align="left" valign="top" class="dataHeader cufon">							# Uses						</td>					</tr>					<?php for ($count = 1; $coupon = mysql_fetch_array($coupons); ++$count) { ?>						<?php							if ($count % 2) {								$class = "oddRowSmall";							} else {								$class = "evenRowSmall";							}						?>						<tr>							<td align="left" valign="top" class="<?=$class?> cufon">								<a href="#" id="editCoupon.php?id=<?=$coupon['id']?>" class="breadcrumb"><?php echo $coupon['code']; ?></a>							</td>							<td align="left" valign="top" class="<?=$class?> cufon">								<?php if ($coupon['discountType'] === "percentage") { ?>									<?php echo $coupon['discount']; ?>%									<?php } elseif ($coupon['discountType'] === "dollar") { ?>										$<?php echo $coupon['discount']; ?>										<?php } else { ?>											<b>Free Shipping</b>								<?php } ?>							</td>							<td align="left" valign="top" class="<?=$class?> cufon">								<?php echo $coupon['custType']; ?>							</td>							<td align="left" valign="top" class="<?=$class?> cufon">								<?php echo timeDate($coupon['startDate']); ?>							</td>							<td align="left" valign="top" class="<?=$class?> cufon">								<?php echo timeDate($coupon['endDate']); ?>							</td>							<td align="left" valign="top" class="<?=$class?> cufon">								<?php echo $coupon['uses']; ?>							</td>							<td align="left" valign="top" class="<?=$class?> cufon">								<?php echo countCoupon($coupon['code']); ?>							</td>						</tr>					<?php } ?>				</table>						</div>		</div>
	</div>
</div>
<?php include 'includes/cufon.php' ?><script type="text/javascript">$(function () {  var plot30 = <?=$js_array?>;    $.plot($('#salesGraph'), [ {    label: 'Sales Summary',    data: plot30,    lines: { show: true, fill: true },    points: { show: true },    color: '#9eb453'  }], {    xaxis: {      ticks: 4,      mode: 'time'    },    yaxis: {      ticks: 3,      min: 0    },    grid: {      backgroundColor: { colors: ['#fff', '#ededed'] },      hoverable: true    },    legend: {      labelFormatter: function(label, series) {        return label;      }    }  });});function showTooltip(x, y, contents) {  $('<div id="tooltip">' + contents + '</div>').css( {    position: 'absolute',    display: 'none',    top: y + 5,    left: x + 5,    border: '1px solid #fdd',    padding: '2px',    backgroundColor: '#fee',    opacity: 0.80  }).appendTo('body').fadeIn(200);}var monthNames = [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ];var previousPoint = null;$('#salesGraph').bind('plothover', function (event, pos, item) {  if (item) {    if (previousPoint != item.datapoint) {      previousPoint = item.datapoint;      $('#tooltip').remove();      var x = item.datapoint[0],          y = item.datapoint[1],          xdate = new Date(x);      showTooltip(item.pageX, item.pageY, y + ' for ' + monthNames[xdate.getMonth()] + '-' + xdate.getDate());    }  } else {    $('#tooltip').remove();    previousPoint = null;  }});

$(function() {
// image upload js

	var swfu;

	$("#prodcatid").change(function() {
		var catID = $("#prodcatid option:selected").val();
		imageLoad(catID);
	});

	function imageLoad(catID) {
		var settings = {
			flash_url : "swfupload/swfupload.swf",
			upload_url: "upload_products.php",
			post_params: {"PHPSESSID" : "<?php echo session_id(); ?>", "catid" : catID},
			file_size_limit : "100 MB",
			file_types : "*.jpg;*.gif;*.png;*.pdf",
			file_types_description : "JPEG, GIF or PNG Files",
			file_upload_limit : 100,
			file_queue_limit : 0,
			custom_settings : {
				progressTarget : "fsUploadProgress",
				cancelButtonId : "btnCancel"
			},
			debug: false,

			// Button settings
			button_image_url: "images/browse.png",
			button_width: "80",
			button_height: "20",
			button_placeholder_id: "spanButtonPlaceHolder",
			button_text: '<span class="theFont">Browse...</span>',
			button_text_style: ".theFont { font-size: 11; font-family: Arial; color: fff; background: #50b6b8;}",
			button_text_left_padding: 15,
			button_text_top_padding: 2,

			// The event handler functions are defined in handlers.js
			file_queued_handler : fileQueued,
			file_queue_error_handler : fileQueueError,
			file_dialog_complete_handler : fileDialogComplete,
			upload_start_handler : uploadStart,
			upload_progress_handler : uploadProgress,
			upload_error_handler : uploadError,
			upload_success_handler : uploadSuccess,
			upload_complete_handler : MyUploadCompleteHandler,
			queue_complete_handler : queueComplete	// Queue plugin event
		};

		swfu = new SWFUpload(settings);

		function MyUploadCompleteHandler(file) {
			if (this.getStats().files_queued == 0) {

				$.ajax({
				  url: 'manage_products.php?catID='+catID,
				  success: function(data) {
					$('#pageContent').html(data);
					Cufon.refresh();
				  }
				});
			}
		}
	 };
 
 });

</script>
</BODY>
</HTML>