<?php include('connection.php');
$_SESSION['firstquote']=0;
?>
<!DOCTYPE html>
<html><head>
<title>MowPros</title>
<meta http-equiv="x-ua-compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<link href="files/styles.css" rel="stylesheet" type="text/css" media="all">
<link rel="shortcut icon" type="image/x-icon" href="https://www.mowpros.com/framework/favicon.ico">
<link rel="apple-touch-icon" href="https://www.mowpros.com/framework/homescreen60.png">
<link rel="apple-touch-icon" sizes="76x76" href="https://www.mowpros.com/framework/homescreen76.png">
<link rel="apple-touch-icon" sizes="120x120" href="https://www.mowpros.com/framework/homescreen120.png">
<link rel="apple-touch-icon" sizes="152x152" href="https://www.mowpros.com/framework/homescreen152.png">
<style type="text/css" media="print">
.no-print{display: none;}
</style>

<script type="text/javascript">
function PrintThisDiv(id) {
var HTMLContent = document.getElementById(id);
var Popup = window.open('', '', 'width=500,height=700');
if (typeof Popup == "undefined") {
    alert('pu');
}
Popup.document.writeln('<html><head>');
Popup.document.writeln('<style type="text/css" media="print">');
Popup.document.writeln('.no-print{display: none;}');
Popup.document.writeln('</style>');
Popup.document.writeln('</head><body style="font-family: Helvetica, Arial, sans-serif; font-size:14px; color:#073;">');
Popup.document.writeln('<a href="javascript:;" onclick="window.print();" class="no-print">Print</a>');
Popup.document.writeln(HTMLContent.innerHTML);
Popup.document.writeln('</body></html>');
Popup.document.close();
Popup.focus();
}
<?php
$qry="select * from sv_postal_code";
$res=mysql_query($qry)or die("Error In Query");
$pc="";
$i=0;
while($row=mysql_fetch_array($res))
{
	$i++;
	
	if(mysql_num_rows($res)==$i)
	{
		$pc.="'".$row['postal_code']."'";
	}
	else
	{
		$pc.="'".$row['postal_code']."',";
	}
}

