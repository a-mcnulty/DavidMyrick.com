<?php

	error_reporting(E_ERROR | E_PARSE);

	session_start();
	include("connect.php");
	include("functions.php");
	include('../classes/upsRates.class');

	$customer_id = $_SESSION['customer_id'];
	$customer = getCustomer($customer_id);

	$MyUPS= new ups();
        $MyUPS->setCurlVerifyCert(false);
        $MyUPS->SetAccountInfo($ups_xml_access_key,$ups_userid,$ups_password);
        $MyUPS->SetPickupType(01); 				#Set daily-pickup
        $MyUPS->SetShipper(getOption("city"),
                           getOption("state"),
                           getOption("zip"),
                           getOption("country"));

        $MyUPS->SetShipFrom(
                           getOption("city"),
                           getOption("state"),
                           getOption("zip"),
                           getOption("country"));

        $MyUPS->SetShipTo(  addslashes(trim($customer['ship_city'])),
                            addslashes(trim($customer['ship_state'])),
                            addslashes(trim($customer['ship_zip'])),
                            addslashes(trim($customer['ship_country'])),
                            $residental = true);

	$weight = weighCartItems($sid);  // NEED TO ADD THE WEIGHT OF ALL ITEMS IN CART

	$price  = getCartTotal($sid, $customer['type'], "shipping");    // SUBTOTAL FOR CURRENT CART
	$added_handling_price = 0;

	$MyUPS->AddPackage('02', getOption("company") .' Package',$weight,$price,'LBS','USD');
	$MyUPS->ModeRateShop();
	$MyUPS->SetRateListLimit('01','02','03');
	$MyUPS->GetRateListShort($added_handling_price);

    $errcode = $MyUPS->GetErrorCode();
    if($errcode > 0){
        $err = $MyUPS->GetErrorDescription();
        die($err);
    }

    $arr_shippings = array(
    'Next Day Air' => $MyUPS->ModeGetRate('01'),
    '2nd Day Air'  => $MyUPS->ModeGetRate('02'),
    'Ground'       => $MyUPS->ModeGetRate('03'));

    //$MyUPS->Debug();
    //$MyUPS->Debug2();

?>

<script>

$(".itemPopup").colorbox({top:"16%", width:"850px", height:"563px", iframe:true});

function validate() {
	var radioLength = document.shipping.shipping_method.length;
	var formPass = false;
	for(var i = 0; i < radioLength; i++) {
		if(document.shipping.shipping_method[i].checked) {
			formPass = true
		}
	}
	if (formPass) {

	} else {
		alert("Please select a shipping method");
		return false;
	}
}

</script>

<div style="display: inline-block; width: 48%; vertical-align: top;">

	<form name="shipping" action="checkout_payment.php" method="post" onsubmit="return validate()">

	<h4 style="margin-bottom: 10px;">Shipping Method:</h4>
	<p>
	<?php foreach($arr_shippings as $method => $value) { ?>

		<input type="radio" name="shipping_method" id="shipping_method" value="<?=$method?> - <?=$value?>"> <?=$method?> - $<?=$value?><br />

	<?php } ?>
	</p>

	<button class="btn callOut" style="margin-top: 40px;">SELECT</button>

	</form>

</div>

<div style="display: inline-block; width: 48%; vertical-align: top;">

	<h4 style="margin-bottom: 10px; float: left;">Shipping Address:</h4> <a href="addressForm.php?type=shipping" class="alert itemPopup" style="float: left; margin-left: 10px;">change</a>
	<p style="clear: both;">
	<?=$customer['ship_fname']?> <?=$customer['ship_lname']?><br />
	<?=$customer['ship_address1']?> <?=$customer['ship_address2']?><br />
	<?=$customer['ship_city']?>, <?=$customer['ship_state']?> <?=$customer['ship_zip']?><br />
	<?=$customer['ship_country']?><br /><br />
	</p>

</div>