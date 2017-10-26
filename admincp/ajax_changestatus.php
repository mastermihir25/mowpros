<?php include("../connection.php"); 
//$qry="select price from sv_services_sub where sid=".$_POST['id'];
if($_POST['action']=="mainchange")
{
$qry="update sv_user_order set main_service=".$_POST['stval']." where order_id=".$_POST['oid']; 
$res=mysql_query($qry) or die("Error IN Query");
 $row= mysql_affected_rows();
echo 1;
}
if($_POST['action']=="subchange")
{
	$price=$_POST['price'];
	if($_POST['stval']=="De-active")
	{
		$subqry="serviceprice=serviceprice-$price";
	}
	else
	{
		$subqry="serviceprice=serviceprice+$price";
	}
	$qry="update sv_user_order set services='".$_POST['service']."',".$subqry." where order_id=".$_POST['oid']; 
	//echo $qry;
$res=mysql_query($qry) or die("Error IN Query");
 $row= mysql_affected_rows();
echo 1;
}
?>