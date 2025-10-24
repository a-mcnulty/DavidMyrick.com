<?php

	session_start();
	include 'includes/secure.php';
	include 'includes/functions.php';
	include 'includes/connect.php';
	
	$idStr = $_GET['id'];
	$idArray = explode(",", $idStr);
	
	require('html2pdf.php');

	$pdf=new PDF_HTML();

	$pdf->AliasNbPages();

	foreach($idArray as $orderID) {
	
		if ($orderID === "") {
			continue;
		}

		$order = getOrder($orderID);
		$products = getOrderProducts($orderID);
		$customer = getCustomer($order['customerID']);
	
		// INVOICE STARTS HERE
		
		$pdf->AddPage('P');

		$pdf->SetFont('Arial','',14);

		$pdf->SetXY(10,25);
		
		$pdf->WriteHTML( getOption("company") . '<br />' );
		$pdf->WriteHTML( getOption("address1") . ' ' . getOption("address2") . '<br />');
		$pdf->WriteHTML( getOption("city") . ', ' . getOption("state") . ' ' . getOption("zip") . ' <br />');
		if (getOption("phone") != "") {
			$pdf->WriteHTML ( 'ph: ' . getOption("phone") . '<br />');
		}
		if (getOption("fax") != "") {
			$pdf->WriteHTML ( 'fax: ' . getOption("fax") . '<br />');
		}
		
		$pdf->SetFont('Arial','',10);
		
		$pdf->WriteHTML( 'Order #: <b>' . $orderID . '</b> | Order Placed: <b>' . displayDate($order['dateAdded']) . '</b><br /><br />');
		
		$pdf->Line( 10, 55, 190, 55 );
		
		$pdf->SetXY(10,65);
		
		$pdf->WriteHTML( '<b>SOLD TO:</b><br />' );
		if ($customer['company'] != "") {
			$pdf->WriteHTML( $customer['company'] . '<br />' );
		}
		$pdf->WriteHTML( $customer['fname'] . ' ' . $customer['lname'] . '<br />' );
		$pdf->WriteHTML( $customer['address1'] );
		if ($customer['address2'] != "APT. / SUITE / UNIT") {
			$pdf->WriteHTML( ' ' . $customer['address2'] . '<br />' );
		}
		$pdf->WriteHTML( $customer['city'] . ', ' . $customer['state'] . ' ' . $customer['zip'] );
		$pdf->WriteHTML( $customer['country'] . '<br /><br />' );

		$pdf->WriteHTML( $customer['phone'] . '<br />' );
		$pdf->WriteHTML( $customer['email'] . '<br /><br />' );

		$pdf->WriteHTML( '<b>Payment Method:</b> ' . $order['paymentMethod'] . '<br /><br />' );
		
		$pdf->SetXY(100,65);
		
		$pdf->WriteHTML( '<b>SHIP TO:</b><br />' );
		$pdf->SetX(100);
		$pdf->WriteHTML( $customer['ship_fname'] . ' ' . $customer['ship_lname'] . '<br />' );
		$pdf->SetX(100);
		$pdf->WriteHTML( $customer['ship_address1'] );
		if ($customer['ship_address2'] != "APT. / SUITE / UNIT") {
			$pdf->WriteHTML( ' ' . $customer['ship_address2'] . '<br />' );
		}
		$pdf->SetX(100);
		$pdf->WriteHTML( $customer['ship_city'] . ', ' . $customer['ship_state'] . ' ' . $customer['ship_zip'] . '<br />' );
		$pdf->SetX(100);
		$pdf->WriteHTML( $customer['ship_country'] . '<br /><br />' );
		
		$pdf->SetXY(10,120);
		
		$header = array('PRODUCT', 'PRICE', 'QTY', 'SUBTOTAL');

		// Colors, line width and bold font
		$pdf->SetFillColor(102,102,102);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetLineWidth(0);
		$pdf->SetFont('','B');
		// Header
		$w = array(70, 40, 40, 40);
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		for($i=0;$i<count($header);$i++) {
			$pdf->MultiCell($w[$i],7,$header[$i],1,'L',true);
			$pdf->SetXY($x + $w[$i], $y);
			$x = $pdf->GetX();
		}
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(239,239,239);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		
		$x = $pdf->GetX();
		$y = $pdf->GetY();

		for ($count = 0; $product = mysql_fetch_array($products); ++$count) {
		
			$prodDetail = getProductDetail($product['prodID']);
			
			$optionStr = $product['optionStr'];
			$optionArray = explode(",", $optionStr);
			$sizeArray = explode(":", $optionArray[1]);
			$colorArray = explode(":", $optionArray[2]);
			
			$y1 = $pdf->GetY();
			
			$pdf->MultiCell($w[0],6,strtoupper($prodDetail['title']) . "\ncolor: " . strtolower(itemName($colorArray[1])) . " | size: " . strtolower(itemName($sizeArray[1])),'LRB','L',$fill);
			
			$y2 = $pdf->GetY();
			$yH = $y2 - $y1;
						
			$pdf->SetXY($x + $w[0], $pdf->GetY() - $yH);
			
			$pdf->Cell($w[1],$yH,'$' . number_format($product['price']),'LRB',0,'L',$fill);
			$pdf->Cell($w[2],$yH,number_format($product['qty']),'LRB',0,'L',$fill);
			$pdf->Cell($w[3],$yH,'$' . number_format($product['price'] * $product['qty']),'LRB',0,'L',$fill);

			$pdf->Ln();
			$fill = !$fill;
		}

		// Closing line
		$pdf->MultiCell(array_sum($w),0,'','T');
		$endY = $pdf->GetY();
		$pdf->SetY($endY + 3);
		
		if ($order['discountAmount'] > 0) {
			$theTotals = "subtotal: $" . getOrderSubTotal($order['id']) . "\ntax: $" . number_format($order['tax'], 2, '.', ',') . "\nshipping: (" . $order['shippingMethod'] . ") $" . number_format($order['shippingCost'], 2, '.', ',') . "discount: (" . $order['discountCode'] . ") - $" . number_format($order['discountAmount'], 2, '.', ',') . "\nTOTAL: $" . getOrderTotal($order['id']);
		} else {
			$theTotals = "subtotal: $" . getOrderSubTotal($order['id']) . "\ntax: $" . number_format($order['tax'], 2, '.', ',') . "\nshipping: (" . $order['shippingMethod'] . ") $" . number_format($order['shippingCost'], 2, '.', ',') . "\nTOTAL: $" . getOrderTotal($order['id']);
		}
		
		$pdf->SetX(10);
		$pdf->MultiCell(190, 6, $theTotals, 0, 'R');

		$pdf->SetFillColor(239,239,239);
		$pdf->SetY($pdf->GetY() + 10);
		$pdf->MultiCell(190, 6, "***For your convenience, you have been added to our email list.", 'TB', 'L', true);
		
		// PACKING SLIP STARTS HERE
		
		mysql_data_seek($products, 0);
		
		$pdf->AddPage('P');

		$pdf->SetFont('Arial','',14);

		$pdf->SetXY(10,25);
		
		$pdf->WriteHTML( getOption("company") . '<br />' );
		$pdf->WriteHTML( getOption("address1") . ' ' . getOption("address2") . '<br />');
		$pdf->WriteHTML( getOption("city") . ', ' . getOption("state") . ' ' . getOption("zip") . ' <br />');
		if (getOption("phone") != "") {
			$pdf->WriteHTML ( 'ph: ' . getOption("phone") . '<br />');
		}
		if (getOption("fax") != "") {
			$pdf->WriteHTML ( 'fax: ' . getOption("fax") . '<br />');
		}
		
		$pdf->SetFont('Arial','',10);
		
		$pdf->WriteHTML( 'Order #: <b>' . $orderID . '</b> | Order Placed: <b>' . displayDate($order['dateAdded']) . '</b><br /><br />');
		
		$pdf->Line( 10, 55, 190, 55 );
		
		$pdf->SetXY(10,65);
		
		$pdf->WriteHTML( '<b>SOLD TO:</b><br />' );
		if ($customer['company'] != "") {
			$pdf->WriteHTML( $customer['company'] . '<br />' );
		}
		$pdf->WriteHTML( $customer['fname'] . ' ' . $customer['lname'] . '<br />' );
		$pdf->WriteHTML( $customer['address1'] );
		if ($customer['address2'] != "APT. / SUITE / UNIT") {
			$pdf->WriteHTML( ' ' . $customer['address2'] . '<br />' );
		}
		$pdf->WriteHTML( $customer['city'] . ', ' . $customer['state'] . ' ' . $customer['zip'] );
		$pdf->WriteHTML( $customer['country'] . '<br /><br />' );

		$pdf->WriteHTML( $customer['phone'] . '<br />' );
		$pdf->WriteHTML( $customer['email'] . '<br /><br />' );

		$pdf->WriteHTML( '<b>Payment Method:</b> ' . $order['paymentMethod'] . '<br /><br />' );
		
		$pdf->SetXY(100,65);
		
		$pdf->WriteHTML( '<b>SHIP TO:</b><br />' );
		$pdf->SetX(100);
		$pdf->WriteHTML( $customer['ship_fname'] . ' ' . $customer['ship_lname'] . '<br />' );
		$pdf->SetX(100);
		$pdf->WriteHTML( $customer['ship_address1'] );
		if ($customer['ship_address2'] != "APT. / SUITE / UNIT") {
			$pdf->WriteHTML( ' ' . $customer['ship_address2'] . '<br />' );
		}
		$pdf->SetX(100);
		$pdf->WriteHTML( $customer['ship_city'] . ', ' . $customer['ship_state'] . ' ' . $customer['ship_zip'] . '<br />' );
		$pdf->SetX(100);
		$pdf->WriteHTML( $customer['ship_country'] . '<br /><br />' );
		
		$pdf->SetXY(10,120);
		
		$header = array('PRODUCT', 'QTY');

		// Colors, line width and bold font
		$pdf->SetFillColor(102,102,102);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetLineWidth(0);
		$pdf->SetFont('','B');
		// Header
		$w = array(150, 40);
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		for($i=0;$i<count($header);$i++) {
			$pdf->MultiCell($w[$i],7,$header[$i],1,'L',true);
			$pdf->SetXY($x + $w[$i], $y);
			$x = $pdf->GetX();
		}
		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(239,239,239);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;
		
		$x = $pdf->GetX();
		$y = $pdf->GetY();

		for ($count = 0; $product = mysql_fetch_array($products); ++$count) {
		
			$prodDetail = getProductDetail($product['prodID']);
			
			$optionStr = $product['optionStr'];
			$optionArray = explode(",", $optionStr);
			$sizeArray = explode(":", $optionArray[1]);
			$colorArray = explode(":", $optionArray[2]);
			
			$y1 = $pdf->GetY();
			
			$pdf->MultiCell($w[0],6,strtoupper($prodDetail['title']) . "\ncolor: " . strtolower(itemName($colorArray[1])) . " | size: " . strtolower(itemName($sizeArray[1])),'LRB','L',$fill);
			
			$y2 = $pdf->GetY();
			$yH = $y2 - $y1;
						
			$pdf->SetXY($x + $w[0], $pdf->GetY() - $yH);
			
			$pdf->Cell($w[2],$yH,number_format($product['qty']),'LRB',0,'L',$fill);

			$pdf->Ln();
			$fill = !$fill;
		}

		// Closing line
		$pdf->MultiCell(array_sum($w),0,'','T');
		
	}
	
	$pdf->Output();

?>