<?php
include('../connection.php');
$type=$_REQUEST['action'];
if($type=='add')
{
	$gname=mysql_real_escape_string($_REQUEST['gname']);
	$teamleader=mysql_real_escape_string($_REQUEST['teamleader']);
	$eid=mysql_real_escape_string($_REQUEST['eid']);
  if(mysql_query("insert into sv_groups(gname,teamleader,eid)values('$gname','$teamleader','$eid')"))
	echo "Inserted";
 else
	echo "Server Error";
}
else if($type=='update')
{
	$gname=mysql_real_escape_string($_REQUEST['gname']);
	$teamleader=mysql_real_escape_string($_REQUEST['teamleader']);
	$eid=mysql_real_escape_string($_REQUEST['eid']);
	$hid=mysql_real_escape_string($_REQUEST['hid']);
	if(mysql_query("update sv_groups set gname='$gname',teamleader='$teamleader',eid='$eid' where gid='$hid'")) 
		echo "Updated";
	else 
		echo "Error";				

}
else if($type=='delete')
{
	$hid=mysql_real_escape_string($_REQUEST["hid"]);		
	if(mysql_query("delete from sv_groups where gid='$hid'")) 
		echo "Deleted";
	else 
		echo "Error";
}  

?>