<?php

	session_start();
	include("connect.php");
	include("functions.php");

	$items = getCartItems($sid);

	// IF WE HAVE A LOGGED IN CUSTOMER NEED TO CHECK CUSTOMER TYPE FOR RETAIL / WHOLESALE PRICING

	$customer_id = $_SESSION['customer_id'];
	$customer = getCustomer($customer_id);

?>

<script>

//Colorbox Call
$(".itemPopup").colorbox({top:"16%", width:"850px", height:"563px", iframe:true});

$(".removeButton").click(function() {

	var cartID = $(this).attr("id");

	var dataString = 'cartID='+ cartID;
	$.ajax({
	type: "POST",
	url: "classes/removeFromCart.php",
	data: dataString,
	success: function() {
	  $("#cartHolder").load('includes/shopping-cart.php');
	  $("#bag").load('includes/bag.php');
	}
	});
	return false;

});

function updateQty(cartID, prodID) {

	var qty = parseInt($("#qty_"+prodID).find(":selected").text());

	var dataString = 'cartID='+ cartID + '&qty=' + qty;
	$.ajax({
	type: "POST",
	url: "classes/updateQuantity.php",
	data: dataString,
	success: function() {
	  $("#cartHolder").load('includes/shopping-cart.php', function() {
  	  		$("#qtyResults_"+prodID).html("Quantity has been updated!");
	  });
	  $("#bag").load('includes/bag.php');
	}
	});
	return false;

}

</script>

<!-- HEADER INFO -->
<table class="register" border="0" cellspacing="0">

	<thead>
		<tr>
			<th colspan="2">Item</th>
			<th>Quantity</th>
			<th>Price</th>
			<th>Total</th>
			<th></th>
		</tr>
	</thead>

	<tbody>

		<?php for ($count = 0; $item = mysql_fetch_array($items); ++$count) { ?>

			<tr class="prodRow" id="<?=$item['id']?>">
				<td>
					<a href="catalog-popup.php?itemNumber=<?=$item['id']?>" class="itemPopup"><img class="product_thumb" src="<?php echo getOption("imagePathFront"); ?>thumbs/<?=$item['img']?>" alt="" /></a>
				</td>
				<td width="150">
					<p class="product_descr">
						<?=$item['title']?><br />

						<div class="prodSizes">

							<?php

								// HANDLE PRODUCT OPTIONS HERE
								$picID = $item['id'];
								$optionsCount = checkOptions($picID);

								if ($optionsCount > 0) {

									$optionStr = $item['optionStr'];
									$optionArray = explode(",", $optionStr);

									$sql = "SELECT * FROM ec_options WHERE picid = '$picID' ORDER BY name ASC";

									$result = mysql_query($sql);

									for ($count = 1; $option = mysql_fetch_array($result); ++$count) {

										echo $option['name'] . " - " . itemName($optionArray[$count]) . "<br />";

									}

								}


							?>

						</div>

					</p>
					<div id="qtyResults_<?=$item['id']?>" class="results product_descr"></div>
				</td>
				<td>
					<select class="product_qty selectField" id="qty_<?=$item['id']?>" onchange="updateQty(<?=$item['cartID']?>, <?=$item['id']?>);" style="width: 45px;">
						<option value="<?=$item['qty']?>"><?=$item['qty']?></option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
					</select><br />
				</td>
				<td>
					<p class="product_price">$<?php if ($customer['type'] === "retail" OR !isset($_SESSION['customer_id'])) { ?><?php echo number_format($item['price'], 2, '.', ','); ?><?php } else { ?><?php echo number_format($item['wPrice'], 2, '.', ','); ?><?php } ?></p>
				</td>
				<td>
					<p class="product_total">$<?php if ($customer['type'] === "retail" OR !isset($_SESSION['customer_id'])) { ?><?php echo number_format(($item['price'] * $item['qty']), 2, '.', ','); ?><?php } else { ?><?php echo number_format(($item['wPrice'] * $item['qty']), 2, '.', ','); ?><?php } ?></p>
				</td>
				<td>
					<div class="removeButton" id="<?=$item['cartID']?>"></div>
				</td>
			</tr>

		<?php } ?>

	</tbody>


	<tfoot>
		<tr>
			<td colspan="3"></td>
			<td colspan="2" style="text-align: right; padding-right: 5px;">SUBTOTAL:</td>
			<td class="totalPrice" colspan="2"><?php echo getCartTotal($sid, $customer['type']); ?></td>
		</tr>
	</tfoot>

</table>

<!--
END ACTUAL SHOPPING CART
************************
************************
-->