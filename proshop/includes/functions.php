<?php

Database::initialize();

//DEFINE PAGE GLOBALS
date_default_timezone_set('America/Los_Angeles');

global $catID, $parentID, $picID, $parentParentID, $parentDetails, $thisCatID;

if (isset($_REQUEST['category'])) {
	$catID = $_REQUEST['category'];
}

if (isset($_REQUEST['picID'])) {
	$picID = $_REQUEST['picID'];
}

if (isset($_REQUEST['parentID'])) {
	$parentID = $_REQUEST['parentID'];
}

$_SESSION['catID'] = $catID;
$_SESSION['picID'] = $picID;

function getLnt($zip)
{
	$url = "https://maps.googleapis.com/maps/api/geocode/json?address=
		" . urlencode($zip) . "&sensor=false&key=AIzaSyCHp7NpcSs5wqNdGUHet1VfiwOwwBHldTM";
	$result_string = file_get_contents($url);
	$result = json_decode($result_string, true);

	$result1[] = $result['results'][0];
	$result2[] = $result1[0]['geometry'];
	$result3[] = $result2[0]['location'];
	return $result3[0];
}

function embedURL($url)
{

	// Grab the video url from the url, or use default
	$video_url = $url;

	if (stristr($url, "youtube")) {

		$oembed_endpoint = 'http://www.youtube.com/oembed';
		$xml_url = $oembed_endpoint . '?url=' . rawurlencode($video_url) . "&autoplay=true&width=1920&format=xml";
	} else {

		$oembed_endpoint = 'http://vimeo.com/api/oembed';
		$xml_url = $oembed_endpoint . '.xml?url=' . rawurlencode($video_url) . "&autoplay=true&width=1920";
	}

	/*
		// Create the URLs
		$json_url = $oembed_endpoint . '.json?url=' . rawurlencode($video_url) . "&autoplay=true&width=1920";
		$xml_url = $oembed_endpoint . '.xml?url=' . rawurlencode($video_url) . "&autoplay=true&width=1920";
		*/

	// Curl helper function
	function curl_get($url)
	{
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		$return = curl_exec($curl);
		curl_close($curl);
		return $return;
	}

	// Load in the oEmbed XML
	$oembed = simplexml_load_string(curl_get($xml_url));

	return $oembed;
}

