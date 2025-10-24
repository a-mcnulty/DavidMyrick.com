<?php

	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';
	require_once('../classes/class.phpmailer.php');

	if(isset($_POST["myContent"])){
		$data_json = $_POST["myContent"];

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$orderID = $data_array["orderID"];
        	$status = $data_array["status"];
			$comments = $data_array["comments"];
			$comments = str_replace("\n\n", "<br /><br />", $comments);
        	$comments = mysql_real_escape_string($comments);
        	$emailComments = str_replace("\n\n", "<br /><br />", $comments);
        	$notify = $data_array["notify"];
    	}

	} else {
		echo "Error!";
	}

	if (!isset($orderID)) {
		echo "error updating page!";
		exit;
	}

	$order = getOrder($orderID);
	$customer = getCustomer($order['customerID']);

	$query = "INSERT INTO ec_order_history (orderID, notified, comments, status) VALUES ('$orderID', '$notify', '$comments', '$status')";

	$result = mysql_query($query);

	if (!$result) {
			die('Could not query:' . mysql_error());
			exit;
	}
	
	// if order status is shipped we need to add shipped date to the orders table
	
	if ($status == "6") {
	
		$query2 = "UPDATE ec_orders SET shipDate = NOW() WHERE id = '$orderID'";

		$result2 = mysql_query($query2);

		if (!$result2) {
				die('Could not query:' . mysql_error());
				exit;
		}
	
	}
	

	// update order table with new status
	$query2 = "UPDATE ec_orders SET status = '$status' WHERE id = '$orderID'";

	$result2 = mysql_query($query2);

	if (!$result2) {
			die('Could not query:' . mysql_error());
			exit;
	}

	if ($notify === "yes") {

		// we need to add the order summary and status below the comments.

		$products = getOrderProducts($orderID);

		$emailComments = stripslashes($emailComments);
		$emailComments = str_replace("\n", "<br />", $emailComments);

		if (getOption("adminLogo") != "") {
			$msgBody = '<img src="' . getOption("URL") . '/admin/' . getOption("adminLogo") . '"><br /><br />';
		}

		$msgBody .= 'ORDER # <b>' . $orderID . '</b><br />STATUS: <b>' . strtoupper(getStatus($status)). '</b><br /><br />';

		$msgBody .= $emailComments . "<br /><br />";

		$msgBody .= '<b>SHIPPING ADDRESS:</b><br />
					' . $order['shipfname'] . ' ' . $order['shiplname'] . '<br />
					' . $order['shipaddress'];

					if ($order['shipaddress2'] != "Address Line 2") {
						$msgBody .= $order['shipaddress2'];
					}

		$msgBody .= '<br />
					' . $order['shipcity'] . ', ' . $order['shipstate'] . ' ' . $order['shipzip'] . '<br />
					' . $order['shipcountry'] . '<br /><br />

					<b>BILLING ADDRESS:</b><br />
					' . $order['billfname'] . ' ' . $order['billlname'] . '<br />
					' . $order['billaddress'];

					if ($order['billaddress2'] != "Address Line 2") {
						$msgBody .= $order['billaddress2'];
					}

		$msgBody .= '<br />
					' . $order['billcity'] . ', ' . $order['billstate'] . ' ' . $order['billzip'] . '<br />
					' . $order['billcountry'] . '<br /><br />';

		$msgBody .= '<table border="0" cellpadding="0" cellspacing="0" style="width: 700px;">
						<tr>
							<td align="left" valign="top" class="dataHeader cufon">
								<b>PRODUCT</b>
							</td>
							<td align="left" valign="top" class="dataHeader cufon">
								<b>PRICE</b>
							</td>
							<td align="left" valign="top" class="dataHeader cufon">
								<b>QTY</b>
							</td>
							<td align="left" valign="top" class="dataHeader cufon">
								<b>SUBTOTAL</b>
							</td>
						</tr>';

						for ($count = 1; $product = mysql_fetch_array($products); ++$count) {

							if ($count % 2) {
								$style = "background: #fff; padding-bottom: 5px; padding-top: 5px;";
							} else {
								$style = "background: #ededed; padding-bottom: 5px; padding-top: 5px;";
							}

							$prodDetail = getProductDetail($product['prodID']);

							$msgBody .= '<tr>
								<td align="left" valign="top" style="' . $style . '">
									' . $prodDetail['title'] . '<br />';

									// retrieve option values from cart
									
									$optionStr = $product['optionStr'];
									$optionArray = explode(",", $optionStr);
									
									// FIRST ITEM IN ARRAY IS SIZE, SECOND IS COLOR. NEW FORMAT IS THE SET:OPTION STRING
									
									$sizeArray = explode(":", $optionArray[1]);
									$colorArray = explode(":", $optionArray[2]);

									// HANDLE SIZE OPTIONS HERE
									$picID = $product['prodID'];
									$optionsCount = checkOptions($picID, 418);

									$msgBody .= '<em>color: ' . itemName($colorArray[1]) . ' | size: ' . itemName($sizeArray[1]) . '</em><br />';

								$msgBody .= '</td>
								<td align="left" valign="top" style="' . $style . '">
									$' . $product['price'] . '
								</td>
								<td align="left" valign="top" style="' . $style . '">
									' . $product['qty'] . '
								</td>
								<td align="left" valign="top" style="' . $style . '">
									$' . number_format(($product[price] * $product[qty]), 2, ".", ",") . '
								</td>
							</tr>';

						}

			$msgBody .= '<tr>
							<td colspan="5" align="right"><br /><br />

								<span class="dataHeader">subtotal: $' . getOrderSubTotal($orderID) . '</span><br />
								<span class="dataHeader">tax: $' . number_format($order['tax'], 2, '.', ',') . '</span><br />
								<span class="dataHeader">shipping: (' . $order['shippingMethod'] . ') $' . number_format($order['shippingCost'], 2, '.', ',') . '</span><br />';

								if ($order['discountAmount'] > 0) {
									$msgBody .= '<span class="dataHeader">discount: (' . $order['discountCode'] . ') -$' . number_format($order['discountAmount'], 2, '.', ',') . '</span><br />';
								}

								$msgBody .= '<br /><span class="dataHeader"><b>TOTAL: $' . getOrderTotal($order['id']) . '</b></span><br />

							</td>
						</tr>
					</table><br /><br />';

			$msgBody .= getOption("company") . '<br />'
				. getOption("address1") . ' ' . getOption("address2") . '<br />'
				. getOption("city") . ', ' . getOption("state") . ' ' . getOption("zip") . '<br />
				ph: ' . getOption("phone") . '<br />
				fax: ' . getOption("fax") . '<br />';

		$mailer = sendMail($order['billfname'], $customer['email'], getOption('company'), getOption('email'), "Order Update", $msgBody);

	}

	echo "manage_orders.php";

?>