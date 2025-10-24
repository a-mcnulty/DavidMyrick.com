<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	$id = $_GET['id'];
	$customer = getCustomer($id);

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

	$(".makeActive").click(function() {

		var customerID = $(this).attr("id");

		// handle ajax post here
		var data = {"customerID" : customerID};
		var data_encoded = JSON.stringify(data);

		$.ajax({
		   type: "POST",
		   url: "makeActive.php",
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
	
	$(".switchType").click(function() {

		var customerID = $(this).attr("id");

		// handle ajax post here
		var data = {"customerID" : customerID};
		var data_encoded = JSON.stringify(data);

		$.ajax({
		   type: "POST",
		   url: "switchType.php",
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

	$(".update").click(function() {

		var customerID = $("input#customerID").val();
		var paymentType = $("#paymentType option:selected").val();

		// handle ajax post here
		 var data = {"customerID" : customerID, "paymentType" : paymentType};
		 var data_encoded = JSON.stringify(data);

		 $.ajax({
		   type: "POST",
		   url: "updatePaymentType.php",
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
	<p style="float: left;"><span class="head"><?=$customer['fname']?> <?=$customer['lname']?></span></p>

		<div id="breadcrumb">
			<a href="main.php">DASHBOARD</a> > <a href="#" id="manage_customers.php" class="breadcrumb">CUSTOMERS</a> > <span class="pageOn">CUSTOMER DETAIL</span>
		</div>

		<div id="line" style="width: 90%; margin-bottom: 2px; clear: both;"></div>
		<div id="line" style="width: 90%;"></div><br />

		<div id="manageCustomers" style="margin-top: 20px; margin-bottom: 20px;">

			<div style="width: 23%; display: inline-block;">
				<div class="dataBox" style="margin-left: 0px;">
					<img src="images/icons/customer.png" style="position: absolute; top: 10px; right: 8px;">
					<span class="heading cufon">CUSTOMER INFO</span><br />
					<div class="grayLine"></div>
					<div class="cufon">
						<?php if ($customer['company'] != "") { ?><?=$customer['company']?> (<?=$customer['resaleNumber']?>)<br /><?php } ?>
						Name: <?=$customer['fname']?> <?=$customer['lname']?><br />
						<a href="mailto:<?=$customer['email']?>"><?=$customer['email']?></a><br />
						<?=$customer['phone']?><br />
						<?php if ($customer['taxID'] != "") { ?>Tax ID #: <?=$customer['taxID']?><?php } ?><br />
					</div>
				</div>
			</div>

			<div style="width: 23%; display: inline-block;">
				<div class="dataBox">
					<span class="heading cufon">SHIPPING ADDRESS</span><br />
					<div class="grayLine"></div>
					<div class="cufon">
						Name: <?=$customer['ship_fname']?> <?=$customer['ship_lname']?><br />
						<?=$customer['ship_address1']?> <?php if ($customer['ship_address2'] != "Address Line 2") { ?><?=$customer['ship_address2']?><?php } ?><br />
						<?=$customer['ship_city']?>, <?=$customer['ship_state']?> <?=$customer['ship_zip']?>
						<?=$customer['ship_country']?><br />
					</div>
				</div>
			</div>

			<div style="width: 23%; display: inline-block;">
				<div class="dataBox">
					<span class="heading cufon">BILLING ADDRESS</span><br />
					<div class="grayLine"></div>
					<div class="cufon">
						Name: <?=$customer['fname']?> <?=$customer['lname']?><br />
						<?=$customer['address1']?> <?php if ($customer['address2'] != "Address Line 2") { ?><?=$customer['address2']?><?php } ?><br />
						<?=$customer['city']?>, <?=$customer['state']?> <?=$customer['zip']?>
						<?=$customer['country']?><br />
					</div>
				</div>
			</div>

			<div style="width: 29%; display: inline-block; float: right; text-align: right;">
				<div class="dataBox" style="background: transparent; font-size: 12px; width: 175px;">
					<span class="heading cufon">CUSTOMER STATUS</span><br />
					<div class="grayLine"></div>
					<div class="cufon">

						<b>Type: <a href="#" id="<?=$id?>" class="switchType"><?php echo strtoupper($customer['type']); ?></a></b><br />
						<b>Active: <a href="#" id="<?=$id?>" class="makeActive"><?php echo strtoupper($customer['active']); ?></a></b><br />
						<?php if ($customer['type'] === "wholesale") { ?>
							<b>Payment Type: </b>
							<form name="payment" method="post">
							<input type="hidden" name="customerID" id="customerID" value="<?=$id?>">
							<select name="paymentType" id="paymentType">
								<option value="<?=$customer['paymentType']?>"><?=$customer['paymentType']?></option>
								<option value="CreditCard">Credit Card</option>
								<option value="Net30">Net 30</option>
							</select> <button id="btn" class="update" style="margin-top: 0px;">UPDATE</button>
						<?php } ?>

					</div>
				</div>
			</div>

			<div style="clear: both; height: 40px;"></div>

		</div>

	</div>

</div>