?>
function checkZipCodes() {var zipCodes = [<?php echo $pc;?>];var zip = document.getElementById("zip").value;var found = false;for (i = 0; i < zipCodes.length && !found; i++) {if (zipCodes[i] === zip) {found = true;}}if (found) {
document.getElementById("checkZipFound").style.display = "block";
document.getElementById("checkZipNotFound").style.display = "none";
} else {
document.getElementById("checkZipFound").style.display = "none";
document.getElementById("checkZipNotFound").style.display = "block";
}}</script>
<style type="text/css" media="all" id="wpHomePage_jsywxOAD">#jsywxOAD {
overflow: hidden;
position: absolute;
left: -100%;
top: 0%;
width: 100%;
height: 100%;
}
#TgXdRP6a {
position: absolute;
overflow: hidden;
left: 0px;
right: 0px;
width: auto;
top: 0px;
bottom: 0px;
height: auto;
cursor: inherit;
z-index: 1;
}
#sBen8ZRP {
position: absolute;
overflow: hidden;
left: 50%;
margin-left: -362px;
width: 724px;
top: 75px;
bottom: auto;
height: 895px;
cursor: inherit;
background-color: rgb(255,255,255) !important;
background-image: none !important;
z-index: 2;
}
#J1LtKRKJ { 
position: absolute;
overflow: hidden;
left: 50%;
margin-left: -307px;
width: 614px;
top: 302px;
bottom: auto;
height: 630px;
cursor: inherit;
z-index: 4;
padding: 10px 10px 10px 10px;
visibility: visible;
}
#NdBXlZqT {
position: absolute;
overflow: hidden;
left: 50%;
margin-left: -358px;
width: 717px;
top: 794px;
bottom: auto;
height: 32px;
cursor: inherit;
z-index: 8;
}
#FSFJq633 {
position: absolute;
overflow-x: auto;overflow-y: auto;
left: 50%;
margin-left: -362px;
width: 724px;
top: 258px;
bottom: auto;
height: 32px;
cursor: inherit;
z-index: 5;
-webkit-overflow-scrolling: touch
}
#nRW98XFl {
position: absolute;
overflow: hidden;
left: 16%;
margin-left: -100px;
width: 139px;
top: 0px;
bottom: auto;
height: 30px;
cursor: pointer;
z-index: 1;
}
#STuOp7Hh {
position: absolute;
overflow: hidden;
left: 42%;
margin-left: -136px;
width: 170px;
top: 0px;
bottom: auto;
height: 30px;
cursor: pointer;
z-index: 2;
}
#T4vjujAk {
position: absolute;
overflow: hidden;
left: 350px;
right: auto;
width: 133px;
top: 0px;
bottom: auto;
height: 30px;
cursor: pointer;
z-index: 3;
}
#OE9JZWPJ {
position: absolute;
overflow: hidden;
left: 88%;
margin-left: -135px;
width: 93px;
top: 0px;
bottom: auto;
height: 30px;
cursor: pointer;
z-index: 4;
}
#OE9JZWPJ2 {
position: absolute;
overflow: hidden;
left: 88%;
margin-left: -32px;
width: 93px;
top: 0px;
bottom: auto;
height: 30px;
cursor: pointer;
z-index: 4;
}
#RHttrfLW {
position: absolute;
overflow-x: auto;overflow-y: auto;
left: 50%;
margin-left: -387px;
width: 774px;
top: 75px;
bottom: auto;
height: 163px;
cursor: inherit;
z-index: 3;
-webkit-overflow-scrolling: touch
}
#HxSwMJgn { 
position: absolute;
overflow: hidden;
left: 74%;
margin-left: -150px;
width: 300px;
top: 14px;
bottom: auto;
height: 105px;
cursor: inherit;
z-index: 1;
padding: 10px 10px 10px 10px;
visibility: visible;
}
#eawF0aAK { 
position: absolute;
overflow: hidden;
left: 27%;
margin-left: -176px;
width: 353px;
top: 14px;
bottom: auto;
height: 122px;
cursor: inherit;
z-index: 2;
padding: 10px 10px 10px 10px;
visibility: visible;
}
#t11AqcSs {
position: absolute;
overflow-x: auto;overflow-y: auto;
left: 50%;
margin-left: -307px;
width: 614px;
top: 302px;
bottom: auto;
height: 490px;
cursor: inherit;
z-index: 6;
-webkit-overflow-scrolling: touch
}
#gQ9tKQre {
position: absolute;
overflow-x: auto;overflow-y: auto;
left: 10px;
right: auto;
width: 527px;
top: 33px;
bottom: auto;
height: 291px;
cursor: inherit;
z-index: 1;
-webkit-overflow-scrolling: touch
}
#LgYlXwDQ {
position: absolute;
overflow: hidden;
left: 0px;
right: auto;
width: 214px;
top: 0px;
bottom: auto;
height: 31px;
cursor: default;
z-index: 2;
}
#OWjPFF6H {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 305px;
top: 75px;
bottom: auto;
height: 53px;
cursor: default;
z-index: 1;
}
#VVMdtwfx {
position: absolute;
overflow-x: auto;overflow-y: auto;
left: 10px;
right: auto;
width: 527px;
top: 33px;
bottom: auto;
height: 291px;
cursor: inherit;
z-index: 2;
-webkit-overflow-scrolling: touch
}
#AllEfVdh {
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
#R6V7UhKr {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 62px;
top: 70px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 3;
}
#d0aPkQMk {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 70px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 2;
padding-right: 6px;
padding-bottom: 6px;
}
#d0aPkQMk_inner {
width: 100%;
height: 100%;
}
#d0DG0msP {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 62px;
top: 104px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 4;
}
#jwHch36d {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 104px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 5;
padding-right: 6px;
padding-bottom: 6px;
}
#jwHch36d_inner {
width: 100%;
height: 100%;
}
#V1MKuCcu {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 62px;
top: 138px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 6;
}
#upp5gWzK {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 138px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 8;
padding-right: 6px;
padding-bottom: 6px;
}
#upp5gWzK_inner {
width: 100%;
height: 100%;
}
#IE8EvI0F {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 62px;
top: 172px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 7;
}
#JcKMUNJN {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 172px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 9;
padding-right: 6px;
padding-bottom: 6px;
}
#JcKMUNJN_inner {
width: 100%;
height: 100%;
}
#CybL6yBW {
position: absolute;
overflow: visible;
left: 387px;
right: auto;
width: 72px;
top: 249px;
bottom: auto;
height: 22px;
cursor: inherit;
z-index: 10;
}
#CybL6yBW_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#izPKQD3D {
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
#gNLgB9Zv {
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
#bdNqaNn0 {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 62px;
top: 70px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 2;
}
#uWYzwUm8 {
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
#hJoZGa3x {
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
#AiU0rrgB {
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
#yGkABesi {
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
#Xf0Q8pwY {
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
#Xf0Q8pwY_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#pY9A15ox {
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
#pY9A15ox_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#Vox50Fxn {
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
#kVIluDH8 {
position: absolute;
overflow-x: auto;overflow-y: auto;
left: 10px;
right: auto;
width: 527px;
top: 33px;
bottom: auto;
height: 291px;
cursor: inherit;
z-index: 4;
-webkit-overflow-scrolling: touch
}
#Wb1IHWj8 {
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
#nfeEvrF2 {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 320px;
top: 39px;
bottom: auto;
height: 59px;
cursor: default;
z-index: 9;
}
#PbzulQdq {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 62px;
top: 104px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 3;
}
#yoW7sVtr {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 104px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 2;
padding-right: 6px;
padding-bottom: 6px;
}
#yoW7sVtr_inner {
width: 100%;
height: 100%;
}
#logPm9ih {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 62px;
top: 138px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 4;
}
#tCHbxOkd {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 138px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 5;
padding-right: 6px;
padding-bottom: 6px;
}
#tCHbxOkd_inner {
width: 100%;
height: 100%;
}
#S2sNYEIZ {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 62px;
top: 172px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 6;
}
#pHtrKvP7 {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 172px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 7;
padding-right: 6px;
padding-bottom: 6px;
}
#pHtrKvP7_inner {
width: 100%;
height: 100%;
}
#izFkwvEL {
position: absolute;
overflow: visible;
left: 303px;
right: auto;
width: 72px;
top: 249px;
bottom: auto;
height: 22px;
cursor: inherit;
z-index: 10;
}
#izFkwvEL_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#ZC9bydos {
position: absolute;
overflow: visible;
left: 387px;
right: auto;
width: 72px;
top: 249px;
bottom: auto;
height: 22px;
cursor: inherit;
z-index: 8;
}
#ZC9bydos_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#hbt6VpXM {
position: absolute;
overflow-x: auto;overflow-y: auto;
left: 50%;
margin-left: -307px;
width: 614px;
top: 302px;
bottom: auto;
height: 490px;
cursor: inherit;
z-index: 7;
-webkit-overflow-scrolling: touch
}
#WCWeOaPM {
position: absolute;
overflow-x: auto;overflow-y: auto;
left: 10px;
right: auto;
width: 527px;
top: 33px;
bottom: auto;
height: 291px;
cursor: inherit;
z-index: 1;
-webkit-overflow-scrolling: touch
}
#PmS2pLgS {
position: absolute;
overflow: hidden;
left: 0px;
right: auto;
width: 256px;
top: 0px;
bottom: auto;
height: 31px;
cursor: default;
z-index: 1;
}
#KkklKO2k {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 84px;
top: 70px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 3;
}
#qvPMxNBd {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 70px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 2;
padding-right: 6px;
padding-bottom: 6px;
}
#qvPMxNBd_inner {
width: 100%;
height: 100%;
}
#ZBos67Mp {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 84px;
top: 104px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 4;
}
#co7ZfRXG {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 104px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 5;
padding-right: 6px;
padding-bottom: 6px;
}
#co7ZfRXG_inner {
width: 100%;
height: 100%;
}
#lyIUu81c {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 100px;
top: 138px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 6;
}
#sRw9w9BE {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 138px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 8;
padding-right: 6px;
padding-bottom: 6px;
}
#sRw9w9BE_inner {
width: 100%;
height: 100%;
}
#z6kCzw9o {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 62px;
top: 172px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 7;
}
#oGDtyg4A {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 172px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 9;
padding-right: 6px;
padding-bottom: 6px;
}
#oGDtyg4A_inner {
width: 100%;
height: 100%;
}
#IdMwRPHJ {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 91px;
top: 206px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 11;
}
#WM98uuVq {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 231px;
top: 206px;
bottom: auto;
height: 22px;
cursor: inherit;
font-size: 11px !important;
z-index: 12;
}
#WM98uuVq_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#NR1uTgfu {
position: absolute;
overflow: visible;
left: 387px;
right: auto;
width: 72px;
top: 249px;
bottom: auto;
height: 22px;
cursor: inherit;
z-index: 10;
}
#NR1uTgfu_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#FiL4tAuU {
position: absolute;
overflow-x: auto;overflow-y: auto;
left: 10px;
right: auto;
width: 527px;
top: 33px;
bottom: auto;
height: 377px;
cursor: inherit;
z-index: 2;
-webkit-overflow-scrolling: touch
}
#vCeBiYv5 {
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
#SDCAVg2j {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 70px;
top: 70px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 3;
}
#VLDXHOUe {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 70px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 2;
padding-right: 6px;
padding-bottom: 6px;
}
#VLDXHOUe_inner {
width: 100%;
height: 100%;
}
#KZBwPgAl {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 62px;
top: 104px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 4;
}
#FdiKSG2X {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 104px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 5;
padding-right: 6px;
padding-bottom: 6px;
}
#FdiKSG2X_inner {
width: 100%;
height: 100%;
}
#HhJaW2V4 {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 62px;
top: 138px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 6;
}
#QA4Rp3NS {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 138px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 8;
padding-right: 6px;
padding-bottom: 6px;
}
#QA4Rp3NS_inner {
width: 100%;
height: 100%;
}
#vpQcSYpA {
position: absolute;
overflow: hidden;
left: 132px;
right: auto;
width: 62px;
top: 172px;
bottom: auto;
height: 22px;
cursor: default;
z-index: 7;
}
#bfoYGd6U {
position: absolute;
overflow: visible;
left: 228px;
right: auto;
width: 225px;
top: 172px;
bottom: auto;
height: 16px;
cursor: inherit;
z-index: 9;
padding-right: 6px;
padding-bottom: 6px;
}
#bfoYGd6U_inner {
width: 100%;
height: 100%;
}
#uGgg2VGB {
position: absolute;
overflow: visible;
left: 303px;
right: auto;
width: 72px;
top: 254px;
bottom: auto;
height: 22px;
cursor: inherit;
z-index: 11;
}
#uGgg2VGB_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#nQKsHBeW {
position: absolute;
overflow: visible;
left: 387px;
right: auto;
width: 72px;
top: 254px;
bottom: auto;
height: 22px;
cursor: inherit;
z-index: 10;
}
#nQKsHBeW_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#kBhk2Q5w {
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
#uESHeheY {
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
#kSY5YJgH {
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
#PEfy5wBo {
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
#w2akcTPc {
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
#w4NkpwAp {
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
#xk95TtA7 {
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
#eaOZeB2h {
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
#eaOZeB2h_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#DTSOxQhf {
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
#DTSOxQhf_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#ShDo47Bj {
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
#ptuGUSva {
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
#fhVKPg8g { 
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
#IyhVj07c {
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
#UaETNKbl {
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
#zXiPzhCD {
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
#DQBcRYW3 {
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
#DQBcRYW3_inner {
width: 100%;
height: 100%;
}
#C7f1a0X0 {
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
#MUp8Lmoz {
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
#MUp8Lmoz_inner {
width: 100%;
height: 100%;
}
#URZE4cF8 {
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
#CoQSAob4 {
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
#CoQSAob4_inner {
width: 100%;
height: 100%;
}
#yAFwxnb1 {
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
#FvLJlxLb {
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
#FvLJlxLb_inner {
width: 100%;
height: 100%;
}
#VMC714zx {
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
#WjZI8noj {
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
#WjZI8noj_inner {
width: 100%;
height: 100%;
}
#FcI4rFHy {
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
#aw7GSfRS {
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
#aw7GSfRS_inner {
width: 100%;
height: 100%;
}
#HqSQIC1o {
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
#itjRQ3hq {
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
#itjRQ3hq_inner {
width: 100%;
height: 100%;
}
#T5DyPlSh {
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
#gDB8QVDl {
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
#RgZFdFwL {
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
#RgZFdFwL_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#Nfo5LEM2 {
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
#Nfo5LEM2_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#NQkIozip {
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
#t1sq6Slx {
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
#t1sq6Slx_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#ltjOSnKl {
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
#ltjOSnKl_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#YcrmYt5Y {
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
#ErB9nNVa {
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
#upIYVDPc {
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
#nKTygt4g {
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
#nKTygt4g_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#dgxKzSOD {
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
#dvWV8Lwx { 
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
#ObJxjNsv {
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
#Fm6uAUOU {
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
#Q5t3sbQk {
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
#lJvgfReC {
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
#lpYmv9rN { 
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
#mBxSi3Hs {
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
#gcvpIKqB {
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
#IoJQWDIa {
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
#IoJQWDIa_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#lDrE0ZhU {
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
#ZBZHQJhA {
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
#ZBZHQJhA_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#CK6mUuLd {
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
#L6Gn9Dbc {
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
#nTbGwlsd {
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
#nTbGwlsd_inner {
width: 100%;
height: 100%;
resize: none;
}
#cEu2IipQ {
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
#cEu2IipQ_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#lYV7RcOR {
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
#lYV7RcOR_inner {
width: 100%;
height: 100%;
font-size: 11px !important;
}
#vw3hhLMP {
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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="validation.js"></script>
</head>
<body style="margin: 0px;" lang="en">

