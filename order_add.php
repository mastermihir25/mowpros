<?php
include('connection.php');


$type=mysql_real_escape_string($_REQUEST['action']);
if($type=='add')
{
	$address=mysql_real_escape_string($_REQUEST['address']);
	$city=mysql_real_escape_string($_REQUEST['city']);
	$state=mysql_real_escape_string($_REQUEST['state']);
	$zip=mysql_real_escape_string($_REQUEST['zip']);
	 
	$date=date("Y-m-d");
	$_SESSION['address']=$address;
	$_SESSION['city']=$city;
	$_SESSION['state']=$state;
	$_SESSION['zip']=$zip;
	$_SESSION['citystatezip']=$city.' '.$state.' '.$zip;
	//$lotsize=getlotsize();
	//$_SESSION['lotsize']=$lotsize;
	if(isset($_SESSION['username']))
	{
		$_SESSION['firstquote']=0;
	}
	else
	{
		$_SESSION['firstquote']=1;
	}
	echo "Inserted";
		
	/*if(isset($_SESSION['id']))
	{
		
	$row=mysql_fetch_array(mysql_query("select * from sv_user_profile where signup_id='".$_SESSION['id']."'"));
	//$name=$row['firstname'].' '.$row['lastname'];
	$id=$_SESSION['id'];
	$name=$row['user_name'];
	$phone_no=$row['phone_no'];
  if(mysql_query("insert into sv_user_order(uid,name,address,city,state,post_code,services,sub_services,date,phone_no)values('$id','$name','$address','$city','$state','$zip',1,1,'$date','$phone_no')"))
  { 
  	$oid=mysql_insert_id();
	$_SESSION['oid']=$oid; 	
	echo "Inserted";
  }
 else
 {
	echo "Server Error";
 }
 
	}
	else
	{
		echo "Signup First";
		//exit;
	}*/
	
	
	
		
	
	
}
else if($type=='priceupdate')
{
	$lotsize=$_SESSION['lotsize'];
	$price=mysql_real_escape_string($_REQUEST['price']);
	$_SESSION['price']=$price;
	$tax=($price*8.25)/100; 
	$_SESSION['tax']=$tax;
	//$oid=$_SESSION['oid'];
	if(isset($_SESSION['id']))
	{
	echo "Updated"; 
	}
	else
	{
		echo "Signup First"; 
	}
	 /* if(mysql_query("update sv_user_order set price=$price,lotsize=$lotsize,tax=$tax where order_id='$oid'")) 
	  {	
		echo "Updated";
	  }
	 else
	 {
		echo "Server Error";
	 }*/
}
/*else if($type=='update')
{
	$name=mysql_real_escape_string($_REQUEST['name']);
	$email_id=mysql_real_escape_string($_REQUEST['email_id']);
	$phone_no=mysql_real_escape_string($_REQUEST['phone_no']);
	$city=mysql_real_escape_string($_REQUEST['city']);
	$address=mysql_real_escape_string($_REQUEST['address']);
	$pin_code=mysql_real_escape_string($_REQUEST['pin_code']);
	$gender=mysql_real_escape_string($_REQUEST['gender']);

	$hid=mysql_real_escape_string($_REQUEST['hid']);
	if(mysql_query("update sv_user_profile set name='$name',email_id='$email_id',phone_no='$phone_no',city='$city',address='$address',pin_code='$pin_code',gender='$gender' where signup_id='$hid'")) 
		echo "Updated";
	else 
		echo "Error";
  
}
else if($type=='delete')
{
	$hid=mysql_real_escape_string($_REQUEST["hid"]);		
	if(mysql_query("delete from sv_user_profile where signup_id='$hid'")) 
		echo "Deleted";
	else 
		echo "Error";
}  */

?>