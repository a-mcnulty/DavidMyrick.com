<html>
<head>
<title>PHPMailer - Mail() basic test</title>
</head>
<body>

<?php

require_once('../classes/class.phpmailer.php');

$mail             = new PHPMailer(); // defaults to using php "mail()"

$body             = "My test email message";
$body             = eregi_replace("[\]",'',$body);

$mail->AddReplyTo("sales@smartglassjewelry.com","Smartglass Jewelry");

$mail->SetFrom('sales@smartglassjewelry.com', 'Smartglass Jewelry');

$address = "neal@eastofwestern.com";
$mail->AddAddress($address, "Neal St. Clair");

$mail->Subject    = "PHPMailer Test Subject via mail(), basic";

$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

$mail->MsgHTML($body);

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}

?>

</body>
</html>