<script language="javascript">
//navigation('firstservice');
</script>
<div id="XojoSession"></div>
<div id="XojoContainer" style="min-width: 800px; min-height: 830px;">
<div id="XojoPages" style="min-width: 800px; min-height: 830px;">

<div id="jsywxOAD" class="stDefaults" style="min-width: 800px; min-height: 830px; left: 0px; visibility: visible;">
<div id="TgXdRP6a" style="opacity: 1; visibility: visible; background-image: url('files/picture.jpg'); background-size: 100% 100%; background-repeat: no-repeat;" class=""><img src="files/spacer.gif" alt="" style="position: absolute;" id="TgXdRP6a_image" class="" border="0"></div>
<div id="sBen8ZRP" class="stContentBox" style="opacity: 0.85 ! important; visibility: visible;" >
<style>
.modal {
    display: none; /* Hidden by default */
   	position: fixed; /* Stay in place */
    z-index: 9999999; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.7); /* Black w/ opacity */
}
</style>
 
<div id="WaitDialog"   class="modal" style="text-align: center">
    <img  src="files/loader.gif" />
    <div style="margin-top: 10px; color: white">
        <b>Please wait</b>
    </div>
</div>
 
<?php /*?><input type="button" id="submit" name="submit" value="Submit" />
<script language="javascript">
$("#submit").click(function() {
	$( "#WaitDialog" ).show();
});
 
 
</script><?php */?>
</div>
<div id="J1LtKRKJ" style="opacity: 1; cursor: auto;">
<!--start content-->
<div>

