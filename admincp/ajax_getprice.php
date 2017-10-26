<?php include("../connection.php"); 
//$qry="select price from sv_services_sub where sid=".$_POST['id'];
$qry="select sb.sid,s.services_id,s.services_name,sb.services_sub_name,sb.price from sv_services_sub as sb left join sv_services as s on sb.services_name=s.services_id where sb.sid=".$_POST['id']; 
$res=mysql_query($qry);
$row=mysql_fetch_array($res);
echo $row['services_name']."(".$row['services_sub_name'].")#";
echo $row['price'];

?>