
<?php
//including the Gateway class in this page - required
include 'Gateway.php';

//declare an instance of the Gateway object to process transactions  - required
$Gateway = new Gateway();

//fields passed depend on situation, see function for full list of parameters and order they go in. a lot of them are being ignored in this example
$TransactionType = "sale";
$PaymentType = "creditcard";

//gateway credentials 
$GatewayUserName = "flexportdemo";
$GatewayPassword = "FlexPort123!";

$CCNumber = "4111111111111111";
$CCExpDate = "1420";
$CVV = "123";

//general transaction request fields
$Amount = "23.25";
$OrderDescription = "widget";
$FirstName = "Tim";
$LastName = "Tester";
$EMail = "fake@fakeemail.com";
$SAFE_Action = "";
$SAFE_ID = "";


//encrypted mag tek data fake card example
//$MagData = "%B4847180004001326^RECIPIENT/GIFT CARD^24060000000000000000000?;4847180004001326=240600000000000?|0600|6064B8E8FA692E6C4F2E1EE2799EA1C27AFF26297A6809A93090EDBF9B3F7E2D172E1D19B810F8186DC6441B1A5AAD79663AB6E8BF8793ED5BC7A74BFC5A74BC|1DAF1CE832BF57E59A8D799BCE39FBCB7468F1088D9D7F4775F18923C6FB11E21A59CFBCD8DDA43B||61403000|0A9800459E1D9ED5FEAB709E2EB9567927C637F2C48B1F7E41CE9D373E400F860E08B3C4647A543AA28498F9B55C1CFB9A5FD9A16C5E9557|B1C5554082813AA|964D303CA7BE8750|9011880B1C555400008A|3B8F||0000";
$MagData = "";

//calling process transaction via the Gateway object -- required
$results = $Gateway->process($TransactionType, $GatewayUserName, $GatewayPassword, "", "", $PaymentType, $SAFE_Action, $SAFE_ID, $CCNumber, $CCExpDate, $CVV, "", "", "", "", "", "", $Amount, "", "", $OrderDescription, "", "", $FirstName, $LastName, "", "", "", "", "", "", "", "", "", $EMail, "", "", "", "", "", "", "", "", "", "", "", "", $MagData);

//an array of results
print_r($results);

//pulling out individual response values
echo $results['ProcessTransactionResult']['STATUS_CODE'];



?>