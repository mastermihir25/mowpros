<?php
include('../connection.php');
$type=$_REQUEST['action'];
if($type=='add')
{
	$sname=mysql_real_escape_string($_REQUEST['sname']);
  if(mysql_query("insert into sv_services(services_name)values('$sname')"))
	echo "Inserted";
 else
	echo "Server Error";
}
else if($type=='update')
{
	$sname=mysql_real_escape_string($_REQUEST['sname']);
	$hid=mysql_real_escape_string($_REQUEST['hid']);
	if(mysql_query("update sv_services set services_name='$sname' where services_id='$hid'")) 
		echo "Updated";
	else 
		echo "Error";				

}
else if($type=='delete')
{
	$hid=mysql_real_escape_string($_REQUEST["hid"]);		
	if(mysql_query("delete from sv_services where services_id='$hid'")) 
		echo "Deleted";
	else 
		echo "Error";
}  

?>