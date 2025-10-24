<?php
	
	require_once('classes/class.phpmailer.php');
	
	$shopPage = "yes";
	$metaTitle = "WISHLIST";
	$slug = "wishlist";
	
	$items = getWishlistItems($sid);

	// IF WE HAVE A LOGGED IN CUSTOMER NEED TO CHECK CUSTOMER TYPE FOR RETAIL / WHOLESALE PRICING

	$customer_id = $_SESSION['customer_id'];
	$customer = getCustomer($customer_id);
	
	if ($_POST['action'] === "email_wishlist") {
	
		$from = $_POST['from'];
		$to = $_POST['to'];
		$message = $_POST['message'];
	
		$emailWishlist = sendWishlist($sid, $from, $to, $message);
	
	}
	
?>

<!DOCTYPE html>

<html>
<head>

    <title>SKINGRAFT | SHOP | <?=strtoupper($metaTitle)?></title>
	<?php include('includes/scripts.php'); ?>
	
	<script>
	
		$(document).ready(function() {
		
			$(".moveButton").click(function() {

				var cartID = $(this).attr("id");

				var dataString = 'cartID='+ cartID;
				$.ajax({
				type: "POST",
				url: "classes/moveToCart.php",
				data: dataString,
				success: function(response) {
					location.reload();
					/*
					$("#cartHolder").load('includes/wishlist.php');
					$("#miniCart").load('/includes/miniCart.php', function() {				
						$(this).show();		  
					});
					*/
				}
				});
				return false;

			});
	
			$(".removeButton").click(function() {
				
				var cartID = $(this).attr("id");

				var dataString = 'cartID='+ cartID;
				$.ajax({
				type: "POST",
				url: "classes/removeFromCart.php",
				data: dataString,
				success: function() {
					location.reload();
				}
				});
				return false;

			});
			
			$('.qty').click(function() {
				
				var cartID = $(this).data("cartid");
				var qty = parseInt( $(this).text() );
				
				updateQty(cartID,qty);
				
			});
			
			function updateQty(cartID,qty) {

				var dataString = 'cartID='+ cartID + '&qty=' + qty;
				
				$.ajax({
				type: "POST",
				url: "classes/updateQuantity.php",
				data: dataString,
				success: function() {
					location.reload();
				}
				});
				return false;

			}
			
			$('.size').click(function() {
				
				var cartID = $(this).data("cartid");
				var optionStr = $(this).attr("id");
				
				updateSize(cartID,optionStr);
				
			});
			
			function updateSize(cartID,optionStr) {

				var dataString = 'cartID='+ cartID + '&optionStr=' + optionStr;
				
				$.ajax({
				type: "POST",
				url: "classes/updateSize.php",
				data: dataString,
				success: function() {
					location.reload();
				}
				});
				return false;

			}
			
			// HANDLE STEP BUTTON CLICKS.
			
			function scrollMe(scrollTo) {
			
				$('#'+scrollTo+' .inner').show();
				$('.heading').removeClass('headingActive');
				$('#'+scrollTo+' .heading').addClass('headingActive');
				$('body').scrollTo( $('#'+scrollTo), 800, {offset:-75} );
				
			}
			
			$('.step1').click(function() {
				
				scrollMe('step2');				
				return false;
			
			});
			
			$('.step2').click(function() {
				
				scrollMe('step3');				
				return false;
			
			});
			
			$('.step3').click(function() {
				
				// SAVE FORM DATA TO DATABASE
				
				var ship_fname = $("input[name='ship_firstname']").val();
				var ship_lname = $("input[name='ship_lastname']").val();
				var ship_address1 = $("input[name='ship_address1']").val();
				var ship_address2 = $("input[name='ship_address2']").val();
				var ship_city = $("input[name='ship_city']").val();
				var ship_state = $("input[name='ship_state']").val();
				var ship_zip = $("input[name='ship_zip']").val();
				var ship_country = $('.ship_country_selected').text();
				
				var data = {"type" : "retail", "ship_fname" : ship_fname, "ship_lname" : ship_lname, "ship_address1" : ship_address1, "ship_address2" : ship_address2, "ship_city" : ship_city, "ship_state" : ship_state, "ship_zip" : ship_zip, "ship_country" : ship_country};
				var data_encoded = JSON.stringify(data);
				
				$.ajax({
				type: "POST",
				url: "createAccount.php",
				data: {
					"myContent" : data_encoded
				},
				success: function(response) {
					$('#step3 .inner').hide();
					$('.ship_details').show().html(response);
					scrollMe('step4');
				}
				});
				return false;
			
			});
			
			$('.step4').click(function() {
				
				// ADD BILLING DETAILS TO DATABASE
				
				var customerID = $("#customerID").val();
				var fname = $("input[name='firstname']").val();
				var lname = $("input[name='lastname']").val();
				var address1 = $("input[name='address1']").val();
				var address2 = $("input[name='address2']").val();
				var city = $("input[name='city']").val();
				var state = $("input[name='state']").val();
				var zip = $("input[name='zip']").val();
				var country = $(".country span").text();
				var email = $("input[name='email']").val();
				var phone = $("input[name='phone']").val();				
				var password = $("input[name='Password']").val();
				
				var data = {"customerID" : customerID, "fname" : fname, "lname" : lname, "address1" : address1, "address2" : address2, "city" : city, "state" : state, "zip" : zip, "country" : country, "email" : email, "phone" : phone, "password" : password};
				var data_encoded = JSON.stringify(data);
				
				
				$.ajax({
				type: "POST",
				url: "updateBilling.php",
				data: {
					"myContent" : data_encoded
				},
				success: function(response) {
					$('#step4 .inner').hide();
					$('.bill_details').show().html(response);
					loadSection("summaryDetails.php",".summary .inner","?customerID="+customerID)
					scrollMe('step5');
				}
				});
				return false;
			
			});
			
			$('.summary').on( 'click', '.step5', function() {
				
				scrollMe('step6');
				loadSection("paymentDetails.php",".payment .inner","")				
				return false;
			
			});
			
			// LOGIN CLICK
			$('.login').click(function() {
				
				// SAVE FORM DATA TO DATABASE
				
				var login_email = $("input[name='login_email']").val();
				var login_password = $("input[name='login_password']").val();
				
				var data = {"login_email" : login_email, "login_password" : login_password};
				var data_encoded = JSON.stringify(data);
				
				$.ajax({
				type: "POST",
				url: "login.php",
				data: {
					"myContent" : data_encoded
				},
				success: function(response) {
					
					if (response != "failed") {
					
						// we need to reload all sections that require address.  shipping, billing, order confirmation
						loadSection("shippingDetails.php",".ship_details","?customerID="+response);
						loadSection("billingDetails.php",".bill_details","?customerID="+response)
						loadSection("summaryDetails.php",".summary .inner","?customerID="+response)
						$('.heading').removeClass('headingActive');
						$('body').scrollTo( $('#step3'), 800, {offset:-75} );
						$('#step5 .heading').addClass('headingActive');
					
					}
					
				}
				});
				return false;
			
			});
			
			// SHIPPING CALCULATIONS
			$('.summary').on( 'click', '.shipping-option label span', function() {
			
				var shippingCost = $(this).parent().parent().attr("rel");
				$('.cost strong').contents().unwrap();
				$(this).parent().parent().find('.cost').contents().wrap( "<strong></strong>");
				loadSection("summaryTotals.php",".finalTotals","?shipping_method="+shippingCost);

			
			});
			
			// FORM PROCESSING FUNCTIONS
			$('.ship_country').click(function() {
				var selCountry = $(this).text();
				$('.ship_country').removeClass('ship_country_selected');
				$(this).addClass('ship_country_selected');
				$('.shipCountry span').text(selCountry);
			});
			
			$('.bill_country').click(function() {
				var selBillCountry = $(this).text();
				$('.bill_country').removeClass('bill_country_selected');
				$(this).addClass('bill_country_selected');
				$('.country span').text(selBillCountry);
			});
			
			$('input[type=text]:.password').focus(function() {
				$(this).replaceWith('<input type="password" class="password" name="Password" value="" />');
				$("input[name=Password]").focus();
			});

			$('input[type=text]:.password2').focus(function() {
				$(this).replaceWith('<input type="password" class="password2" name="ConfirmPass" value="" />');
				$("input[name=ConfirmPass]").focus();
			});
			
			$('input[type=text]:.login_password').focus(function() {
				$(this).replaceWith('<input type="password" class="login_password" name="login_password" value="" />');
				$("input[name=Password]").focus();
			});
			
		});
		
		function copyAddress() {

			var fname = $("input[name='ship_firstname']").val();
			$("input[name='firstname']").val(fname);
			
			var lname = $("input[name='ship_lastname']").val();
			$("input[name='lastname']").val(lname);
			
			var address1 = $("input[name='ship_address1']").val();
			$("input[name='address1']").val(address1);
			
			var address2 = $("input[name='ship_address2']").val();
			$("input[name='address2']").val(address2);
			
			var city = $("input[name='ship_city']").val();
			$("input[name='city']").val(city);
			
			var state = $("input[name='ship_state']").val();
			$("input[name='state']").val(state);
			
			var zip = $("input[name='ship_zip']").val();
			$("input[name='zip']").val(zip);
			
			var country = $(".ship_country_selected").text();
			$(".country span").text(country);

		}
		
		function loadSection(page,container,params) {
			
			$.ajax({
			type: "GET",
			url: page+params,
			success: function(response) {
				
				$(container).html(response);
				$(container).fadeTo(300, 1);
				
			}
			});
			return false;
		
		}
	
	</script>
    
