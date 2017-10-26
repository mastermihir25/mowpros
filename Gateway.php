<?php

class Gateway {

//main function to process the transactions
public function process($TransactionType, $GatewayUserName, $GatewayPassword, $Custom_Field_1, $Custom_Field_2, $PaymentType, $SAFE_Action, $SAFE_ID, $CCNumber, $CCExpDate, $CVV, $CheckName, $CheckABA, $CheckAccount, $AccountHolderType, $AccountType, $SecCode, $Amount, $Tax, $Shipping, $OrderDescription, $OrderID, $PONumber, $FirstName, $LastName, $Company, $Address1, $Address2, $City, $State, $Zip, $Country, $Phone, $Fax, $EMail, $Website, $ShippingFirstName, $ShippingLastName, $ShippingCompany, $ShippingAddress1, $ShippingAddress2, $ShippingCity, $ShippingState, $ShippingZip, $ShippingCountry, $ShippingEmail, $ProcessorID, $MagData )  {

	ini_set('display_errors', '1');

	//load nusoap code - required
	require_once('includes/nusoap.php');

	//create client - required
	$soapclient = new nusoap_client('https://secure.transaxgateway.com/roxapi/rox.asmx?WSDL','wsdl');

	//gather parameters can be customized for fields you won't use
	$params['TransactionType'] = $TransactionType;
	$params['GatewayUserName'] = $GatewayUserName;
	$params['GatewayPassword'] = $GatewayPassword;
	$params['IPAddress'] = $_SERVER['REMOTE_ADDR'];
	$params['Custom_Field_1'] = $Custom_Field_1;
	$params['Custom_Field_2'] = $Custom_Field_2;
	$params['PaymentType'] = $PaymentType;
	$params['SAFE_Action'] = $SAFE_Action;
	$params['SAFE_ID'] = $SAFE_ID;
	$params['CCNumber'] = $CCNumber;
	$params['CCExpDate'] = $CCExpDate;
	$params['CVV'] = $CVV;
	$params['CheckName'] = $CheckName;
	$params['CheckABA'] = $CheckABA;
	$params['CheckAccount'] = $CheckAccount;
	$params['AccountHolderType'] = $AccountHolderType;
	$params['AccountType'] = $AccountType;
	$params['SecCode'] = $SecCode;
	$params['Amount'] = $Amount;
	$params['Tax'] = $Tax;
	$params['Shipping'] = $Shipping;
	$params['OrderDescription'] = $OrderDescription;
	$params['OrderID'] = $OrderID;
	$params['PONumber'] = $PONumber;
	$params['FirstName'] = $FirstName;
	$params['LastName'] = $LastName;
	$params['Company'] = $Company;
	$params['Address1'] = $Address1;
	$params['Address2'] = $Address2;
	$params['City'] = $City;
	$params['State'] = $State;
	$params['Zip'] = $Zip;
	$params['Country'] = $Country;
	$params['Phone'] = $Phone;
	$params['Fax'] = $Fax;
	$params['EMail'] = $EMail;
	$params['Website'] = $Website;
	$params['ShippingFirstName'] = $ShippingFirstName;
	$params['ShippingLastName'] = $ShippingLastName;
	$params['ShippingCompany'] = $ShippingCompany;
	$params['ShippingAddress1'] = $ShippingAddress1;
	$params['ShippingAddress2'] = $ShippingAddress2;
	$params['ShippingCity'] = $ShippingCity;
	$params['ShippingState'] = $ShippingState;
	$params['ShippingZip'] = $ShippingZip;
	$params['ShippingCountry'] = $ShippingCountry;
	$params['ShippingEmail'] = $ShippingEmail;
	$params['ProcessorID'] = $ProcessorID;

	//magtek data
	$params['MagData'] = $MagData;

	//creates the proxy
   	$proxy = $soapclient->getProxy();

	//process the transaction
	$result = $proxy->ProcessTransaction(Array('objparameters' => $params));

	//return the results
	return $result;

	}

}

?>