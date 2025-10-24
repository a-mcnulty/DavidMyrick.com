<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	$catID = $_GET['catID'];
	$keyword = $_GET['keyword'];
	$sort = $_GET['sort'];
	
	/*
	if (stristr($sort, "ASC")) {
		$theDir = "DESC";
	} else {
		$theDir = "ASC";
	}
	if (!isset($sort)) {
		$sort = "id DESC";
	}
	*/
	
	$report = $_GET['report'];
	if (!isset($_GET['report'])) {
		$report = "report_sales_by_day.php";
	}
	
	$dayRange = $_GET['days'];
	if (!isset($_GET['days'])) {
		$dayRange = 30;
	}

?>

<script type="text/javascript">
$(document).ready(function(){

	$("#days").change(function() {
	
		var theDays = $(this).val();
		var theReport = $('#report').val();
		var theSort = $('#sort').val();
		loadPage("manage_reports.php?days="+theDays+"&report="+theReport+"&sort="+theSort);

		return false;
		
	});
	
	$("#report").change(function() {
	
		var theReport = $(this).val();
		var theDays = $('#days').val();
		var theSort = $('#sort').val();
		loadPage("manage_reports.php?days="+theDays+"&report="+theReport+"&sort="+theSort);

		return false;
		
	});
	
	var totOrders = $('.totOrders').html();
	var totSales = $('.totSales').html();	
	$('.showOrders').html(totOrders);
	$('.showSales').html(totSales);
	
	// in page ajax links
	$("a.ajax").click(function() {

		var thePage = $(this).attr("rel");

		loadPage(thePage);

	});

});
</script>

<div class="infoPage">

	<div id="alertDivPic" style="float: right; margin: 9px 5px 0 0;"></div>
	<p style="float: left;"><span class="head">Reports:</span> 
		<select name="report" id="report" class="report">
			<option value="report_sales_by_day.php" <?php if ($report === "report_sales_by_day.php") { ?>selected<?php } ?>>SALES BY DAY</option>
			<option value="report_sales_by_country.php" <?php if ($report === "report_sales_by_country.php") { ?>selected<?php } ?>>SALES BY COUNTRY</option>
			<option value="report_sales_by_customer.php" <?php if ($report === "report_sales_by_customer.php") { ?>selected<?php } ?>>SALES BY CUSTOMER</option>
			<option value="report_top_sales_days.php" <?php if ($report === "report_top_sales_days.php") { ?>selected<?php } ?>>TOP SALES DAYS</option>
			<option value="report_tax_and_shipping.php" <?php if ($report === "report_tax_and_shipping.php") { ?>selected<?php } ?>>TAX & SHIPPING</option>
		</select>
		<input type="hidden" name="sort" id="sort" value="<?=$sort?>">
	</p>

	<div id="breadcrumb">
		<a href="main.php">DASHBOARD</a> > <span class="pageOn">REPORTS</span>
	</div>

	<div id="line" style="width: 90%; margin-bottom: 2px; clear: both;"></div>
	<div id="line" style="width: 90%;"></div><br />
	
	<?php include($report); ?>

</div>