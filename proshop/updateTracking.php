<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';
	require_once('../classes/class.phpmailer.php');

	if(isset($_POST["myContent"])){
		$data_json = $_POST["myContent"];

		$data_array = json_decode($data_json, true);

		if($data_array !== null){
        	$orderIDArray = $data_array["orderIDArray"];
        	$trackingArray = $data_array["trackingArray"];
    	}

	} else {
		echo "Error!";
	}
	
	$myCount = 0;
	foreach($orderIDArray as $orderID) {
	
		// only update tracking number for new numbers.  ignore blank and "ignore" entries
		
		if ( $trackingArray[$myCount] != "ignore" ) {
		
			if ( $trackingArray[$myCount] != "") {
			
				//echo "UPDATE!\n";
				//echo $orderID . " - " . $trackingArray[$myCount] . "\n\n";
				
				// first step is to update the tracking number and order status in database
				$sql1 = "UPDATE ec_orders SET trackingNumber = '$trackingArray[$myCount]', status = '6', shipDate = NOW() WHERE id = '$orderID'";
				
				$result1 = mysql_query($sql1);

				if (!$result1) {
						die('Could not query:' . mysql_error());
						exit;
				}
				
				$order = getOrder($orderID);
				$customer = getCustomer($order['customerID']);
				
				// now that we have recorded the order id let's email the customer
				$emailID = 518;
				$emailText = getPageText($emailID);
				$emailText = str_replace("xORDERIDx", $orderID, $emailText);
				$emailText = str_replace("xFNAMEx", $customer['fname'], $emailText);
				$emailText = str_replace("xTRACKINGNUMBERx", $trackingArray[$myCount], $emailText);
				
				// update order history table
				$sql2 = "INSERT INTO ec_order_history (orderID, notified, comments, status) VALUES ('$orderID', 'yes', '$emailText', '6')";

				$result2 = mysql_query($sql2);

				if (!$result2) {
						die('Could not query:' . mysql_error());
						exit;
				}
				
				// send out order summary and update below.

				$products = getOrderProducts($orderID);

				$emailText = stripslashes($emailText);
				
				$msgBody = "";

				if (getOption("adminLogo") != "") {
					$msgBody = '<img src="' . getOption("URL") . '/admin/' . getOption("adminLogo") . '"><br /><br />';
				}

				$msgBody .= 'ORDER # <b>' . $orderID . '</b><br />STATUS: <b>' . strtoupper(getStatus($order['status'])). '</b><br /><br />';

				$msgBody .= $emailText . "<br /><br />";

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
			
		}

		++$myCount;	
	
	}
	
	echo "manage_orders.php";
	
	
?>