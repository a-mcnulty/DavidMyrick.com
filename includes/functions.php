<?php

Database::initialize();

//DEFINE PAGE GLOBALS
date_default_timezone_set('America/Los_Angeles');

global $catID, $parentID;

if (isset($_REQUEST['category'])) {
	$catID = $_REQUEST['category'];
}
if (isset($_REQUEST['parentID'])) {
	$parentID = $_REQUEST['parentID'];
}
$_SESSION['catID'] = $catID;

$ups_xml_access_key = "4C9FC3E904ACE498";
$ups_userid = "eastofwestern";
$ups_password = "System78*";

$sid = session_id();

$loaderImg = getOption("loaderImg");
$company = getOption("company");

// LIGHTBOX FUNCTIONS

function countLightbox($sid)
{

	$sql = "SELECT id FROM lightbox WHERE sessionID = '$sid'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return mysqli_num_rows($result);
}

function countLightboxURL($slug)
{

	$sql = "SELECT id FROM lightbox WHERE url = '$slug'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return mysqli_num_rows($result);
}

function createLink($sid)
{

	$sql = "UPDATE lightbox SET url = '$sid' WHERE sessionID = '$sid'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $sid;
}

function getLightboxURL($slug)
{

	$sql = "SELECT * FROM lightbox WHERE url = '$slug' ORDER BY sortBy ASC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getClientLightbox($clientID)
{

	$sql = "SELECT * FROM lightbox_client WHERE clientID = '$clientID' AND view = 'yes' ORDER BY sortBy ASC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getLightbox($sid)
{

	$sql = "SELECT * FROM lightbox WHERE sessionID = '$sid' ORDER BY sortBy ASC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function checkLightbox($sid, $picID)
{

	$sql = "SELECT id FROM lightbox WHERE sessionID = '$sid' AND picID = '$picID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return mysqli_num_rows($result);
}

/* ********* TAGS **************** */

function getTagID($tagName = "", $section = "")
{

	$query = "SELECT * FROM tags WHERE tag = '$tagName'";

	if ($section != "") {

		$query .= " AND section = '$section'";
	}

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row['id'];
}

function getTagBySlug($slug, $section = "tags")
{

	$query = "SELECT * FROM tags WHERE slug = '$slug'";

	if ($section != "") {

		$query .= " AND section = '$section'";
	}

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row;
}

function getTags()
{

	$query = "SELECT * FROM tags ORDER BY sortBy ASC";

	$result = mysqli_query(Database::$conn, $query);

	return $result;
}

function getTaggedWork($tag)
{

	$query = "SELECT pics.* FROM pics JOIN cat_pics ON cat_pics.picid = pics.id JOIN cat_list ON cat_list.id = cat_pics.catid WHERE pics.tags LIKE '%" . $tag . "%' AND cat_pics.catid > 0 ORDER BY cat_pics.sortBy ASC";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getTaggedCats($tag)
{

	$query = "SELECT * FROM cat_list WHERE tags LIKE '%" . $tag . "%' AND status = 'live' ORDER BY sortBy ASC";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getTaggedImg($catID, $tag)
{
	$query = "SELECT pics.* FROM pics JOIN cat_pics ON cat_pics.picid = pics.id WHERE pics.tags LIKE '%" . $tag . "%' AND cat_pics.catid = '$catID' ORDER BY sortBy ASC LIMIT 0,1";
	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}
	$row = mysqli_fetch_array($result);
	return $row;
}

/* ********* ECOMMERCE FUNCTIONS BEGIN **************** */

function checkCode($discountCode, $orderTotal, $custType, $shippingCost = "", $shippingType = "")
{

	/*
		1. check for valid code
		2. check if code has uses left
		3. check if code type and customer type match
		4. check if today's date is within the coupon code date
		5. return discount amount / percent or an invalid code message
		*/

	// first element of results array is message, 2nd is discount amount
	$results = array();

	// check for valid code

	$sql1 = "SELECT * FROM ec_coupons WHERE code = '$discountCode'";

	$result1 = mysqli_query(Database::$conn, $sql1);
	$row = mysqli_fetch_array($result1);
	$num = mysqli_num_rows($result1);

	if ($num == 0) {
		$results[0] = "Invalid Code";
		return $results;
	}

	// check number of uses

	$maxUses = $row['uses'];

	$sql2 = "SELECT id FROM ec_orders WHERE discountCode = '$discountCode'";

	$result2 = mysqli_query(Database::$conn, $sql2);
	$numUses = mysqli_num_rows($result2);

	if ($numUses >= $maxUses) {
		$results[0] = "Code has reached max uses.";
		return $results;
	}

	// make sure code type and customer type match
	$codeType = $row['custType'];

	if ($codeType != $custType) {
		$results[0] = "Invalid Code 2";
		return $results;
	}

	// check current date against code date
	$today = strtotime("now");
	$startDate = $row['startDate'];
	$endDate = $row['endDate'];

	if ($startDate > $today) {
		$results[0] = "Code is not active yet";
		return $results;
	}

	if ($endDate < $today) {
		$results[0] = "Code has expired";
		return $results;
	}

	// valid code, lets return the discount amount
	$discountAmount = $row['discount'];
	$discountType = $row['discountType'];

	if ($discountType === "dollar") {
		$theDiscount = $discountAmount;
		$results[0] = "Discount has been applied";
		$results[1] = number_format($theDiscount, 2, '.', ',');
	} elseif ($discountType === "percentage") {
		$thePercentage = $discountAmount / 100;
		$theDiscount = $orderTotal * $thePercentage;
		$results[0] = "Discount has been applied";
		$results[1] = number_format($theDiscount, 2, '.', ',');
	} else {
		if (stristr($shippingType, 'ground')) {
			$results[0] = "Discount has been applied";
			$results[1] = number_format($shippingCost, 2, '.', '');
		} else {
			$results[0] = "Free shipping only valid for Ground shipping method. <a href='/checkout_shipping.php'><u>Click here</u></a> to change shipping choice.";
			return $results;
		}
	}

	return $results;
}

function getCountries()
{

	$sql = "SELECT * FROM base_country ORDER BY name_en";

	$result = mysqli_query(Database::$conn, $sql);

	return $result;
}

function displayDate($thisDate)
{

	$thisDate = strtotime($thisDate);
	$thisDate = strftime("%m/%d/%y", $thisDate);

	if ($thisDate != "12/31/69") {
		return $thisDate;
	} else {
		return "";
	}
}

function tep_rand($min = null, $max = null)
{
	static $seeded;

	if (!isset($seeded)) {
		mt_srand((float)microtime() * 1000000);
		$seeded = true;
	}

	if (isset($min) && isset($max)) {
		if ($min >= $max) {
			return $min;
		} else {
			return mt_rand($min, $max);
		}
	} else {
		return mt_rand();
	}
}

function tep_validate_password($plain, $encrypted)
{

	if ($plain != "" && $encrypted != "") {
		// split apart the hash / salt
		$stack = explode(':', $encrypted);

		if (sizeof($stack) == 1) {
			if (md5($plain) == $stack[0]) return true;
		} elseif (sizeof($stack) == 2) {
			if (md5($stack[1] . $plain) == $stack[0]) return true;
		} else {
			return false;
		}
	}

	return false;
}

function tep_encrypt_password($plain)
{
	$password = '';

	for ($i = 0; $i < 10; $i++) {
		$password .= tep_rand();
	}

	$salt = substr(md5($password), 0, 2);

	$password = md5($salt . $plain) . ':' . $salt;

	return $password;
}

function countCartItems($sid)
{

	$sql = "SELECT id FROM ec_cart WHERE sessionID = '$sid'";

	$result = mysqli_query(Database::$conn, $sql);

	if (mysqli_num_rows($result) > 0) {

		$sql2 = "SELECT SUM(qty) as totItems FROM ec_cart WHERE sessionID = '$sid'";

		$result2 = mysqli_query(Database::$conn, $sql2);

		if (!$result2) {
			die('Could not query:' . mysqli_error(Database::$conn));
			exit;
		}

		$row = mysqli_fetch_array($result2);

		return $row['totItems'];
	} else {

		return "0";
	}
}

function weighCartItems($sid)
{

	$sql = "SELECT id FROM ec_cart WHERE sessionID = '$sid'";

	$result = mysqli_query(Database::$conn, $sql);

	if (mysqli_num_rows($result) > 0) {

		$sql2 = "SELECT weight, qty FROM pics JOIN ec_cart ON ec_cart.prodID = pics.id WHERE sessionID = '$sid'";

		$result2 = mysqli_query(Database::$conn, $sql2);

		if (!$result2) {
			die('Could not query:' . mysqli_error(Database::$conn));
			exit;
		}

		$packageWeight = 0;
		for ($count = 0; $item = mysqli_fetch_array($result2); ++$count) {

			$thisWeight = $item['weight'];
			$thisQty = $item['qty'];

			$prodTotal = $thisWeight * $thisQty;
			$packageWeight = $packageWeight + $prodTotal;
		}

		return $packageWeight;
	} else {

		return "0";
	}
}

function getShopCats()
{

	$sql = "SELECT * FROM cat_list WHERE contentType = 'category' AND status = 'live' AND parentid = '0' ORDER BY sortBy ASC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getCartItems($sid)
{

	$sql = "SELECT ec_cart.id AS cartID, ec_cart.qty, ec_cart.optionStr, pics.* FROM ec_cart JOIN pics ON pics.id = ec_cart.prodID WHERE sessionID = '$sid' ORDER BY pics.title ASC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getCartTotal($sid, $custType, $format = "cart")
{

	$sql = "SELECT * FROM ec_cart JOIN pics ON pics.id = ec_cart.prodID WHERE sessionID = '$sid' ORDER BY pics.title ASC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$total = 0;
	for ($count = 0; $item = mysqli_fetch_array($result); ++$count) {

		$optionStr = $item['optionStr'];
		$optionArray = explode(",", $optionStr);
		$sizeArray = explode(":", $optionArray[1]);
		$optionPrice = getOptionPrice($sizeArray[1], $item['prodID']);

		if ($custType === "retail" or !isset($_SESSION['customer_id'])) {

			if ($item['salesPrice'] > 0) {

				$thisPrice = $item['salesPrice'] + $optionPrice;
			} else {

				$thisPrice = $item['price']  + $optionPrice;
			}
		} else {
			$thisPrice = $item['wPrice'] + $optionPrice;
		}
		$thisQty = $item['qty'];

		$prodTotal = $thisPrice * $thisQty;
		$total = $total + $prodTotal;
	}

	$showTotal = number_format($total, 2, '.', ',');

	if ($format === "cart") {

		return "$" . $showTotal;
	} elseif ($format === "raw") {

		return $total;
	} else {

		return $showTotal;
	}
}

function getOrderSubTotal($orderID)
{

	$sql = "SELECT subtotal FROM ec_orders WHERE id = '$orderID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);
	$total = $row['subtotal'];

	$showTotal = number_format($total, 2, '.', ',');

	return $showTotal;
}

function getOrderTotal($orderID)
{

	$sql = "SELECT shippingCost, tax, subtotal, discountAmount FROM ec_orders WHERE id = '$orderID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);
	$total = ($row['shippingCost'] + $row['tax'] + $row['subtotal']) - $row['discountAmount'];

	$showTotal = number_format($total, 2, '.', ',');

	return $showTotal;
}

function deleteAbandonedCart()
{

	// DELETE CART ENTRIES THAT ARE MOVE THAN 24 HOURS OLD

	$yesterday = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));
	$sql = "DELETE FROM ec_cart WHERE dateModified < '$yesterday'";

	mysqli_query(Database::$conn, $sql);
}

function getWishlistItems($sid)
{

	$sql = "SELECT ec_wishlist.id AS cartID, ec_wishlist.qty, ec_wishlist.optionStr, pics.* FROM ec_wishlist JOIN pics ON pics.id = ec_wishlist.prodID WHERE sessionID = '$sid' ORDER BY pics.title ASC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getWishTotal($sid, $custType, $format = "cart")
{

	$sql = "SELECT * FROM ec_wishlist JOIN pics ON pics.id = ec_wishlist.prodID WHERE sessionID = '$sid' ORDER BY pics.title ASC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$total = 0;
	for ($count = 0; $item = mysqli_fetch_array($result); ++$count) {

		if ($custType === "retail" or !isset($_SESSION['customer_id'])) {
			$thisPrice = $item['price'];
		} else {
			$thisPrice = $item['wPrice'];
		}
		$thisQty = $item['qty'];

		$prodTotal = $thisPrice * $thisQty;
		$total = $total + $prodTotal;
	}

	$showTotal = number_format($total, 2, '.', ',');

	if ($format === "cart") {

		return "$" . $showTotal;
	} else {

		return $showTotal;
	}
}

function sendWishlist($sid, $from, $to, $message, $toBCC = '')
{

	$mail = new PHPMailer();

	//WE NEED TO BUILD THE MESSAGE BODY WITH THE USERS WISHLIST AND PERSONAL MESSAGE
	$items = getWishlistItems($sid);

	$tableOutput = '<table class="register" border="0" cellspacing="0">

		<thead>

			<tr>

				<th colspan="2" style="width: 350px;">Item</th>

				<th style="width: 160px; text-align: left;">Quantity</th>

				<th style="width: 160px; text-align: left;">Price</th>

				<th style="width: 100px; text-align: left; padding-right: 20px;">Total</th>

			</tr>

		</thead>

		<tbody>';

	for ($count = 0; $item = mysqli_fetch_array($items); ++$count) {

		$tableOutput .= '<tr class="prodRow" id="' . $item['id'] . '">

				<td style="text-align: left; width: 175px; font-family: Arial; font-size: 12px;">

					<a href="' . getOption("url") . '/detail/' . $item['slug'] . '"><img class="product_thumb" src="' . getOption("url") . getOption("imagePathFront") . 'thumbs/' . $item['img'] . '" alt="" /></a>

				</td>

				<td style="font-family: Arial; font-size: 12px;">

					<p class="product_descr">

						' . $item['title'] . '<br />

						<div class="prodSizes">';

		// retrieve option values from cart
		$optionStr = $item['optionStr'];

		$optionArray = explode(",", $optionStr);

		// THIS SHOULD BE AUTOMATED BY LOOPING THROUGH AVAILABLE OPTIONS IN PRODUCT AND OUTPUTTING, HARD-CODED FOR SIZE AND COLOR RIGHT NOW.

		// HANDLE SIZE OPTIONS HERE
		$picID = $item['id'];

		$optionsCount = checkOptions($picID, 449);

		if ($optionsCount > 0) {

			$sql = "SELECT * FROM ec_options WHERE picid = '$picID' AND optionid = '449' ORDER BY sortBy ASC";

			$result = mysqli_query(Database::$conn, $sql);

			for ($count = 1; $option = mysqli_fetch_array($result); ++$count) {

				$allOptionStr = $option['options'];

				$allOptionArray = explode(",", $allOptionStr);

				foreach ($allOptionArray as $choice) {

					if (in_array($choice, $optionArray)) {

						$tableOutput .= $option['name'] . " - " . itemName($choice) . "<br />";
					}
				}
			}
		}

		// HANDLE COLOR OPTIONS HERE

		$picID = $item['id'];

		$optionsCount = checkOptions($picID, 450);

		if ($optionsCount > 0) {

			$sql = "SELECT * FROM ec_options WHERE picid = '$picID' AND optionid = '450' ORDER BY sortBy ASC";

			$result = mysqli_query(Database::$conn, $sql);

			for ($count = 1; $option = mysqli_fetch_array($result); ++$count) {

				$allOptionStr = $option['options'];

				$allOptionArray = explode(",", $allOptionStr);

				foreach ($allOptionArray as $choice) {

					if (in_array($choice, $optionArray)) {

						$tableOutput .= $option['name'] . " - " . itemName($choice) . "<br />";
					}
				}
			}
		}

		$tableOutput .= '</div>

					</p>

					<div id="qtyResults_' . $item['id'] . '" class="results product_descr"></div>

				</td>

				<td style="font-family: Arial; font-size: 12px;">

					' . $item['qty'] . '<br />

				</td>

				<td style="font-family: Arial; font-size: 12px;">

					<p class="product_price">$' . number_format($item['price'], 2, '.', ',') . '</p>

				</td>

				<td style="text-align: right; padding-right: 20px; font-family: Arial; font-size: 12px;">

					<p class="product_total">$' . number_format(($item['price'] * $item['qty']), 2, '.', ',') . '</p>

				</td>

			</tr>
			
			<tr>
				<td colspan="5" style="height: 20px;">&nbsp;</td>
			</tr>';
	}

	$tableOutput .= '</tbody>

			<tfoot>

				<tr>

					<td colspan="3"></td>

					<td colspan="2" style="text-align: right; padding-right: 15px; font-family: Arial; font-size: 12px;">SUBTOTAL: <span class="totalPrice">' .  getWishTotal($sid, 'retail') . '</span></td>

				</tr>

			</tfoot>

		</table>';

	$body = "<font face='Arial' size='2'><b>" . strtoupper(getOption("company")) . " WISHLIST</b><br /><br />" . nl2br($message) . "<br /><br />" . $tableOutput . "<br /><br />Visit us online at: <a href='http://www.skingraftdesigns.com'>www.skingraftdesigns.com</a></font>";
	$body = eregi_replace("[\]", '', $body);

	$mail->AddReplyTo($from, $from);

	if ($toBCC != "") {
		$mail->AddBcc($toBCC);
	}

	$mail->SetFrom($from, $from);

	$address = $to;
	$mail->AddAddress($address, $to);

	$mail->Subject    = "Skingraft Wishlist";

	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";

	$mail->MsgHTML($body);

	if (!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
		exit;
	} else {
		return "sent";
	}
}

/* NEW PRODUCT OPTIONS FUNCTIONS */

function getImagesOption($optionSlug)
{

	$sql = "SELECT id FROM pics WHERE slug = '$optionSlug'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);
	$optionID = $row['id'];

	$sql2 = "SELECT pics.* FROM pics JOIN ec_product_options_inventory ON ec_product_options_inventory.productid = pics.id WHERE optionstr = '$optionID' AND qty > 0 OR pics.ignoreQty = 'yes' GROUP BY productid";

	$result2 = mysqli_query(Database::$conn, $sql2);

	if (!$result2) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result2;
}

function getQty($productID)
{

	$sql = "SELECT qty FROM ec_product_options_inventory WHERE productid = '$productID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);
	return $row['qty'];
}

function getSizes($productID, $ignoreQty = "no")
{

	if ($ignoreQty === "no") {

		$sql = "SELECT * FROM ec_product_options_inventory WHERE productid = '$productID' AND qty > '0'";
	} else {

		$sql = "SELECT * FROM ec_product_options_inventory WHERE productid = '$productID'";
	}

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

/* END NEW PRODUCT OPTIONS */

function checkOptions($picID)
{

	$sql = "SELECT * FROM ec_options WHERE picid = '$picID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$num = mysqli_num_rows($result);

	return $num;
}

function getOptionsID($optionID, $catID, $format = "select")
{

	$sql = "SELECT * FROM ec_options WHERE id = '$optionID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);
	$rawOptionStr = $row['options'];
	$optionArray = explode(",", $rawOptionStr);

	$sql2 = "SELECT * FROM pics WHERE catid = '$catID'";

	$result2 = mysqli_query(Database::$conn, $sql2);

	if (!$result2) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	if ($format === "select") {

		$optionStr = "";
		for ($count = 1; $pic = mysqli_fetch_array($result2); ++$count) {

			if (in_array($pic['id'], $optionArray)) {

				$optionStr .= "<option value='" . $pic['id'] . "'>" . $pic['title'] . "</option>";
			}
		}

		echo $optionStr;
	} else {

		$imgStr = "";
		$optionCount = 1;
		for ($count = 1; $pic = mysqli_fetch_array($result2); ++$count) {

			if (in_array($pic['id'], $optionArray)) {

				$optionName = itemName($pic['id']);
				$optionImg = getMainImage($pic['id']);

				$imgStr .= '<div class="clr singleSelect" id="swatch_' . $optionCount . '" title="' . $optionName . '"><img src="' . getOption("imagePathFront") . $optionImg['img'] . '" /><span>' . $optionName . '</span></div>';

				++$optionCount;
			}
		}

		echo $imgStr;
	}
}

function itemName($picID)
{

	$sql = "SELECT title FROM pics WHERE id = '$picID'";

	$result = mysqli_query(Database::$conn, $sql);
	$row = mysqli_fetch_array($result);

	return $row['title'];
}

function getCustomer($cID)
{

	$sql = "SELECT * FROM ec_customers WHERE id = '$cID'";

	$result = mysqli_query(Database::$conn, $sql);
	$row = mysqli_fetch_array($result);

	return $row;
}

function getCustomerOrders($cID)
{

	$sql = "SELECT * FROM ec_orders WHERE customerID = '$cID' ORDER BY id DESC";

	$result = mysqli_query(Database::$conn, $sql);

	return $result;
}

function getRelated($picID)
{

	$sql1 = "SELECT related FROM pics WHERE id = '$picID'";

	$result1 = mysqli_query(Database::$conn, $sql1);

	if (!$result1) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row1 = mysqli_fetch_array($result1);

	$related = $row1['related'];
	$relatedArray = explode(",", $related);

	return $relatedArray;
}

function getRelatedColors($picID)
{

	$sql1 = "SELECT relatedColors FROM pics WHERE id = '$picID'";

	$result1 = mysqli_query(Database::$conn, $sql1);

	if (!$result1) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row1 = mysqli_fetch_array($result1);

	$related = $row1['relatedColors'];
	$relatedArray = explode(",", $related);

	return $relatedArray;
}

function getNextOrderID()
{

	$sql = "SELECT id FROM ec_orders ORDER BY id DESC LIMIT 0,1";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	$lastID = $row['id'];
	$nextID = $lastID + 1;

	return $nextID;
}

function getOrder($orderID)
{

	$sql = "SELECT * FROM ec_orders WHERE id = '$orderID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row;
}

function getCustomerOrder($cID)
{

	$sql = "SELECT * FROM ec_customers WHERE id = '$cID'";

	$result = mysqli_query(Database::$conn, $sql);
	$row = mysqli_fetch_array($result);

	return $row;
}

function getOrderProducts($orderID)
{

	$sql = "SELECT * FROM ec_orders_products WHERE orderID = '$orderID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getProductDetail($prodID)
{

	$sql = "SELECT * FROM pics WHERE id = '$prodID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row;
}

function getStatus($statusID)
{

	$sql = "SELECT name FROM ec_order_status WHERE id = '$statusID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	if ($row['name'] === "Shipped") {

		return "<span class='greenTxt'>" . $row['name'] . "</span>";
	} else {

		return $row['name'];
	}
}

function productNav($picID, $prodCatID)
{

	$itemArray = array();

	if (is_numeric($prodCatID)) {

		$sql1 = "SELECT pics.id, slug FROM cat_pics JOIN pics ON pics.id = cat_pics.picid WHERE cat_pics.catid = '$prodCatID' ORDER BY pics.sortBy ASC";
	} else {

		$sql1 = "SELECT pics.id, slug FROM pics WHERE tags LIKE '%" . $prodCatID . "%' ORDER BY pics.sortBy ASC";
	}

	$result1 = mysqli_query(Database::$conn, $sql1);
	for ($count = 0; $pic = mysqli_fetch_array($result1); ++$count) {

		array_push($itemArray, $pic['id']);
	}

	/* count number of elements in array to handle first and last project */
	$projectCount = count($itemArray);
	$lastProject = $projectCount - 1;

	/* check for position of current project in work array */
	$thisPosition = array_search($picID, $itemArray);
	$nextPosition = $thisPosition + 1;
	$prevPosition = $thisPosition - 1;

	if ($thisPosition == 0) {
		$nextID = $itemArray[1];
		$prevID = $itemArray[$lastProject];
	} elseif ($thisPosition == $lastProject) {
		$nextID = $itemArray[0];
		$prevID = $itemArray[$prevPosition];
	} else {
		$nextID = $itemArray[$nextPosition];
		$prevID = $itemArray[$prevPosition];
	}

	$navArray = array();
	$navArray[] = $prevID;
	$navArray[] = $nextID;

	return $navArray;
}

/* ********* ECOMMERCE FUNCTIONS END *************** */

/* ***********************EMAIL FUNCTIONS*************************** */

function sendMail($toName, $toEmail, $fromName, $fromEmail, $subject, $msg, $toBCC = '')
{

	$mail = new PHPMailer();

	$body = "<font face='Arial' size='2'>" . $msg . "</font>";

	$mail->AddReplyTo($fromEmail, $fromName);

	if ($toBCC != "") {

		$bccArray = explode(",", $toBCC);
		foreach ($bccArray as $bcc) {

			if ($bcc != "") {
				$mail->AddBcc($bcc);
			}
		}
	}

	$mail->SetFrom($fromEmail, $fromName);

	$address = $toEmail;
	$mail->AddAddress($address, $toName);

	$mail->Subject    = $subject;

	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";

	$mail->MsgHTML($body);

	if (!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
		exit;
	} else {
		//echo "Message sent!";
	}
}

function showStatus($status)
{

	$displayStatus = strtoupper(str_replace("_", " ", $status));
	if ($status != "") {
		echo '<div id="alert">' . $displayStatus . '</div>';
	}
}

function getCatID($slug)
{

	$sql = "SELECT id FROM cat_list WHERE slug = '$slug'";

	$result = mysqli_query(Database::$conn, $sql);
	$row = mysqli_fetch_array($result);

	return $row['id'];
}

function getParentID($slug)
{

	$sql = "SELECT parentID FROM cat_list WHERE slug = '$slug'";

	$result = mysqli_query(Database::$conn, $sql);
	$row = mysqli_fetch_array($result);

	return $row['parentID'];
}

function getSlug($catID)
{

	$sql = "SELECT slug FROM cat_list WHERE id = '$catID'";

	$result = mysqli_query(Database::$conn, $sql);
	$row = mysqli_fetch_array($result);

	return $row['slug'];
}

function getPicID($slug)
{

	$sql = "SELECT pics.id FROM pics JOIN cat_pics ON cat_pics.picid = pics.id WHERE pics.slug = '$slug'";

	$result = mysqli_query(Database::$conn, $sql);
	$row = mysqli_fetch_array($result);

	return $row['id'];
}

function getPicIDCustom($slug)
{

	$sql = "SELECT pics.id FROM pics JOIN cat_pics ON cat_pics.picid = pics.id WHERE pics.customSlug = '$slug'";

	$result = mysqli_query(Database::$conn, $sql);
	$row = mysqli_fetch_array($result);

	return $row['id'];
}

function getFirstCat()
{

	$query = "SELECT id FROM cat_list WHERE parentID = 0 ORDER BY sortBy ASC LIMIT 0,1";

	$result = mysqli_query(Database::$conn, $query);
	$row = mysqli_fetch_array($result);

	return $row['id'];
}

function getFirstSub($catID)
{

	$query = "SELECT id, pageType, parentID, slug FROM cat_list WHERE parentID = '$catID' ORDER BY sortBy ASC LIMIT 0,1";

	$result = mysqli_query(Database::$conn, $query);
	$row = mysqli_fetch_array($result);

	if ($row['pageType'] === "images") {
		$rd = "/photos/" . $row['slug'] . ".html";
	} elseif ($row['pageType'] === "text") {
		$rd = "/information/" . $row['slug'] . ".html";
	} elseif ($row['pageType'] === "videos") {
		$rd = "/videos/" . $row['slug'] . ".html";
	} elseif ($row['pageType'] === "link") {
		$rd = $row['link'];
	}

	return $rd;
}

function getFirstSubID($catID, $status = "live")
{

	$query = "SELECT id FROM cat_list WHERE (parentID = '$catID' AND status = '$status') ORDER BY sortBy ASC LIMIT 0,1";

	$result = mysqli_query(Database::$conn, $query);
	$row = mysqli_fetch_array($result);

	return $row['id'];
}

function getFirstSubIDAll($catID)
{

	$query = "SELECT id FROM cat_list WHERE (parentID = '$catID') ORDER BY sortBy ASC LIMIT 0,1";

	$result = mysqli_query(Database::$conn, $query);
	$row = mysqli_fetch_array($result);

	return $row['id'];
}

function breadcrumb($catID)
{

	$query = "SELECT name, subName, parentID, pageType FROM cat_list WHERE id = '$catID'";

	$result = mysqli_query(Database::$conn, $query);
	$row = mysqli_fetch_array($result);

	if ($row['name'] != "") {

		$myCrumb = "> <span class='pageOn'>" . catName($catID) . "</span>";
	} else {

		if ($row['pageType'] === "images") {
			$rd = "manage_images.php";
		} elseif ($row['pageType'] === "text") {
			$rd = "manage_text.php";
		} elseif ($row['pageType'] === "videos") {
			$rd = "manage_videos.php";
		} elseif ($row['pageType'] === "link") {
			$rd = $row['link'];
		}

		$myCrumb = "> <a href='" . $rd . "?category=" . $row['parentID'] . "'>" . catName($row['parentID']) . "</a> > <span class='pageOn'>" . $row['subName'] . "</span>";
	}

	return $myCrumb;
}

function catList($exc = "")
{

	if (isset($exc)) {

		// NEED TO LOOP THROUGH $exc ARRAY AND ADD TO QUERY

		$excArray = explode(",", $exc);
		$excStr = "AND (";
		foreach ($excArray as $item) {

			$excStr .= "id <> '" . $item . "' AND ";
		}

		$excStr = substr($excStr, 0, -4);

		$excStr .= ")";

		$query = "SELECT * FROM cat_list WHERE (parentID = 0) $excStr AND status = 'live' AND contentType = 'page' ORDER BY sortBy ASC";
	} else {

		$query = "SELECT * FROM cat_list WHERE (parentID = 0) AND status = 'live' AND contentType = 'page' ORDER BY sortBy ASC";
	}

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function catList2($inc = "")
{

	if (isset($inc)) {

		// NEED TO LOOP THROUGH $inc ARRAY AND ADD TO QUERY

		$incArray = explode(",", $inc);
		$incStr = "AND (";
		foreach ($incArray as $item) {

			$incStr .= "id = '" . $item . "' OR ";
		}

		$incStr = substr($incStr, 0, -4);

		$incStr .= ")";

		$query = "SELECT DISTINCT name, subName, id, pageType, status, parentID, imgOFF, imgON, link, slug, linkto FROM cat_list WHERE (parentID = 0) $incStr AND status = 'live' ORDER BY sortBy ASC";
	} else {

		$query = "SELECT DISTINCT name, subName, id, pageType, status, parentID, imgOFF, imgON, link, slug, linkto FROM cat_list WHERE (parentID = 0 AND) AND status = 'live' ORDER BY sortBy ASC";
	}

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function catList3($exc = "")
{

	if (isset($exc)) {

		// NEED TO LOOP THROUGH $exc ARRAY AND ADD TO QUERY

		$excArray = explode(",", $exc);
		$excStr = "AND (";
		foreach ($excArray as $item) {

			$excStr .= "id <> '" . $item . "' AND ";
		}

		$excStr = substr($excStr, 0, -4);

		$excStr .= ")";

		$query = "SELECT DISTINCT name, id, pageType, status, parentID, imgOFF, imgON, link, slug, linkto FROM cat_list WHERE (parentID = 0 AND sectionID = 0) $excStr AND status = 'live' ORDER BY sortBy ASC";
	} else {

		$query = "SELECT DISTINCT name, id, pageType, status, parentID, imgOFF, imgON, link, slug, linkto FROM cat_list WHERE (parentID = 0 AND sectionID = 0) AND status = 'live' ORDER BY sortBy ASC";
	}

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function subCount($parentID)
{

	$query = "SELECT DISTINCT subName, id, pageType, status, parentID, imgOFF, imgON, slug FROM cat_list WHERE parentID = '$parentID' AND status = 'live' ORDER BY sortBy ASC";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$num = mysqli_num_rows($result);

	return $num;
}

function subcatList($parentID, $exc = "")
{

	if (isset($exc)) {

		// NEED TO LOOP THROUGH $exc ARRAY AND ADD TO QUERY

		$excArray = explode(",", $exc);
		$excStr = "AND (";
		foreach ($excArray as $item) {

			$excStr .= "id <> '" . $item . "' AND ";
		}

		$excStr = substr($excStr, 0, -4);

		$excStr .= ")";

		$query = "SELECT * FROM cat_list WHERE parentID = '$parentID' $excStr AND status = 'live' ORDER BY sortBy ASC";
	} else {

		$query = "SELECT * FROM cat_list WHERE parentID = '$parentID' AND status = 'live' ORDER BY sortBy ASC";
	}

	//echo $query;

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function subcatListAll($parentID, $exc = "")
{

	if (isset($exc)) {

		// NEED TO LOOP THROUGH $exc ARRAY AND ADD TO QUERY

		$excArray = explode(",", $exc);
		$excStr = "AND (";
		foreach ($excArray as $item) {

			$excStr .= "id <> '" . $item . "' AND ";
		}

		$excStr = substr($excStr, 0, -4);

		$excStr .= ")";

		$query = "SELECT * FROM cat_list WHERE parentID = '$parentID' $excStr ORDER BY sortBy ASC";
	} else {

		$query = "SELECT * FROM cat_list WHERE parentID = '$parentID' ORDER BY sortBy ASC";
	}

	//echo $query;

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function catDetails($catID)
{

	$query = "SELECT * FROM cat_list WHERE id = '$catID'";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error());
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row;
}

function catName($catID)
{

	$query = "SELECT name FROM cat_list WHERE id = '$catID'";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	$thisName = $row['name'];

	return $thisName;
}

function catStatus($catID)
{

	$query = "SELECT status FROM cat_list WHERE id = '$catID'";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	$status = $row['status'];

	return $status;
}

function subName($catID)
{

	$query = "SELECT subName FROM cat_list WHERE id = '$catID'";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	$thisName = $row['subName'];

	return $thisName;
}

function getImagesCount($catID)
{

	$query2 = "SELECT id FROM pics WHERE catid = '$catID' ORDER BY pics.sortBy ASC";

	$result2 = mysqli_query(Database::$conn, $query2);

	if (!$result2) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$num = mysqli_num_rows($result2);
	return $num;
}

function getPicCatID($picID)
{

	$sql = "SELECT catid FROM cat_pics WHERE picid = '$picID' AND catid != '563'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	//echo $sql;

	$row = mysqli_fetch_array($result);
	return $row['catid'];
}

function getImages($catID, $theSort = "drag")
{

	$sql = "SELECT pics.*, cat_pics.rowBreak as catBreak, cat_pics.imgSize as catSize FROM pics JOIN cat_pics ON pics.id = cat_pics.picid WHERE cat_pics.catid = '$catID'";

	if ($theSort === "drag") {

		$sql .= " ORDER BY cat_pics.sortBy ASC";
	} else {

		$sql .= " ORDER BY pics.filename";
	}

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getRandomImage($catID)
{

	$sql = "SELECT pics.*, cat_pics.rowBreak as catBreak, cat_pics.imgSize as catSize FROM pics JOIN cat_pics ON pics.id = cat_pics.picid WHERE cat_pics.catid = '$catID' ORDER BY rand()";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row;
}

function getRandomImageResult($catID)
{

	$sql = "SELECT pics.*, cat_pics.rowBreak as catBreak, cat_pics.imgSize as catSize FROM pics JOIN cat_pics ON pics.id = cat_pics.picid WHERE cat_pics.catid = '$catID' ORDER BY rand()";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getImages2($catID)
{

	$query2 = "SELECT pics.* FROM pics JOIN cat_pics ON pics.id = cat_pics.picid WHERE cat_pics.catid = '$catID' ORDER BY pics.sortBy ASC";

	$result2 = mysqli_query(Database::$conn, $query2);

	if (!$result2) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result2;
}

function getAllImages($catID)
{

	$sql1 = "SELECT id FROM cat_list WHERE parentID = '$catID' AND status = 'live' ORDER BY sortBy ASC";

	$result1 = mysqli_query(Database::$conn, $sql1);
	$num = mysqli_num_rows($result1);

	if ($num > 0) {

		$catStr = "(";
		for ($count = 1; $cat = mysqli_fetch_array($result1); ++$count) {

			$catStr .= "cat_pics.catid = " . $cat['id'] . " OR ";
		}

		$catStrCount = (strlen($catStr) - 4);
		$catStr = substr($catStr, 0, $catStrCount);
		$catStr .= ")";
	} else {

		$catStr = "cat_pics.catid = '$catID'";
	}

	$query2 = "SELECT pics.* FROM pics JOIN cat_pics ON cat_pics.picid = pics.id JOIN cat_list ON cat_list.id = cat_pics.catid WHERE $catStr ORDER BY cat_list.sortBy ASC, pics.sortBy ASC";

	$result2 = mysqli_query(Database::$conn, $query2);

	if (!$result2) {
		die('Could not query: 1' . mysqli_error(Database::$conn));
		exit;
	}

	return $result2;
}

function getImage($catID)
{

	$query2 = "SELECT pics.* FROM pics JOIN cat_pics ON cat_pics.picid = pics.id WHERE cat_pics.catid = '$catID' ORDER BY rand() LIMIT 0,1";

	$result2 = mysqli_query(Database::$conn, $query2);

	if (!$result2) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result2);

	return $row;
}

function getFirstImage($catID, $theSort = "drag")
{

	$sql = "SELECT pics.* FROM pics JOIN cat_pics ON pics.id = cat_pics.picid WHERE cat_pics.catid = '$catID'";

	if ($theSort === "drag") {

		$sql .= " ORDER BY cat_pics.sortBy ASC LIMIT 0,1";
	} else {

		$sql .= " ORDER BY pics.filename LIMIT 0,1";
	}

	$result2 = mysqli_query(Database::$conn, $sql);

	if (!$result2) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result2);

	return $row;
}

function getImageText($id)
{

	$query3 = "SELECT * FROM content WHERE picid = '$id'";

	$result3 = mysqli_query(Database::$conn, $query3);

	if (!$result3) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result3);

	return $row;
}

function getAltImages($picID, $catID = '')
{

	if ($catID != "") {

		$query2 = "SELECT * FROM pics WHERE picid = '$picID' AND catid = '$catID' ORDER BY pics.sortBy ASC";
	} else {

		$query2 = "SELECT * FROM pics WHERE picid = '$picID' ORDER BY pics.sortBy ASC";
	}

	//echo $query2;

	$result2 = mysqli_query(Database::$conn, $query2);

	if (!$result2) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result2;
}

function getVideos($catID)
{

	$query2 = "SELECT videos.* FROM videos JOIN cat_videos ON cat_videos.vidid = videos.id WHERE cat_videos.catid = '$catID' ORDER BY cat_videos.sortBy ASC";

	$result2 = mysqli_query(Database::$conn, $query2);

	if (!$result2) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result2;
}

function getVideo($vidID)
{

	$query2 = "SELECT * FROM videos WHERE id = '$vidID' ORDER BY videos.sortBy ASC";

	$result2 = mysqli_query(Database::$conn, $query2);

	if (!$result2) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return mysqli_fetch_array($result2);
}

function getVideoText($id)
{

	$query3 = "SELECT * FROM content WHERE vidid = '$id'";

	$result3 = mysqli_query(Database::$conn, $query3);

	if (!$result3) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result3);

	return $row;
}

function getImageVideo($picID)
{

	$query2 = "SELECT * FROM videos WHERE picid = '$picID' ORDER BY videos.sortBy ASC";

	$result2 = mysqli_query(Database::$conn, $query2);

	if (!$result2) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result2);

	return $row;
}

function getVideoImages($vidID)
{

	$query2 = "SELECT * FROM pics WHERE vidid = '$vidID' ORDER BY pics.sortBy ASC";

	$result2 = mysqli_query(Database::$conn, $query2);

	if (!$result2) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result2;
}

function getFrameColorImage($picID, $colorID)
{

	$query2 = "SELECT img FROM pics WHERE picid = '$picID' AND colorid = '$colorID' ORDER BY sortBy ASC LIMIT 0,1";

	$result2 = mysqli_query(Database::$conn, $query2);

	if (!$result2) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result2);
	return $row['img'];
}

function getFrameColorImageBack($picID, $colorID)
{

	$query2 = "SELECT img FROM pics WHERE picid = '$picID' AND colorid = '$colorID' ORDER BY sortBy ASC LIMIT 1,1";

	$result2 = mysqli_query(Database::$conn, $query2);

	if (!$result2) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result2);
	return $row['img'];
}

function getMainImage($picID)
{

	$query2 = "SELECT * FROM pics WHERE id = '$picID'";

	$result2 = mysqli_query(Database::$conn, $query2);

	if (!$result2) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$array = mysqli_fetch_array($result2);

	return $array;
}

function getMainImageSlug($slug)
{

	$query2 = "SELECT * FROM pics WHERE slug = '$slug'";

	$result2 = mysqli_query(Database::$conn, $query2);

	if (!$result2) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$array = mysqli_fetch_array($result2);

	return $array;
}

function getOption($name)
{

	$query = "SELECT value FROM options WHERE name = '$name'";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row['value'];
}

function getOptionPrice($optionID, $productID)
{

	$query = "SELECT price FROM ec_product_options_inventory WHERE optionstr = '$optionID' AND productid = '$productID'";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row['price'];
}

function getPageText($slug)
{

	$query = "SELECT content FROM content JOIN cat_list ON cat_list.id = content.catID WHERE slug = '$slug'";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return stripslashes($row['content']);
}

function getPageTextID($catID)
{

	$query = "SELECT * FROM content WHERE catid = '$catID'";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row;
}

/* MODULE FUNCTIONS BEGIN */

function getCatModules($catID)
{

	$sql = "SELECT * FROM modules WHERE catid = '$catID' AND picid = '0' ORDER BY sortBy ASC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getModules($picID)
{

	$sql = "SELECT * FROM modules WHERE picid = '$picID' ORDER BY sortBy ASC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getModule($modID)
{

	$sql = "SELECT * FROM modules WHERE id = '$modID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row;
}

function getFirstModule($picID)
{

	$sql = "SELECT id FROM modules WHERE picid = '$picID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row['id'];
}

function getFirstModuleImage($moduleID, $theSort = "drag")
{

	if ($moduleID > 0) {
		$moduleID = $moduleID;
	} else {
		$moduleID = '999999999999';
	}

	$sql = "SELECT * FROM pics WHERE moduleid = '$moduleID'";

	if ($theSort === "drag") {

		$sql .= " ORDER BY pics.sortBy ASC LIMIT 0,1";
	} else {

		$sql .= " ORDER BY pics.filename LIMIT 0,1";
	}

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row;
}

function getModuleImages($moduleID, $theSort = "drag")
{

	if ($moduleID > 0) {
		$moduleID = $moduleID;
	} else {
		$moduleID = '999999999999';
	}

	$sql = "SELECT pics.*, module_pics.rowBreak as modRowBreak, module_pics.imgSize as modImgSize FROM module_pics JOIN pics ON module_pics.picid = pics.id WHERE module_pics.modid = '$moduleID'";
	//$sql = "SELECT * FROM pics WHERE moduleid = '$moduleID'";

	if ($theSort === "drag") {

		$sql .= " ORDER BY module_pics.sortBy ASC";
	} else {

		$sql .= " ORDER BY pics.filename";
	}

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getHoverImage($moduleID)
{

	$sql = "SELECT * FROM pics WHERE moduleid = '$moduleID' AND hover = 'yes' ORDER BY sortBy ASC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}
