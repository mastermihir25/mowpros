<?php
include('../connection.php');
$type=mysql_real_escape_string($_REQUEST['action']);
if($type=='add' || $type=='reorder')
{	
	$oid=mysql_real_escape_string($_REQUEST['oid']);
	$name=mysql_real_escape_string($_REQUEST['name']);
	$uid=mysql_real_escape_string($_REQUEST['uid']);
	$address=mysql_real_escape_string($_REQUEST['address']);
	$city=mysql_real_escape_string($_REQUEST['city']);
	$state=mysql_real_escape_string($_REQUEST['state']);
	$post_code=mysql_real_escape_string($_REQUEST['post_code']);
	$phone_no=mysql_real_escape_string($_REQUEST['phone_no']);
	$lotsize=mysql_real_escape_string($_REQUEST['lotsize']);
	$price=mysql_real_escape_string($_REQUEST['price']);
	$tax=mysql_real_escape_string($_REQUEST['tax']);
	$startweek=mysql_real_escape_string($_REQUEST['startweek']);
	$cleanup_req=mysql_real_escape_string($_REQUEST['cleanup_req']);
	$gatecode=mysql_real_escape_string($_REQUEST['gatecode']);
	$notes=mysql_real_escape_string($_REQUEST['notes']);
	$cardno=mysql_real_escape_string($_REQUEST['cardno']);
	$exp_month=mysql_real_escape_string($_REQUEST['exp_month']);
	$exp_year=mysql_real_escape_string($_REQUEST['exp_year']);
	$payment_status=mysql_real_escape_string($_REQUEST['payment_status']);
	$bfirstname=mysql_real_escape_string($_REQUEST['bfirstname']);
	$blastname=mysql_real_escape_string($_REQUEST['blastname']);
	$baddress=mysql_real_escape_string($_REQUEST['baddress']); 
	$bcity=mysql_real_escape_string($_REQUEST['bcity']); 
	$bstate=mysql_real_escape_string($_REQUEST['bstate']); 
	$bzip=mysql_real_escape_string($_REQUEST['bzip']); 
	$agree=mysql_real_escape_string($_REQUEST['agree']); 
	$terms=mysql_real_escape_string($_REQUEST['terms']); 
	$date=date("Y-m-d"); 	 
	if(mysql_query("insert into sv_user_order set name='$name',uid='$uid',address='$address',city='$city',state='$state',date='$date',post_code='$post_code',phone_no='$phone_no',lotsize='$lotsize',price='$price',tax='$tax',startweek='$startweek',cleanup_req='$cleanup_req',gatecode='$gatecode',notes='$notes',cardno='$cardno',exp_month='$exp_month',exp_year='$exp_year',payment_status='$payment_status',bfirstname='$bfirstname',blastname='$blastname',baddress='$baddress',bcity='$bcity',bstate='$bstate',bzip='$bzip',agree='$agree',terms='$terms'"))
	echo "Inserted";
 else
	echo "Server Error";
}
else if($type=='update')
{
	$oid=mysql_real_escape_string($_REQUEST['oid']);
	$name=mysql_real_escape_string(md5($_REQUEST['name']));
	$uid=mysql_real_escape_string($_REQUEST['uid']);
	$address=mysql_real_escape_string($_REQUEST['address']);
	$city=mysql_real_escape_string($_REQUEST['city']);
	$state=mysql_real_escape_string($_REQUEST['state']);
	$post_code=mysql_real_escape_string($_REQUEST['post_code']);
	$phone_no=mysql_real_escape_string($_REQUEST['phone_no']);
	$lotsize=mysql_real_escape_string($_REQUEST['lotsize']);
	$price=mysql_real_escape_string($_REQUEST['price']);
	$tax=mysql_real_escape_string($_REQUEST['tax']);
	$startweek=mysql_real_escape_string($_REQUEST['startweek']);
	$cleanup_req=mysql_real_escape_string($_REQUEST['cleanup_req']);
	$gatecode=mysql_real_escape_string($_REQUEST['gatecode']);
	$notes=mysql_real_escape_string($_REQUEST['notes']);
	$cardno=mysql_real_escape_string($_REQUEST['cardno']);
	$exp_month=mysql_real_escape_string($_REQUEST['exp_month']);
	$exp_year=mysql_real_escape_string($_REQUEST['exp_year']);
	$payment_status=mysql_real_escape_string($_REQUEST['payment_status']);
	$bfirstname=mysql_real_escape_string($_REQUEST['bfirstname']);
	$blastname=mysql_real_escape_string($_REQUEST['blastname']);
	$baddress=mysql_real_escape_string($_REQUEST['baddress']); 
	$bcity=mysql_real_escape_string($_REQUEST['bcity']); 
	$bstate=mysql_real_escape_string($_REQUEST['bstate']); 
	$bzip=mysql_real_escape_string($_REQUEST['bzip']); 
	$agree=mysql_real_escape_string($_REQUEST['agree']); 
	$terms=mysql_real_escape_string($_REQUEST['terms']); 
	if($oid==0)
	{
		echo "Error";
	}
	else if(mysql_query("update sv_user_order set name='$name',uid='$uid',address='$address',city='$city',state='$state',post_code='$post_code',phone_no='$phone_no',lotsize='$lotsize',price='$price',tax='$tax',startweek='$startweek',cleanup_req='$cleanup_req',gatecode='$gatecode',notes='$notes',cardno='$cardno',exp_month='$exp_month',exp_year='$exp_year',payment_status='$payment_status',bfirstname='$bfirstname',blastname='$blastname',baddress='$baddress',bcity='$bcity',bstate='$bstate',bzip='$bzip',agree='$agree',terms='$terms' where order_id='$oid'")) 
	{
		echo "Updated";
	}
	else
	{ 
		echo "Error";
	}
}
else if($type=='delete')
{
	$hid=mysql_real_escape_string($_REQUEST["hid"]);		
	if(mysql_query("delete from sv_user_order where signup_id='$hid'")) 
		echo "Deleted";
	else 
		echo "Error";
}
else if($type=='getpricequote')
{
	$lotsize=0;
function download_page($path){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$path);
    curl_setopt($ch, CURLOPT_FAILONERROR,1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $retValue = curl_exec($ch);          
    curl_close($ch);
    return $retValue;
}
$url1='http://www.zillow.com/webservice/GetSearchResults.htm?zws-id=X1-ZWz19129471e6j_38l6u&address='.urlencode($_REQUEST['address']).'&citystatezip='.urlencode($_REQUEST['city'].$_REQUEST['state'].$_REQUEST['post_code']);
//echo $url1;
//exit;
$sXML = download_page($url1);
$oXML = new SimpleXMLElement((string)$sXML);
//print_r($oXML);
//echo $oXML->response->results->result->zpid;
if(isset($oXML->response->results->result->zpid))
{
$zpid=(string) $oXML->response->results->result->zpid;

$url2='http://www.zillow.com/webservice/GetDeepComps.htm?zws-id=X1-ZWz19129471e6j_38l6u&zpid='.$zpid.'&count=1';
//echo "<br /><br />";
$sXML2 = download_page($url2);
$oXML2 = new SimpleXMLElement((string)$sXML2);
//echo "<pre>";
//print_r($oXML2);
//echo "</pre>";
$lotsize=(string)$oXML2->response->properties->principal->lotSizeSqFt;
//echo "<br />";
//echo "lotsize=".$lotsize;
//echo "<br />";
}
else
{
	$msg="Invalid address";
}
/*foreach($oXML->entry as $oEntry){
    echo $oEntry->result->zpid . "\n";
}*/
//$lotsize=$lotsize;
//exit;
$price1=0;
$price2=0;
if($lotsize>0 && $lotsize<=6000)
{
	$price1=25;
	$price2=35;
}
else if($lotsize>6000 && $lotsize<=8000)
{
	$price1=27;
	$price2=37;
}
else if($lotsize>8000 && $lotsize<=11000)
{
	$price1=30;
	$price2=40;
}
else if($lotsize>11000 && $lotsize<=13000)
{
	$price1=35;
	$price2=45;
}
else if($lotsize>11000 && $lotsize<=13000)
{
	$price1=35;
	$price2=45;
}
else if($lotsize>13000 && $lotsize<=16000)
{
	$price1=40;
	$price2=50;
}
$price1tax=($price1*8.25)/100;
$finalprice=$price1+$price1tax;
$price2tax=($price2*8.25)/100;
$finalprice2=$price2+$price2tax;

echo $lotsize."#".$price1."#".number_format($price1tax,2)."#".$price2."#".number_format($price2tax,2);

 
}

?>