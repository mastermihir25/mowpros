<?php
include('../connection.php');
$type=mysql_real_escape_string($_REQUEST['action']);
if($type=='getval')
{
	$str="";
	$id=mysql_real_escape_string($_REQUEST['id']);
	$res=mysql_query("select * from sv_user_profile where signup_id='$id'");
	if(mysql_num_rows($res)>0)
	{
		while($row=mysql_fetch_array($res))
		{
			$str.=$row['user_name']; 
		}
	}
	echo $str;
}
else if($type=='add')
{
	$name=mysql_real_escape_string($_REQUEST['name']);
	$password=mysql_real_escape_string(md5($_REQUEST['password']));
	$firstname=mysql_real_escape_string($_REQUEST['firstname']);
	$lastname=mysql_real_escape_string($_REQUEST['lastname']);
	$email_id=mysql_real_escape_string($_REQUEST['email_id']);
	$phone_no=mysql_real_escape_string($_REQUEST['phone_no']);
	$ref_by=mysql_real_escape_string($_REQUEST['ref_by']);
	$role=mysql_real_escape_string($_REQUEST['role']);
	/*$city=mysql_real_escape_string($_REQUEST['city']);
	$address=mysql_real_escape_string($_REQUEST['address']);
	$pin_code=mysql_real_escape_string($_REQUEST['pin_code']);
	$gender=mysql_real_escape_string($_REQUEST['gender']);*/
	 
	//if(mysql_query("insert into sv_user_profile(name,email_id,phone_no,city,address,pin_code,gender)values('$name','$email_id','$phone_no','$city','$address','$pin_code','$gender')"))
	if(mysql_query("insert into sv_user_profile(user_name,password,firstname,lastname,email_id,phone_no,ref_by,role)values('$name','$password','$firstname','$lastname','$email_id','$phone_no','$ref_by','$role')"))
	echo "Inserted";
 else
	echo "Server Error";
}
else if($type=='update')
{
	$name=mysql_real_escape_string($_REQUEST['name']);
	//$password=mysql_real_escape_string(md5($_REQUEST['password']));
	$firstname=mysql_real_escape_string($_REQUEST['firstname']);
	$lastname=mysql_real_escape_string($_REQUEST['lastname']);
	$email_id=mysql_real_escape_string($_REQUEST['email_id']);
	$phone_no=mysql_real_escape_string($_REQUEST['phone_no']);
	$ref_by=mysql_real_escape_string($_REQUEST['ref_by']);
	$role=mysql_real_escape_string($_REQUEST['role']);
	/*$city=mysql_real_escape_string($_REQUEST['city']);
	$address=mysql_real_escape_string($_REQUEST['address']);
	$pin_code=mysql_real_escape_string($_REQUEST['pin_code']);
	$gender=mysql_real_escape_string($_REQUEST['gender']);*/

	$hid=mysql_real_escape_string($_REQUEST['hid']);
	//if(mysql_query("update sv_user_profile set name='$name',email_id='$email_id',phone_no='$phone_no',city='$city',address='$address',pin_code='$pin_code',gender='$gender' where signup_id='$hid'")) 
	if(mysql_query("update sv_user_profile set user_name='$name',firstname='$firstname',lastname='$lastname',email_id='$email_id',phone_no='$phone_no',ref_by='$ref_by',role='$role' where signup_id='$hid'")) 
		echo "Updated";
	else 
		echo "Error";
  
}
else if($type=='delete')
{
	$hid=mysql_real_escape_string($_REQUEST["hid"]);		
	if(mysql_query("delete from sv_user_profile where signup_id='$hid'"))
	{ 
		mysql_query("delete from sv_user_order where uid='$hid'");
		echo "Deleted";
	}
	else 
	{
		echo "Error";
	}
}  

?>