</head>

<body>

<body class="store detail">

<div id="header" class="store row" style="position: fixed;">
	
	<?php include('includes/header.php'); ?>
	
</div>

<div id="wrapper" class="row">

	<div id="main" class="inner grid fadeMeOn">
	
		<div class="spacer"></div>

		<div id="cart_wrapper" style="margin-bottom: 900px;">
			
			
			<!--
			******************************
			STEP 1: "ITEMS IN BAG"
			******************************
			-->
			
			<div class="section" id="step1">
				
				<!--heading-->
				<div class="heading" style="background-color: #000;">
					<h1 class="title">items in wishlist</h1>
					<img src="images/shop_arrow.png" alt="" />
				</div>
				
				
				<table>
					
					<colgroup>
						<col style="width: 182px;">
						<col style="width: 194px">
						<col style="width: 122px">
						<col style="width: 122px">
						<col style="width: 109px">
						<col style="width: 87px">
						<col style="width: 92px">
						<col style="width: 47px">
					</colgroup>
					
					<tr height="55" style="background-color: #f5f5f5;">
						<th colspan="2" style="padding-left: 30px;">Item Description</th>
						<th>Color/Style</th>
						<th>Size</th>
						<th>Quantity</th>
						<th>Price</th>
						<th colspan="2">Total</th>
					</tr>
					
					
					<!--spacer-->
					<tr height="25"></tr>
					
					<?php for ($count = 0; $item = mysql_fetch_array($items); ++$count) { ?>
					
						<!--BEGIN PRODUCT ROW-->
						<tr class="productRow">
							<td>
								<!-- link image to product detail... -->
								<a class="transitionThis" data-href="/detail/<?=$item['slug']?>"><img class="productThumb" src="<?=getOption("imagePathFront")?>siteThumbs/<?=$item['img']?>" alt="" /></a>
								<a class="wishlist moveButton" href="#" id="<?=$item['cartID']?>">+ MOVE TO SHOPPING BAG</a>
							</td>
							
							<td>
								<p class="productName"><?=$item['title']?></p>
								<?php if ($item['ignoreQty'] === "yes") { ?>
									<p>Made to order item.<br />
									<span style="font-size: 10px; font-style: italic;">Will ship within 7 days<br />
									of order date.</span></p>
								<?php } ?>
							</td>
							
							<td>
								<div class="dropdown" style="width: 80px; background-position: 63px 8px;">
									<div class="trigger"></div>
									
									<?php
									
										$optionStr = $item['optionStr'];
										$optionArray = explode(",", $optionStr);
										
										// FIRST ITEM IN ARRAY IS SIZE, SECOND IS COLOR
									
									?>
									
									<span><?=strtoupper(itemName($optionArray[2]))?></span> <!--currently selected choice-->
									
									<ul class="options">
										
										<?php
										
											$relatedColors = getRelatedColors($item['id']);
											foreach($relatedColors as $color) {
											
												$thisRelated = getMainImage($color);
												$thisColor = getMainImage($thisRelated['colorid']);
												
												if ($color != "") {
												
										?>
										
													<li onclick="location.href='/detail/<?=$thisRelated['slug']?>'"><?=strtoupper($thisColor['title'])?></li>
													
												<?php } ?>
											
										<?php } ?>
									</ul>
								</div>
							</td>
							
							<td>
								<div class="dropdown" style="width: 88px; background-position: 72px 8px;">
									<div class="trigger"></div>
									<span><?=strtoupper(itemName($optionArray[1]))?></span> <!--currently selected choice-->
									
									<ul class="options">
									
									<?php
									
										$sizes = getSizes($item['id']);
										
										for ($sizecount = 0; $size = mysql_fetch_array($sizes); ++$sizecount) {
										
											$thisSize = getMainImage($size['optionstr']);
									
									?>
									
									
										<li class="size" id=",<?=$size['optionstr']?>,<?=$optionArray[2]?>" data-cartid="<?=$item['cartID']?>"><?=$thisSize['title']?></li>
									
									<?php } ?>
									
									</ul>
								</div>
							</td>
							
							<td>
								<div class="dropdown" style="width: 43px; background-position: 27px 8px;">
									<div class="trigger"></div>
									
									<span><?=$item['qty']?></span> <!--currently selected choice-->
									
									<ul class="options">
									<?php for($i=1; $i<=10; ++$i) { ?>
									
										<li class="qty" rel="<?=$item['id']?>" data-cartid="<?=$item['cartID']?>"><?=$i?></li>
									
									<?php } ?>
									</ul>
									
								</div>
							</td>
							
							<td>
								<p class="price">$<?php if ($customer['type'] === "retail" OR !isset($_SESSION['customer_id'])) { ?><?php echo number_format($item['price'], 2, '.', ','); ?><?php } else { ?><?php echo number_format($item['wPrice'], 2, '.', ','); ?><?php } ?></p>
							</td>
							
							<td>
								<p class="productTotal">$<?php if ($customer['type'] === "retail" OR !isset($_SESSION['customer_id'])) { ?><?php echo number_format(($item['price'] * $item['qty']), 2, '.', ','); ?><?php } else { ?><?php echo number_format(($item['wPrice'] * $item['qty']), 2, '.', ','); ?><?php } ?></p>
							</td>
							
							<td>
								<a href="#" class="removeButton" id="<?=$item['cartID']?>"><img class="remove" src="/images/shop_remove.jpg" /></a>
							</td>
						</tr>
						<!--END PRODUCT ROW-->
						
						<!--spacer-->
						<tr height="25"></tr>
						
					<?php } ?>
					
					<tr height="44" style="background-color: #f5f5f5;">
						<td class="subtotal" colspan="5">SUBTOTAL: <?php echo getWishTotal($sid, $customer['type']); ?></td>
						<td colspan="3"><a class="buttonBlk step1 openLB" href="#"><h1>EMAIL WISHLIST</h1></a></td>
					</tr>
					
				</table>
				
				<div class="spacer"></div>
				
			</div>
			
		</div>
		
	</div>
		
</div>


<div id="footer" class="row">

   <?php include('includes/footer.php'); ?>

</div>	

<div id="x_QVoverlay" class="wishOverlay">
	<div class="inner" style="width: 500px;">
		<img class="QVClose" src="/images/QVclose.png" />
		<form name="email" method="post" class="col" action="/wishlist.php" target="_parent" style="width: 100%;">
			<h2 class="headline" style="text-align: center;">Email Your Wishlist</h2>
			<input type="hidden" name="action" value="email_wishlist">
				
			<input type="text" name="from" class="txtField" value="YOUR EMAIL" style="width: 360px;" />

			<input type="text" name="to" class="txtField" value="SEND TO" style="width: 360px;" />

			<textarea name="message" class="txtField" style="width: 360px; height: 45px; padding: 10px; display: block; margin: auto;"></textarea>
			
			<a class="buttonBlk" href="#"><h1>SEND WISHLIST</h1></a>
		</form>
	</div>
</div>

</body>
</html>