<div style="color:#606; font-size:24px;"><p>Welcome to <b><i><span style="color:#073;">Mow</span>Pros</i></b></p></div><p><b><i>Mow<span style="color:#606;">Pros</span></i></b> offers dependable, quality weekly or bi-weekly lawn service at an affordable price.</p><p>Our
 mission is to provide reliable lawn care service to our customers with 
the highest degree of friendliness, honesty, and integrity.</p><p>Regular service includes mowing, edging, line trimming, and blowing area clean.</p><ul style="color:#606"><li>Easy <a onclick="navigation('signup')" style="color:#606; cursor:pointer; text-decoration: underline;" >Sign-up</a> (Phone or Web)</li><li>Friendly Staff</li><li>Quality Service</li><li>Reliable Mow Day</li><li><a onclick="navigation('getaquote')" style="color:#606;  cursor:pointer; text-decoration: underline;" >Competitive Pricing</a></li><li>Customer Satisfaction</li><li>Convenient Credit or Debit Card Billing (MC or Visa)</li></ul></div>
<!--end content-->
</div>
<div id="NdBXlZqT" style="opacity: 1 ! important; visibility: visible;" class=""><img src="files/siteseal.png" alt="" style="position: absolute; width: 131px; height: 32px; bottom: 0px; margin-top: 0px; right: 0px; margin-left: 0px;" id="NdBXlZqT_image" class="" border="0"></div>
<!--start menu-->
<div id="FSFJq633" class="stMenuSeparators" style="visibility: visible;">
<div id="nRW98XFl" class="stMenuLabel" style="opacity: 1 ! important; visibility: visible;">
<div style="position: absolute; left: 0px; right: 0px; top: 50%; overflow: hidden; margin-top: -12px; height: 25px; max-height: 25px;" class="">
<a  style="margin: 0px; color:rgb(0, 119, 51) !important; text-decoration:none;" class="" onClick="navigation('servicearea')">
Service Area
</a>
</div>
</div>
<div id="STuOp7Hh" class="stMenuLabel" style="opacity: 1 ! important; visibility: visible;">
<div style="position: absolute; left: 0px; right: 0px; top: 50%; overflow: hidden; margin-top: -12px; height: 25px; max-height: 25px;" class="">
<a  style="margin: 0px; color:rgb(0, 119, 51) !important; text-decoration:none;" class="" onClick="navigation('whatexpect')">
What to Expect
</a>
</div>
</div>
<div id="T4vjujAk" class="stMenuLabel" style="opacity: 1 ! important; visibility: visible;">
<div style="position: absolute; left: 0px; right: 0px; top: 50%; overflow: hidden; margin-top: -12px; height: 25px; max-height: 25px;" class="">
<a style="margin: 0px; color:rgb(0, 119, 51) !important; text-decoration:none;" class="" onClick="navigation('getaquote')">
Get a Quote
</a>
</div>
</div>
<div id="OE9JZWPJ" class="stMenuLabel" style="opacity: 1 ! important; visibility: visible;">
<div style="position: absolute; left: 0px; right: 0px; top: 50%; overflow: hidden; margin-top: -12px; height: 25px; max-height: 25px;" class="">
<a style="margin: 0px; color:rgb(0, 119, 51) !important; text-decoration:none;" class="" onClick="navigation('signup')">
Sign Up
</a>
</div>
</div>
<div id="OE9JZWPJ2" class="stMenuLabel" style="opacity: 1 ! important; visibility: visible;">
<div style="position: absolute; left: 0px; right: 0px; top: 50%; overflow: hidden; margin-top: -12px; height: 25px; max-height: 25px;" class="">
<a href="admincp/" target="_blank" style="margin: 0px; color:rgb(0, 119, 51) !important; text-decoration:none;" class="" >
Sign In
</a>
</div>
</div>
</div>
<!--end menu-->
<!--start header-->
<div id="RHttrfLW" style="visibility: visible;">
<!--start header right-->
<div id="HxSwMJgn" style="opacity: 1; cursor: auto;">
<p style="font-family: Helvetica, Arial, sans-serif; margin-left:0; font-size:20px; line-height:22px;color:#606; text-align:right;"><b>Call: <i>(214) 669-7767</i></b> <br>(214) Mow-Pros</p>
<p style="font-family: Helvetica, Arial, sans-serif; margin-top:-10px; font-size:16px; line-height:16px;color:#606; text-align:right;"><b>Email: <i><a onclick="Xojo.triggerServerEvent('HxSwMJgn','Link',[this.href]);return false;" href="mailto:service@mowpros.com" style="color:#606;">service@mowpros.com</a></i></b></p>
</div>
<!--end header right-->
<!--start header left-->
<div id="eawF0aAK" style="opacity: 1; cursor: auto;"><p style="font-family: Helvetica, Arial, sans-serif; font-weight: bold; font-size: 60px; font-style: italic; margin:0px; padding:0px;"><a onclick="document.location.reload(0);"  style="text-decoration:none; cursor:pointer;"><span style="color:#084;">Mow</span><span style="color:#606;">Pros</span></a></p><p style="font-family: Helvetica, Arial, sans-serif; font-weight: bold; font-size: 20px; font-style: italic; margin:0px; padding:0px;"><span style="color:#084;">Need a Mow? </span><span style="color:#606;">Call the Pros!</span></p></div>
<!--end header left-->
</div>
<!--end header-->

</div>
</div>
<div id="XojoDialogs">
</div>
<div id="XojoMenus" onmousedown="Xojo.menus.dismissAllMenus(0)">
</div>
</div>

<noscript>
<div id="nojs_mask"></div>
<div id="nojs_warning"><p><b>Javascript must be enabled to access this page.</b></p><p class="explaintext">To turn Javascript on, please refer to your browser settings window.</p></div>
</noscript>

<script type="text/javascript" src="files/framework.js"></script>

</body></html>