function generateRandomString($length = 6)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function getLayouts()
{

	$sql = "SELECT * FROM layouts ORDER BY layout ASC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getModLayouts()
{

	$sql = "SELECT * FROM layouts WHERE ismod != 'no' ORDER BY layout ASC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getLayout($layout)
{

	$sql = "SELECT * FROM layouts WHERE layout = '$layout'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row;
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

function getPicMainVideo($picID)
{

	$query = "SELECT * FROM videos WHERE picid = '$picID' AND file <> ''";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row;
}

//USER FUNCTIONS

function getUsers()
{

	$sql = "SELECT * FROM admin ORDER BY username ASC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getUser($userID)
{

	$sql = "SELECT * FROM admin WHERE id = '$userID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return mysqli_fetch_array($result);
}

//TAG FUNCTIONS

function getTags($section = "work")
{

	$query = "SELECT * FROM tags WHERE section = '$section' ORDER BY sortBy ASC";

	$result = mysqli_query(Database::$conn, $query);

	return $result;
}

function getTag($id)
{

	$query = "SELECT * FROM tags WHERE id = '$id'";

	$result = mysqli_query(Database::$conn, $query);
	$row = mysqli_fetch_array($result);

	return $row;
}

/* ***********************ECOM FUNCTIONS**************************** */

function createDateRangeArray($strDateFrom, $strDateTo)
{
	// takes two dates formatted as YYYY-MM-DD and creates an
	// inclusive array of the dates between the from and to dates.

	// could test validity of dates here but I'm already doing
	// that in the main script

	$days = array();

	$iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2),     substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
	$iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2),     substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

	if ($iDateTo >= $iDateFrom) {
		array_push($days, date('Y-m-d', $iDateFrom)); // first entry
		while ($iDateFrom < $iDateTo) {
			$iDateFrom += 86400; // add 24 hours
			array_push($days, date('Y-m-d', $iDateFrom));
		}
	}
	return $days;
}

function getTaxAndShipping($dateRange = "")
{

	$nextDay = date("Y-m-d", strtotime('+1 days'));
	$endDate = date("Y-m-d", strtotime('-' . $dateRange . ' days'));

	$sql = "SELECT SUM(subtotal) as prodTotal, COUNT(id) as orderCount, SUM(tax) as taxTotal, SUM(shippingCost) as shipTotal FROM ec_orders WHERE (status = '5' OR status = '6') AND (dateAdded >= '$endDate' AND dateAdded < '$nextDay')";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getSalesByCustomer($dateRange = "", $sort = "")
{

	$nextDay = date("Y-m-d", strtotime('+1 days'));
	$endDate = date("Y-m-d", strtotime('-' . $dateRange . ' days'));

	if ($sort === "") {
		$sort = "orderCount DESC";
	} else {
		$sort = $sort;
	}

	$sql = "SELECT SUM(subtotal) as dayTotal, COUNT(id) as orderCount, billfname, billlname FROM ec_orders WHERE (status = '5' OR status = '6') AND (dateAdded >= '$endDate' AND dateAdded < '$nextDay') GROUP BY billlname ORDER BY $sort";

	//echo $sql;

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getSalesByCountry($dateRange = "", $sort = "")
{

	$nextDay = date("Y-m-d", strtotime('+1 days'));
	$endDate = date("Y-m-d", strtotime('-' . $dateRange . ' days'));

	if ($sort === "") {
		$sort = "orderCount DESC";
	} else {
		$sort = $sort;
	}

	$sql = "SELECT SUM(subtotal) as dayTotal, COUNT(id) as orderCount, billcountry FROM ec_orders WHERE (status = '5' OR status = '6') AND (dateAdded >= '$endDate' AND dateAdded < '$nextDay') GROUP BY billcountry ORDER BY $sort";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getSalesByDay($dateRange = "", $sort = "")
{

	$nextDay = date("Y-m-d", strtotime('+1 days'));
	$endDate = date("Y-m-d", strtotime('-' . $dateRange . ' days'));

	if ($sort === "") {
		$sort = "dayTotal DESC";
	} else {
		$sort = $sort;
	}

	$sql = "SELECT SUM(subtotal) as dayTotal, COUNT(id) as orderCount, dateAdded FROM ec_orders WHERE (status = '5' OR status = '6') AND (dateAdded >= '$endDate' AND dateAdded < '$nextDay') GROUP BY YEAR(dateAdded), MONTH(dateAdded), DAY(dateAdded) ORDER BY $sort";

	//echo $sql;

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getSales($theDate = "")
{

	$timeStamp = strtotime($theDate);
	$nextDay = strtotime('+1 days', $timeStamp);
	$nextDay = date('Y-m-d', $nextDay);

	$sql = "SELECT SUM(subtotal) as dayTotal, COUNT(id) as orderCount FROM ec_orders WHERE (status = '5' OR status = '6') AND (dateAdded >= '$theDate' AND dateAdded < '$nextDay')";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row;
}

function getOptionSets()
{

	$sql1 = "SELECT id FROM cat_list WHERE contentType = 'options'";

	$result1 = mysqli_query(Database::$conn, $sql1);

	if (!$result1) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row1 = mysqli_fetch_array($result1);
	$parentID = $row1['id'];

	$sql = "SELECT * FROM cat_list WHERE parentid = '$parentID' ORDER BY sortBy ASC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function checkProductOptions($productID)
{

	$sql = "SELECT * FROM ec_product_options_inventory WHERE productid = '$productID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$num = mysqli_num_rows($result);

	return $num;
}

function getProductOptionSets($productID)
{

	$sql = "SELECT DISTINCT(optionsetid) FROM ec_product_options WHERE productid = '$productID' ORDER BY sortBy ASC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getProductInventory($productID)
{

	$sql = "SELECT * FROM ec_product_options_inventory WHERE productid = '$productID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getProductVariantID($productID, $variantID)
{

	$variantArray = array();

	$sql = "SELECT * FROM ec_product_options_inventory WHERE productid = '$productID' AND optionstr = '$variantID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	if (mysqli_num_rows($result) > 0) {

		$variantArray[] = $row['id'];
		$variantArray[] = $row['qty'];

		return $variantArray;
	} else {

		return "N/A";
	}
}

function ignoreInventory($productID)
{

	$sql = "SELECT ignoreQty FROM pics WHERE id = '$productID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row['ignoreQty'];
}
function getOptions($catID, $format = "select")
{

	$sql = "SELECT * FROM pics WHERE catid = '$catID' ORDER BY sortBy ASC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	if ($format === "select") {

		$optionStr = "";
		for ($count = 1; $pic = mysqli_fetch_array($result); ++$count) {

			$optionStr .= "<option value='" . $pic['id'] . "'>" . $pic['title'] . "</option>";
		}

		return $optionStr;
	} else {

		return $result;
	}
}

function getOptionsID($optionID, $catID, $format = "select")
{

	$sql = "SELECT * FROM ec_options WHERE id = '$optionID' ORDER BY sortBy ASC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);
	$optionStr = $row['options'];
	$optionArray = explode(",", $optionStr);

	$sql2 = "SELECT * FROM pics WHERE catid = '$catID'";

	$result2 = mysqli_query(Database::$conn, $sql2);

	if (!$result2) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$optionStr = "";
	for ($count = 1; $pic = mysqli_fetch_array($result2); ++$count) {

		if (in_array($pic['id'], $optionArray)) {

			$optionStr .= "<option value='" . $pic['id'] . "' selected>" . $pic['title'] . "</option>";
		} else {

			$optionStr .= "<option value='" . $pic['id'] . "'>" . $pic['title'] . "</option>";
		}
	}

	echo $optionStr;
}

function checkOptions($picID, $optionID)
{

	$sql = "SELECT * FROM ec_options WHERE optionid = '$optionID' AND picid = '$picID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$num = mysqli_num_rows($result);

	return $num;
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

	foreach ($relatedArray as $relatedID) {

		if ($relatedID === "") {
			continue;
		}

		$sql2 = "SELECT * FROM pics WHERE id = '$relatedID'";

		$result2 = mysqli_query(Database::$conn, $sql2);

		if (!$result2) {
			die('Could not query:' . mysqli_error(Database::$conn));
			exit;
		}

		$row2 = mysqli_fetch_array($result2);

		echo '<img src="' . getOption("imagePath") . 'thumbs/' . $row2['img'] . '" class="relatedThumb" id="' . $relatedID . '" width="50" style="display: inline-block; margin: 0 3px; cursor: pointer;">';
	}
}

function getShopCategories($format = "select", $parentOnly = "no")
{

	$sql = "SELECT * FROM cat_list WHERE contentType = 'category' AND parentID = 0 ORDER BY sortBy ASC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	if ($format === "select") {

		if ($parentOnly != "yes") {
			$optionStr = "<option value=''>VIEW ALL</option>";
		}
		for ($count = 1; $cat = mysqli_fetch_array($result); ++$count) {

			$optionStr .= "<option value='" . $cat['id'] . "'>" . strtoupper($cat['name']) . "</option>";

			if ($parentOnly != "yes") {

				// check for sub-categories
				$thisCat = $cat['id'];

				$sql2 = "SELECT * FROM cat_list WHERE parentID = '$thisCat'";

				$result2 = mysqli_query(Database::$conn, $sql2);

				$suboptionStr = "";
				for ($subcount = 1; $subcat = mysqli_fetch_array($result2); ++$subcount) {

					$optionStr .= "<option value='" . $subcat['id'] . "'>" . strtolower($subcat['subName']) . "</option>";
				}
			}
		}

		echo $optionStr;
	} else {

		return $result;
	}
}

function getProductOptions($format = "select")
{

	$sql = "SELECT * FROM cat_list WHERE contentType = 'options' AND parentID = 0";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	if ($format === "select") {

		$optionStr = "<option value=''>VIEW ALL</option>";
		for ($count = 1; $cat = mysqli_fetch_array($result); ++$count) {

			$optionStr .= "<option value='" . $cat['id'] . "'>" . strtoupper($cat['name']) . "</option>";

			// check for sub-categories
			$thisCat = $cat['id'];

			$sql2 = "SELECT * FROM cat_list WHERE parentID = '$thisCat'";

			$result2 = mysqli_query(Database::$conn, $sql2);

			$suboptionStr = "";
			for ($subcount = 1; $subcat = mysqli_fetch_array($result2); ++$subcount) {

				$optionStr .= "<option value='" . $subcat['id'] . "'>" . strtolower($subcat['subName']) . "</option>";
			}
		}

		echo $optionStr;
	} else {

		return $result;
	}
}

function getOrders($searchBy = "", $orderBy = "id DESC", $limit = "")
{

	$sql = "SELECT * FROM ec_orders";

	if ($searchBy != "") {
		$sql .= " WHERE (shipfname LIKE '%" . $searchBy . "%' OR shiplname LIKE '%" . $searchBy . "%' OR billfname LIKE '%" . $searchBy . "%' OR billlname LIKE '%" . $searchBy . "%')";
	}

	$sql .= " ORDER BY $orderBy";

	if ($limit != "") {
		$sql .= " LIMIT 0, $limit";
	}

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
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

function checkProdOptions($picID)
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

function getCustomers($searchBy = "", $orderBy = "dateAdded DESC", $limit = "")
{

	$sql = "SELECT * FROM ec_customers";

	if ($searchBy != "") {
		$sql .= " WHERE (fname LIKE '%" . $searchBy . "%' OR lname LIKE '%" . $searchBy . "%' OR ship_fname LIKE '%" . $searchBy . "%' OR ship_lname LIKE '%" . $searchBy . "%')";
	}

	$sql .= " ORDER BY $orderBy";

	if ($limit != "") {
		$sql .= " LIMIT 0, $limit";
	}

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getCustomer($customerID)
{

	$sql = "SELECT * FROM ec_customers WHERE id = '$customerID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row;
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

function getCoupons($limit = "")
{

	$sql = "SELECT * FROM ec_coupons ORDER BY dateAdded DESC";

	if ($limit != "") {
		$sql .= " LIMIT 0, $limit";
	}

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getCoupon($couponID)
{

	$sql = "SELECT * FROM ec_coupons WHERE id = '$couponID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return mysqli_fetch_array($result);
}

function getOrderHistory($orderID)
{

	$sql = "SELECT * FROM ec_order_history WHERE orderID = '$orderID' ORDER BY id DESC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getStatuses()
{

	$sql = "SELECT * FROM ec_order_status ORDER BY id ASC";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function countCoupon($code)
{

	$sql = "SELECT id FROM ec_orders WHERE discountCode = '$code'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$num = mysqli_num_rows($result);

	return $num;
}


/* ***********************ECOM FUNCTIONS**************************** */

/* ***********************EMAIL FUNCTIONS*************************** */

function sendMail($toName, $toEmail, $fromName, $fromEmail, $subject, $msg)
{

	$mail = new PHPMailer();

	$body = "<font face='Arial' size='2'>" . $msg . "</font>";
	$body = eregi_replace("[\]", '', $body);

	$mail->AddReplyTo($fromEmail, $fromName);

	$mail->SetFrom($fromEmail, $fromName);

	$address = $toEmail;
	$mail->AddAddress($address, $toName);

	$mail->Subject    = $subject;

	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";

	$mail->MsgHTML($body);

	if (!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		//echo "Message sent!";
	}
}

/* ***********************EMAIL FUNCTIONS*************************** */

function checkContent($catID)
{

	$sql = "SELECT contentType FROM cat_list WHERE id = '$catID'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);
	return $row['contentType'];
}

function showStatus($status)
{

	$displayStatus = strtoupper(str_replace("_", " ", $status));
	if ($status != "") {
		echo '<div id="alert">' . $displayStatus . '</div>';
	}
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

function displayDateTime($thisDate)
{

	$thisDate = strtotime($thisDate);
	$thisDate = strftime("%m/%d/%y %l:%M %p", $thisDate);

	return $thisDate;
}

function timeDate($thisTime)
{

	$thisDate = strftime("%m/%d/%y", $thisTime);

	return $thisDate;
}

function createSlug($str)
{

	$slug = strtolower($str);
	$slug = str_replace(" ", "-", $slug);
	$slug = str_replace("&", "and", $slug);
	$slug = str_replace("/", "-", $slug);
	$slug = preg_replace("/[^a-z0-9_@\-]/", '', $slug);

	return $slug;
}

function uniqueSlug($str)
{

	$sql = "SELECT * FROM pics WHERE customSlug = '$str'";

	$result = mysqli_query(Database::$conn, $sql);
	$num = mysqli_num_rows($result);

	return $num;
}

function itemName($picID)
{

	$sql = "SELECT title FROM pics WHERE id = '$picID'";

	$result = mysqli_query(Database::$conn, $sql);
	$row = mysqli_fetch_array($result);

	return $row['title'];
}

function getParentID($catID)
{

	$query = "SELECT parentID FROM cat_list WHERE id = '$catID'";

	$result = mysqli_query(Database::$conn, $query);
	$row = mysqli_fetch_array($result);

	return $row['parentID'];
}

function cleanFileName($name)
{

	// FIRST STEP IS TO IDENTIFY TYPE OF IMAGE AND SAVE EXTENSION

	$name = strtolower($name);

	if (stristr($name, '.jpg')) {
		$ext = ".jpg";
	} elseif (stristr($name, '.jpeg')) {
		$ext = ".jpg";
	} elseif (stristr($name, '.png')) {
		$ext = ".png";
	} elseif (stristr($name, '.gif')) {
		$ext = ".gif";
	} else {
		$ext = ".jpg";
	}

	$name = str_replace(".jpg", "", $name);
	$name = str_replace(" ", "-", $name);
	$name = str_replace(".", "", $name);
	$name = str_replace("'", "", $name);
	$name = str_replace("#", "", $name);
	$name = str_replace("(", "", $name);
	$name = str_replace(")", "", $name);

	$name .= $ext;

	return $name;
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
		}

		$myCrumb = "> <a href='" . $rd . "?category=" . $row['parentID'] . "'>" . catName($row['parentID']) . "</a> > <span class='pageOn'>" . $row['subName'] . "</span>";
	}

	return $myCrumb;
}

function imageSpecs()
{

	if (getOption("imageHeight") != "" and getOption("imageWidth") != "") {

		return "For best results images should be " . getOption("imageWidth") . " pixels wide by " . getOption("imageHeight") . " pixels tall";
	} elseif (getOption("imageHeight") != "") {

		return "For best results images should be no taller than " . getOption("imageHeight") . " pixels.";
	} else {

		return "For best results images should be no wider than " . getOption("imageWidth") . " pixels.";
	}
}

// THIS QUERY IS FOR STORE CONTENT
function catList()
{

	$query = "SELECT name, id, pageType, status, parentID, imgON, imgOFF, link AS linkURL, sectionID FROM cat_list WHERE parentID = 0 AND contentType = 'page' ORDER BY sortBy ASC";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function subcatList($parentID)
{

	$query = "SELECT subName, id, pageType, status, parentID, imgON, imgOFF, link AS linkURL FROM cat_list WHERE parentID = '$parentID' ORDER BY sortBy ASC";

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
		die('Could not query:' . mysqli_error(Database::$conn));
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

function countMedia()
{

	$sql = "SELECT id FROM pics";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$num = mysqli_num_rows($result);

	return $num;
}

function searchMedia($keyword)
{

	$sql = "SELECT * FROM pics WHERE filename LIKE '%" . $keyword . "%' OR title LIKE '%" . $keyword . "%'";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getMedia($start = 0, $limit = 100)
{

	$sql = "SELECT * FROM pics ORDER BY filename ASC LIMIT $start, $limit";

	$result = mysqli_query(Database::$conn, $sql);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
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

function checkStatus($catID)
{

	$query = "SELECT status FROM cat_list WHERE id = '$catID'";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row['status'];
}

function getPageText($catID)
{

	$query = "SELECT content FROM content WHERE catid = '$catID'";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row['content'];
}

function getPageTextAll($catID)
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

function getPageDetailText($picID)
{

	$query = "SELECT * FROM content WHERE picid = '$picID'";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row;
}

function getPicVideo($picID)
{

	$query = "SELECT * FROM videos WHERE picid = '$picID'";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getVideoDetailText($vidID)
{

	$query = "SELECT content FROM content WHERE vidid = '$vidID'";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row['content'];
}

function getPageLink($catID)
{

	$query = "SELECT link FROM cat_list WHERE id = '$catID'";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row['link'];
}

function getNews($url)
{

	$doc = new DOMDocument();
	$doc->load($url);
	$arrFeeds = array();
	foreach ($doc->getElementsByTagName('item') as $node) {
		$itemRSS = array(
			'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
			'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
			'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
			'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue
		);
		array_push($arrFeeds, $itemRSS);
	}

	return $arrFeeds;
}

/* MODULES FUNCTIONS BEGIN */

function getModules($picID)
{

	$query = "SELECT modules.*, layouts.icon FROM modules JOIN layouts ON layouts.layout = modules.pageType WHERE picid = '$picID' ORDER BY sortBy ASC";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getCatModules($catID)
{

	$query = "SELECT modules.*, layouts.icon FROM modules JOIN layouts ON modules.pageType = layouts.layout WHERE catid = '$catID' AND picid = '0' ORDER BY sortBy ASC";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}

function getModule($id)
{

	$query = "SELECT * FROM modules WHERE id = '$id'";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row;
}

function getModuleText($id)
{

	$query = "SELECT content FROM modules WHERE id = '$id'";

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	$row = mysqli_fetch_array($result);

	return $row['content'];
}

function getModuleImages($id, $theSort = "drag")
{

	//$query = "SELECT * FROM pics WHERE moduleid = '$id'";

	$query = "SELECT pics.*, module_pics.rowBreak as modRowBreak, module_pics.imgSize as modImgSize FROM module_pics JOIN pics ON module_pics.picid = pics.id WHERE module_pics.modid = '$id'";

	if ($theSort === "drag") {

		$query .= " ORDER BY module_pics.sortBy ASC";
	} else {

		$query .= " ORDER BY pics.filename";
	}

	$result = mysqli_query(Database::$conn, $query);

	if (!$result) {
		die('Could not query:' . mysqli_error(Database::$conn));
		exit;
	}

	return $result;
}
	
	/* MODULES FUNCTIONS END */
