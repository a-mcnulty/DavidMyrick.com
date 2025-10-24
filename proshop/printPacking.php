<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';

	$orderID = $_GET['id'];
	$order = getOrder($orderID);
	$products = getOrderProducts($orderID);
	$customer = getCustomer($order['customerID']);

?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>

<style>
BODY, TD {font-family: Arial; font-size: 12px; line-height: 16px;}
.head {font-size: 18px; font-weight: bold; color: #666; line-height: 22px;}
.header {font-size: 16px; font-weight: bold; color: #fff; background: #666; padding: 5px;}
.oddRowSmall, .evenRowSmall {background: #f2f2f2; padding: 5px;}
</style>

</head>
<body>

<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td align="left" valign="top">
			<div class="head">
				<?php echo getOption("company"); ?><br />
				<?php echo getOption("address1"); ?> <?php echo getOption("address2"); ?><br />
				<?php echo getOption("city"); ?>, <?php echo getOption("state"); ?> <?php echo getOption("zip"); ?><br />
				ph: <?php echo getOption("phone"); ?><br />
				<!--fax: <?php echo getOption("fax"); ?><br />-->
			</div>
		</td>
		<td align="right" valign="top">
			<img src="<?php echo getOption("adminLogo"); ?>"><br />
		</td>
	</tr>
	<tr>
		<td colspan="2" style="border-bottom: 1px solid #000;">
			Order #: <b><?=$orderID?></b> | Order Placed: <b><?php echo displayDate($order['dateAdded']); ?></b><br /><br />
		</td>
	</tr>
	<tr>
		<td align="left" valign="top" width="50%">
			<b>SOLD TO:</b><br />
			<?php if ($customer['company'] != "") { ?><?=$customer['company']?><br /><?php } ?>
			<?php echo $customer['fname']; ?> <?php echo $customer['lname']; ?><br />
			<?php echo $customer['address1']; ?> <?php if ($customer['address2'] != "APT. / SUITE / UNIT") { ?><?php echo $customer['address2']; ?><br /><?php } ?>
			<?php echo $customer['city']; ?>, <?php echo $customer['state']; ?> <?php echo $customer['zip']; ?><br />
			<?php echo $customer['country']; ?><br /><br />

			<?php echo $customer['phone']; ?><br />
			<?php echo $customer['email']; ?><br /><br />

			<b>Payment Method:</b> <?php echo $order['paymentMethod']; ?><br /><br />
		</td>
		<td align="left" valign="top" width="50%">
			<b>SHIP TO:</b><br />
			<?php echo $customer['ship_fname']; ?> <?php echo $customer['ship_lname']; ?><br />
			<?php echo $customer['ship_address1']; ?> <?php if ($customer['ship_address2'] != "APT. / SUITE / UNIT") { ?><?php echo $customer['ship_address2']; ?><br /><?php } ?>
			<?php echo $customer['ship_city']; ?>, <?php echo $customer['ship_state']; ?> <?php echo $customer['ship_zip']; ?><br />
			<?php echo $customer['ship_country']; ?><br /><br />
		</td>
	</tr>
<table>

<table border="0" cellpadding="0" cellspacing="0" style="width: 90%;">
	<tr>
		<td align="left" valign="top" class="header">
			PRODUCT
		</td>
		<!--
		<td align="left" valign="top" class="header">
			STYLE NO.
		</td>
		-->
		<td align="left" valign="top" class="header">
			QTY
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
				<b><?php echo $prodDetail['title']; ?></b><br />

				<?php

					// retrieve option values from cart
					
					$optionStr = $product['optionStr'];
					$optionArray = explode(",", $optionStr);
					
					// THIS SHOULD BE AUTOMATED BY LOOPING THROUGH AVAILABLE OPTIONS IN PRODUCT AND OUTPUTTING, HARD-CODED FOR SIZE AND COLOR RIGHT NOW.

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
				<b><?php echo $product['qty']; ?></b>
			</td>
		</tr>

	<?php } ?>

</table>

</body>
</html>