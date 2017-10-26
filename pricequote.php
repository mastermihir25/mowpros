<?php include('connection.php');
 
 
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
$url1='http://www.zillow.com/webservice/GetSearchResults.htm?zws-id=X1-ZWz19129471e6j_38l6u&address='.urlencode($_SESSION['address']).'&citystatezip='.urlencode($_SESSION['citystatezip']);
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
$_SESSION['lotsize']=$lotsize;
//exit;
$price1=0;
$price2=0;
if($_SESSION['lotsize']>0 && $_SESSION['lotsize']<=6000)
{
	$price1=25;
	$price2=35;
}
else if($_SESSION['lotsize']>6000 && $_SESSION['lotsize']<=8000)
{
	$price1=27;
	$price2=37;
}
else if($_SESSION['lotsize']>8000 && $_SESSION['lotsize']<=11000)
{
	$price1=30;
	$price2=40;
}
else if($_SESSION['lotsize']>11000 && $_SESSION['lotsize']<=13000)
{
	$price1=35;
	$price2=45;
}
else if($_SESSION['lotsize']>11000 && $_SESSION['lotsize']<=13000)
{
	$price1=35;
	$price2=45;
}
else if($_SESSION['lotsize']>13000 && $_SESSION['lotsize']<=16000)
{
	$price1=40;
	$price2=50;
}
?>

 
<style type="text/css" media="all" id="wpHomePage_K7VHRMtk">
#q9P9UxJo {
position: absolute;
overflow-x: auto;overflow-y: auto;
left: 10px;
right: auto;
width: 527px;
top: 33px;
bottom: auto;
height: 291px;
cursor: inherit;
z-index: 3;
-webkit-overflow-scrolling: touch
}
#HMQiFEF0 {
position: absolute;
overflow: hidden;
left: 0px;
right: auto;
width: 214px;
top: 0px;
bottom: auto;
height: 31px;
cursor: default;
z-index: 1;
}
#F8QKRrPZ {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 70px;
top: 70px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 2;
}
#Zg9KFvsa {
position: absolute;
overflow: hidden;
left: 228px;
right: auto;
width: 222px;
top: 73px;
bottom: auto;
height: 33px;
cursor: default;
z-index: 5;
}
#qt87SgP4 {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 62px;
top: 115px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 6;
}
#Ck09F9Ev {
position: absolute;
overflow: hidden;
left: 228px;
right: auto;
width: 231px;
top: 118px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 7;
}
#X5Mm3l0P {
position: absolute;
overflow: hidden;
left: 212px;
right: auto;
width: 232px;
top: 149px;
bottom: auto;
height: 44px;
cursor: default;
z-index: 8;
}
#cmdcHHcx {
position: absolute;
overflow: visible;
left: 303px;
right: auto;
width: 72px;
top: 266px;
bottom: auto;
height: 22px;
cursor: inherit;
z-index: 4;
}
#cmdcHHcx_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#rq2f9kbR {
position: absolute;
overflow: visible;
left: 387px;
right: auto;
width: 72px;
top: 266px;
bottom: auto;
height: 22px;
cursor: inherit;
z-index: 3;
}
#rq2f9kbR_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#sh2ogtYu {
position: absolute;
overflow: hidden;
left: 212px;
right: auto;
width: 295px;
top: 205px;
bottom: auto;
height: 49px;
cursor: default;
z-index: 9;
}
#iuCIwq0v {
position: absolute;
overflow-x: auto;overflow-y: auto;
left: 10px;
right: auto;
width: 527px;
top: 33px;
bottom: auto;
height: 448px;
cursor: inherit;
z-index: 4;
-webkit-overflow-scrolling: touch
}
#uKNQnUWk { 
position: absolute;
overflow: hidden;
left: 238px;
right: auto;
width: 258px;
top: 372px;
bottom: auto;
height: 22px;
cursor: inherit;
z-index: 23;
padding: 10px 10px 10px 10px;
visibility: visible;
}
#AVOTS24L {
position: absolute;
overflow: hidden;
left: 0px;
right: auto;
width: 230px;
top: 0px;
bottom: auto;
height: 31px;
cursor: default;
z-index: 1;
}
#Xf8msLAV {
position: absolute;
overflow: hidden;
left: 228px;
right: auto;
width: 246px;
top: 72px;
bottom: auto;
height: 18px;
cursor: default;
z-index: 16;
}
#WqSqM3Uf {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 84px;
top: 102px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 3;
}
#yeSyMxju {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 102px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 2;
padding-right: 6px;
padding-bottom: 6px;
}
#yeSyMxju_inner {
width: 100%;
height: 100%;
}
#RMqaSLFg {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 84px;
top: 136px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 4;
}
#djhuMoOA {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 136px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 5;
padding-right: 6px;
padding-bottom: 6px;
}
#djhuMoOA_inner {
width: 100%;
height: 100%;
}
#sMuKng05 {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 84px;
top: 170px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 6;
}
#vEmLnKUf {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 170px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 8;
padding-right: 6px;
padding-bottom: 6px;
}
#vEmLnKUf_inner {
width: 100%;
height: 100%;
}
#WHYYGiOU {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 84px;
top: 204px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 7;
}
#Q8gAdr6x {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 204px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 12;
padding-right: 6px;
padding-bottom: 6px;
}
#Q8gAdr6x_inner {
width: 100%;
height: 100%;
}
#LvPqDJzv {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 84px;
top: 238px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 10;
}
#NpCdr2MS {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 238px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 13;
padding-right: 6px;
padding-bottom: 6px;
}
#NpCdr2MS_inner {
width: 100%;
height: 100%;
}
#Hl9YvDh2 {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 84px;
top: 272px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 11;
}
#KQXnrJF3 {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 272px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 14;
padding-right: 6px;
padding-bottom: 6px;
}
#KQXnrJF3_inner {
width: 100%;
height: 100%;
}
#G7N4x7xT {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 62px;
top: 306px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 17;
}
#zTmpHzcU {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 306px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 18;
padding-right: 6px;
padding-bottom: 6px;
}
#zTmpHzcU_inner {
width: 100%;
height: 100%;
}
#uKKyTnvn {
position: absolute;
overflow: hidden;
left: 228px;
right: auto;
width: 231px;
top: 330px;
bottom: auto;
height: 14px;
cursor: default;
z-index: 22;
}
#WMzN4D92 {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 84px;
top: 355px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 19;
}
#zCgaywTC {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 90px;
top: 355px;
bottom: auto;
height: 22px;
cursor: inherit;
font-size: 11px !important;
z-index: 20;
}
#zCgaywTC_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#XR3jQfh2 {
position: absolute;
overflow: visible;
left: 330px;
right: auto;
width: 90px;
top: 355px;
bottom: auto;
height: 22px;
cursor: inherit;
font-size: 11px !important;
z-index: 21;
}
#XR3jQfh2_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#o0BZIVhu {
position: absolute;
overflow: hidden;
left: 228px;
right: auto;
width: 17px;
top: 382px;
bottom: auto;
height: 28px;
cursor: default;
z-index: 24;
}
#o3t61uum {
position: absolute;
overflow: visible;
left: 303px;
right: auto;
width: 72px;
top: 422px;
bottom: auto;
height: 22px;
cursor: inherit;
z-index: 15;
}
#o3t61uum_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#KkqrQcWp {
position: absolute;
overflow: visible;
left: 387px;
right: auto;
width: 72px;
top: 422px;
bottom: auto;
height: 22px;
cursor: inherit;
z-index: 9;
}
#KkqrQcWp_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#k3X6MSZS {
position: absolute;
overflow-x: auto;overflow-y: auto;
left: 10px;
right: auto;
width: 527px;
top: 33px;
bottom: auto;
height: 291px;
cursor: inherit;
z-index: 5;
-webkit-overflow-scrolling: touch
}
#ZZrgPBI3 {
position: absolute;
overflow: hidden;
left: 0px;
right: auto;
width: 285px;
top: 0px;
bottom: auto;
height: 31px;
cursor: default;
z-index: 1;
}
#idwtkFZc {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 305px;
top: 70px;
bottom: auto;
height: 114px;
cursor: default;
z-index: 2;
}
#M4lCjl8n {
position: absolute;
overflow: visible;
left: 365px;
right: auto;
width: 72px;
top: 249px;
bottom: auto;
height: 22px;
cursor: inherit;
z-index: 3;
}
#M4lCjl8n_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#wuheQyo6 {
position: absolute;
overflow-x: auto;overflow-y: auto;
left: 10px;
right: auto;
width: 527px;
top: 33px;
bottom: auto;
height: 291px;
cursor: inherit;
z-index: 6;
-webkit-overflow-scrolling: touch
}
#WWdjYpXQ { 
position: absolute;
overflow: hidden;
left: 29px;
right: 496px;
width: auto;
top: 14px;
bottom: 276px;
height: auto;
cursor: inherit;
z-index: 1;
padding: 10px 10px 10px 10px;
visibility: visible;
}
#R3qJUpeh {
position: absolute;
overflow: hidden;
left: 0px;
right: auto;
width: 285px;
top: 0px;
bottom: auto;
height: 31px;
cursor: default;
z-index: 2;
}
#SV6zIUS6 {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 305px;
top: 70px;
bottom: auto;
height: 118px;
cursor: default;
z-index: 3;
}
#sQY2J8nk {
position: absolute;
overflow: hidden;
left: 199px;
right: auto;
width: 130px;
top: 219px;
bottom: auto;
height: 20px;
cursor: pointer;
z-index: 4;
}
#f25e2i5M {
position: absolute;
overflow-x: auto;overflow-y: auto;
left: 10px;
right: auto;
width: 584px;
top: 32px;
bottom: auto;
height: 398px;
cursor: inherit;
z-index: 7;
-webkit-overflow-scrolling: touch
}
#xwMm6lqx { 
position: absolute;
overflow: hidden;
left: 222px;
right: auto;
width: 299px;
top: 293px;
bottom: auto;
height: 43px;
cursor: inherit;
z-index: 12;
padding: 10px 10px 10px 10px;
visibility: visible;
}
#m95u3Nvr {
position: absolute;
overflow: hidden;
left: 0px;
right: auto;
width: 214px;
top: 0px;
bottom: auto;
height: 31px;
cursor: default;
z-index: 1;
}
#HujcLKX7 {
position: absolute;
overflow: hidden;
left: 81px;
right: auto;
width: 119px;
top: 70px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 2;
}
#d615Y62E {
position: absolute;
overflow: visible;
left: 212px;
right: auto;
width: 267px;
top: 70px;
bottom: auto;
height: 22px;
cursor: inherit;
font-size: 11px !important;
z-index: 7;
}
#d615Y62E_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#Xt5heqG4 {
position: absolute;
overflow: hidden;
left: 42px;
right: auto;
width: 158px;
top: 187px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 5;
}
#hidyr5qI {
position: absolute;
overflow: visible;
left: 211px;
right: auto;
width: 267px;
top: 187px;
bottom: auto;
height: 22px;
cursor: inherit;
font-size: 11px !important;
z-index: 8;
}
#hidyr5qI_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#kHQSu4RP {
position: absolute;
overflow: hidden;
left: 113px;
right: auto;
width: 414px;
top: 104px;
bottom: auto;
height: 71px;
cursor: default;
z-index: 6;
}
#LPGCqngL {
position: absolute;
overflow: hidden;
left: 138px;
right: auto;
width: 62px;
top: 221px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 10;
}
#C1X59pWQ {
position: absolute;
overflow: visible;
left: 212px;
right: auto;
width: 260px;
top: 221px;
bottom: auto;
height: 66px;
cursor: inherit;
z-index: 9;
padding-right: 6px;
padding-bottom: 6px;
}
#C1X59pWQ_inner {
width: 100%;
height: 100%;
resize: none;
}
#Q7bzRM3B {
position: absolute;
overflow: visible;
left: 303px;
right: auto;
width: 72px;
top: 348px;
bottom: auto;
height: 22px;
cursor: inherit;
z-index: 4;
}
#Q7bzRM3B_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#Hl1K4ZHm {
position: absolute;
overflow: visible;
left: 387px;
right: auto;
width: 72px;
top: 348px;
bottom: auto;
height: 22px;
cursor: inherit;
z-index: 3;
}
#Hl1K4ZHm_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#WKhJ0ekV {
position: absolute;
overflow: hidden;
left: 212px;
right: auto;
width: 17px;
top: 303px;
bottom: auto;
height: 28px;
cursor: default;
z-index: 11;
}</style>
<div id="q9P9UxJo" style="visibility: visible; display: block;">
<div id="HMQiFEF0" class="stSectionHeader" style="opacity: 1 ! important; visibility: visible;">
<div style="position: absolute; left: 0px; right: 0px; top: 50%; overflow: hidden; margin-top: -14px; height: 28px; max-height: 28px;" class="">
<p style="margin: 0px;" class="">
Price Quote
</p>
</div>
</div>
<div id="F8QKRrPZ" class="stFormFieldLabel" style="opacity: 1 ! important; visibility: visible;">
<div style="position: absolute; left: 0px; right: 0px; top: 50%; overflow: hidden; margin-top: -8px; height: 16px; max-height: 16px;" class="">
<p style="margin: 0px;" class="">
Address
</p>
</div>
</div>
<div id="Zg9KFvsa" style="opacity: 1 ! important; visibility: visible;">
<div style="position: absolute; left: 0px; right: 0px; top: 0px; overflow: hidden; bottom: 0px;" class=""><p style="margin: 0px;"><?php echo $_SESSION['address'];?><br><?php echo $_SESSION['citystatezip'];?></p></div>
</div>
<div id="qt87SgP4" class="stFormFieldLabel" style="opacity: 1 ! important; visibility: visible;">
<div style="position: absolute; left: 0px; right: 0px; top: 50%; overflow: hidden; margin-top: -8px; height: 16px; max-height: 16px;" class="">
<p style="margin: 0px;" class="">
Lot Size
</p>
</div>
</div>
<div id="Ck09F9Ev" style="opacity: 1 ! important; visibility: visible;">
<div style="position: absolute; left: 0px; right: 0px; top: 50%; overflow: hidden; margin-top: -8px; height: 16px; max-height: 16px;" class=""><p style="margin: 0px;"><?php echo $_SESSION['lotsize'];?> sq. ft.</p></div>
</div>
<div id="X5Mm3l0P" style="visibility: visible;">
<?php 
if($price1!=0)
{
?>
<table style="height:100%" width="232" cellspacing="0" cellpadding="0" border="0">
<tbody>
<?php

$price1tax=($price1*8.25)/100;
$finalprice=$price1+$price1tax;
$price2tax=($price2*8.25)/100;
$finalprice2=$price2+$price2tax;
?>
<tr>
<td width="20"><input name="radioprice" id="radioprice" value="<?php echo $price1;?>" checked="checked" type="radio"><input id="X5Mm3l0P_enabled_0_0" value="true" type="hidden"></td>
<td id="X5Mm3l0P_caption_0_0" onclick="Xojo.controls['X5Mm3l0P'].setValue('0,0');" class="" style="opacity: 1;">$<?php echo $price1;?>.00 Weekly (+$<?php echo number_format($price1tax,2);?> tax)</td>
</tr>
<tr>
<td width="20"><input name="radioprice" id="radioprice1" value="<?php echo $price2;?>" type="radio"><input id="X5Mm3l0P_enabled_1_0" value="true" type="hidden"></td>
<td id="X5Mm3l0P_caption_1_0" onclick="Xojo.controls['X5Mm3l0P'].setValue('1,0');" class="" style="opacity: 1;">$<?php echo $price2;?>.00 Weekly (+$<?php echo number_format($price2tax,2);?> tax)</td>
</tr>
</tbody></table>
<?php
}
else
{
?>
<span style="color:#F00;">Invalid address Go Back</span>
<?php
}
?>
</div>
<div id="cmdcHHcx" style="visibility: visible;">
<input id="cmdcHHcx_inner" name="bBack" value="Back" type="button" onclick="navigation('getaquote')">
</div>
<div id="rq2f9kbR" style="visibility: visible;">
<?php 

 
if($price1!=0)
{
if(isset($_SESSION['id']) || isset($_SESSION['username']))
{
?>
<input id="rq2f9kbR_inner" name="bNext" value="Next" type="button" onclick="price_func('priceupdate')">
<?php
}
else
{
?>
<input id="rq2f9kbR_inner" name="bNext" value="Sign Up" type="button" onclick="navigation('signup')">
<?php
}
}
?>
</div>
<div id="sh2ogtYu" class="stSmallLabel" style="opacity: 1 ! important; visibility: visible;">
<div style="display: none; position: absolute; left: 0px; right: 0px; top: 0px; overflow: hidden; bottom: 0px;" class="">
<p style="margin: 0px;" class="">
Includes mulch mow, edging, weed eating, and blowing grass off patios, 
porches, sidewalks and driveway, for front, back, sides, and alley.
</p>
</div>
</div>
</div>