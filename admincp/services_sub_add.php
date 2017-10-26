<?php
include('../connection.php');
$type=mysql_real_escape_string($_REQUEST['action']);
if($type=='add')
{
	$sname=mysql_real_escape_string($_REQUEST['sname']);
	$sub_sname=mysql_real_escape_string($_REQUEST['sub_sname']);
	$price=mysql_real_escape_string($_REQUEST['price']);
	if(mysql_query("insert into sv_services_sub(services_name,services_sub_name,price)values('$sname','$sub_sname','$price')"))
	echo "Inserted";
 else
	echo "Server Error";
}
else if($type=='update')
{
	$sname=mysql_real_escape_string($_REQUEST['sname']);
	$sub_sname=mysql_real_escape_string($_REQUEST['sub_sname']);
	$price=mysql_real_escape_string($_REQUEST['price']);	
	$hid=mysql_real_escape_string($_REQUEST['hid']);
	if(mysql_query("update sv_services_sub set services_name='$sname',services_sub_name='$sub_sname',price='$price' where sid='$hid'")) 
		echo "Updated";
	else 
		echo "Error";				

}
else if($type=='delete')
{
	$hid=mysql_real_escape_string($_REQUEST["hid"]);		
	if(mysql_query("delete from sv_services_sub where sid='$hid'")) 
		echo "Deleted";
	else 
		echo "Error";
}  

?>