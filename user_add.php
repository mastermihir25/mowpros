<?php
include('connection.php');

function repeatcheck($username,$email)
{
	 
	//$qry2 =  mysql_query("select * from sv_user_profile where (firstname = '".$fname."' and lastname ='".$lname."') or email_id='".$email."'");
	$qry2 =  mysql_query("select * from sv_user_profile where user_name = '".$username."' and email_id='".$email."'");
	
		if(mysql_num_rows($qry2)>0)
		{
			/*kk 27-9 $row=mysql_fetch_array($qry2);
			$_SESSION['id']=$row['signup_id'];
			$_SESSION['username']=$row['user_name'];
			$_SESSION['firstname']=$row['firstname'];
			$_SESSION['lastname']=$row['lastname'];
			$_SESSION['email_id']=$row['email_id'];
			$_SESSION['phone_no']=$row['phone_no'];*/
			return false;
		}
		else
		{
			return true;
		}
	
	
}

$type=mysql_real_escape_string($_REQUEST['action']);
if($type=='add')
{
	$username=mysql_real_escape_string($_REQUEST['username']);
	$pass=mysql_real_escape_string($_REQUEST['password']);
	$password=mysql_real_escape_string(md5($pass));
	//$pass=md5('1234');
	$firstname=mysql_real_escape_string($_REQUEST['firstname']);
	$lastname=mysql_real_escape_string($_REQUEST['lastname']);
	//$name=mysql_real_escape_string($_REQUEST['name']);
	$email_id=mysql_real_escape_string($_REQUEST['email_id']);
	$phone_no=mysql_real_escape_string($_REQUEST['phone_no']);
	
	//$city=mysql_real_escape_string($_REQUEST['city']);
	//$address=mysql_real_escape_string($_REQUEST['address']);
	//$pin_code=mysql_real_escape_string($_REQUEST['pin_code']);
	//$gender=mysql_real_escape_string($_REQUEST['gender']);
	$ref_by=mysql_real_escape_string($_REQUEST['ref_by']);
	$_SESSION['username']=$username;
	$_SESSION['password']=$pass;
	$_SESSION['firstname']=$firstname;
	$_SESSION['lastname']=$lastname;
	$_SESSION['email_id']=$email_id;
	$_SESSION['phone_no']=$phone_no;
	$_SESSION['ref_by']=$ref_by;
	
	if(repeatcheck($username,$email_id))
	{
		$date=date("Y-m-d");
		 
	  /*if(mysql_query("insert into sv_user_profile(user_name,password,firstname,lastname,email_id,phone_no,ref_by,date)values('$username','$password','$firstname','$lastname','$email_id','$phone_no',$ref_by,'$date')"))
	  {*/
		//$id=mysql_insert_id();
		//$_SESSION['id']=$id;
		if(isset($_SESSION['address']))
		{
			echo "Order Inserted";
				/*$name=$_SESSION['firstname'].' '.$_SESSION['lastname'];
				$phone_no=$_SESSION['phone_no'];
				$date=date("Y-m-d");
				$address=$_SESSION['address'];
				$city=$_SESSION['city'];
				$state=$_SESSION['state'];
				$zip=$_SESSION['zip'];
			  if(mysql_query("insert into sv_user_order(uid,name,address,city,state,post_code,services,sub_services,date,phone_no,price)values('$id','$name','$address','$city','$state','$zip',1,1,'$date','$phone_no',30)"))
			  { 
				$oid=mysql_insert_id();
				$_SESSION['oid']=$oid; 	
				echo "Order Inserted";
			  }*/
		}
		else
		{
		echo "Inserted";
		}
	  
	  /*else
	  {
	 	echo "Server Error";
	  }*/
	}
	else
	{
		//echo "hi";
		/*kk 27-9 if(isset($_SESSION['address']))
		{*/
				/*$id=$_SESSION['id'];
				$name=$_SESSION['firstname'].' '.$_SESSION['lastname'];
				$phone_no=$_SESSION['phone_no'];
				$date=date("Y-m-d");
				$address=$_SESSION['address'];
				$city=$_SESSION['city'];
				$state=$_SESSION['state'];
				$zip=$_SESSION['zip'];
			  if(mysql_query("insert into sv_user_order(uid,name,address,city,state,post_code,services,sub_services,date,phone_no,price)values('$id','$name','$address','$city','$state','$zip',1,1,'$date','$phone_no',30)"))
			  { 
				$oid=mysql_insert_id();
				$_SESSION['oid']=$oid; 	
				echo "Order Inserted";
			  }*/
		/*kk 27-9	  echo "Order Inserted";
		}
		else
		{*/
			 
		
		/*}*/
		echo "Repeated";
	}
	
}
else if($type=='repeatcheck')
{
	$field=$_REQUEST['field'];
	$value=$_REQUEST['value'];
	$qry2 =  mysql_query("select * from sv_user_profile where $field = '".$value."' ");
	
		if(mysql_num_rows($qry2)>0)
		{
			
			echo 'Repeated';
		}
		else
		{
			return 'false';
		}
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