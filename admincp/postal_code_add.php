<?php
include('../connection.php');
$type=mysql_real_escape_string($_REQUEST['action']);
if($type=='add')
{
	$pcode=mysql_real_escape_string($_REQUEST['pcode']);
  if(mysql_query("insert into sv_postal_code(postal_code)values('$pcode')"))
	echo "Inserted";
 else
	echo "Server Error";
}
else if($type=='update')
{
	$pcode=mysql_real_escape_string($_REQUEST['pcode']);
	$hid=mysql_real_escape_string($_REQUEST['hid']);
	if(mysql_query("update sv_postal_code set postal_code='$pcode' where postal_id='$hid'")) 
		echo "Updated";
	else 
		echo "Error";				

}
else if($type=='delete')
{
	$hid=mysql_real_escape_string($_REQUEST["hid"]);		
	if(mysql_query("delete from sv_postal_code where postal_id='$hid'")) 
		echo "Deleted";
	else 
		echo "Error";
}  

?>