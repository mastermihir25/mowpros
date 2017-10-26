<?php
include('../connection.php');

$type=mysql_real_escape_string($_REQUEST['action']);
$hid=mysql_real_escape_string($_REQUEST['hid']);

if($type=='add')
{
$email=mysql_real_escape_string($_REQUEST['email']);
$cemail=mysql_real_escape_string($_REQUEST['cemail']);
$fname=mysql_real_escape_string($_REQUEST['fname']);
$lname=mysql_real_escape_string($_REQUEST['lname']);
$mob_no=mysql_real_escape_string($_REQUEST['mob_no']);
$post_code=mysql_real_escape_string($_REQUEST['post_code']);
$exp=mysql_real_escape_string($_REQUEST['exp']);
$paid_work=mysql_real_escape_string($_REQUEST['paid_work']);
$gender=mysql_real_escape_string($_REQUEST['gender']);
$dob=mysql_real_escape_string($_REQUEST['dob']);
$dob1 = mysql_real_escape_string(date("Y-m-d", strtotime($dob)));
$nation=mysql_real_escape_string($_REQUEST['nation']);
$address=mysql_real_escape_string($_REQUEST['address']);
$suburb=mysql_real_escape_string($_REQUEST['suburb']);
$abt=mysql_real_escape_string($_REQUEST['abt']);
$update_time=mysql_real_escape_string(date("Y-m-d"));
	if(mysql_query("update sv_service_provider set email='$email',confirm_email='$cemail',first_name='$fname',last_name='$lname',mob_no='$mob_no',post_code='$post_code',exp='$exp',paid_work='$paid_work',gender='$gender',dob='$dob1',nationality='$nation',address='$address',suburb='$suburb',abt_us='$abt',update_time='$update_time' where id='$hid'")) 
		echo "Updated";
	else 
		echo "Error";
}
else if($type=='delete')
{
	if(mysql_query("delete from sv_service_provider where id='$hid'")) 
		echo "Deleted";
	else 
		echo "Error";
}  

 

?>