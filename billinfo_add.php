<?php
include('connection.php');

$type=mysql_real_escape_string($_REQUEST['action']);
if($type=='add')
{
	$bfirstname=mysql_real_escape_string($_REQUEST['bfirstname']);
	$blastname=mysql_real_escape_string($_REQUEST['blastname']);
	$baddress=mysql_real_escape_string($_REQUEST['baddress']);
	$bcity=mysql_real_escape_string($_REQUEST['bcity']);
	$bstate=mysql_real_escape_string($_REQUEST['bstate']);
	$bzip=mysql_real_escape_string($_REQUEST['bzip']);
	
	$cardno=mysql_real_escape_string($_REQUEST['cardno']);
	$exp_month=mysql_real_escape_string($_REQUEST['exp_month']);
	$exp_year=mysql_real_escape_string($_REQUEST['exp_year']);
	$cvv=mysql_real_escape_string($_REQUEST['cvv']);
	$terms=mysql_real_escape_string($_REQUEST['terms']);
	
	$_SESSION['bfirstname']=$bfirstname;
	$_SESSION['blastname']=$blastname;
	$_SESSION['baddress']=$baddress;
	$_SESSION['bcity']=$bcity;
	$_SESSION['bstate']=$bstate;
	$_SESSION['bzip']=$bzip;
	$_SESSION['cardno']=$cardno;
	$_SESSION['exp_month']=$exp_month;
	$_SESSION['exp_year']=$exp_year;
	$_SESSION['terms']=$terms;
	 //$oid=$_SESSION['oid'];
	 
	// $row=mysql_fetch_array(mysql_query("select * from sv_user_profile where signup_id='".$_SESSION['id']."'"));
	//$name=$row['firstname'].' '.$row['lastname'];
	//$id=$_SESSION['id'];
	$name=$_SESSION['username'];
	$phone_no=$_SESSION['phone_no'];
	
	/*start payment process */
	include 'Gateway.php';

	//declare an instance of the Gateway object to process transactions  - required
	$Gateway = new Gateway();
	
	//fields passed depend on situation, see function for full list of parameters and order they go in. a lot of them are being ignored in this example
	$TransactionType = "sale";
	$PaymentType = "creditcard";
	
	//gateway credentials 
	$GatewayUserName = "flexportdemo";
	$GatewayPassword = "FlexPort123!";
	
	/*$CCNumber = "4111111111111111";
	$CCExpDate = "1420";
	$CVV = "123";*/
	$CCNumber = $cardno;
	$CCExpDate = $exp_month.substr($exp_year,2,3);
	$CVV = $cvv;
	
	//general transaction request fields
	$Amount = $_SESSION['price']+$_SESSION['serviceprice'];
	$tax=$_SESSION['tax'];
	$OrderDescription = "widget";
	$FirstName = "Tim";
	$LastName = "Tester";
	$EMail = "fake@fakeemail.com";
	$SAFE_Action = "";
	$SAFE_ID = "";
	
	
	//encrypted mag tek data fake card example
	//$MagData = "%B4847180004001326^RECIPIENT/GIFT CARD^24060000000000000000000?;4847180004001326=240600000000000?|0600|6064B8E8FA692E6C4F2E1EE2799EA1C27AFF26297A6809A93090EDBF9B3F7E2D172E1D19B810F8186DC6441B1A5AAD79663AB6E8BF8793ED5BC7A74BFC5A74BC|1DAF1CE832BF57E59A8D799BCE39FBCB7468F1088D9D7F4775F18923C6FB11E21A59CFBCD8DDA43B||61403000|0A9800459E1D9ED5FEAB709E2EB9567927C637F2C48B1F7E41CE9D373E400F860E08B3C4647A543AA28498F9B55C1CFB9A5FD9A16C5E9557|B1C5554082813AA|964D303CA7BE8750|9011880B1C555400008A|3B8F||0000";
	$MagData = "";
	
	//calling process transaction via the Gateway object -- required
	$results = $Gateway->process($TransactionType, $GatewayUserName, $GatewayPassword, "", "", $PaymentType, $SAFE_Action, $SAFE_ID, $CCNumber, $CCExpDate, $CVV, "", "", "", "", "", "", $Amount, $tax, "", $OrderDescription, "", "", $FirstName, $LastName, "", "", "", "", "", "", "", "", "", $EMail, "", "", "", "", "", "", "", "", "", "", "", "", $MagData);
	
	//an array of results
	//print_r($results);
	
	//pulling out individual response values
	$pstatus=$results['ProcessTransactionResult']['STATUS_CODE'];
	
	$paymentstatus='pending';
	if($pstatus==1)
	{
		$paymentstatus='completed';
	}
	$_SESSION['paymentstatus']=$paymentstatus;
	/*end payment process*/
  	$username=mysql_real_escape_string($_SESSION['username']);
	$pass=mysql_real_escape_string($_SESSION['password']);
	$password=mysql_real_escape_string(md5($pass));
	//$pass=md5('1234');
	$firstname=mysql_real_escape_string($_SESSION['firstname']);
	$lastname=mysql_real_escape_string($_SESSION['lastname']);
	//$name=mysql_real_escape_string($_REQUEST['name']);
	$email_id=mysql_real_escape_string($_SESSION['email_id']);
	$phone_no=mysql_real_escape_string($_SESSION['phone_no']);
	$ref_by=mysql_real_escape_string($_SESSION['ref_by']);
	
	$date=date("Y-m-d");
	
	mysql_query("insert into sv_user_profile(user_name,password,firstname,lastname,email_id,phone_no,ref_by,date)values('$username','$password','$firstname','$lastname','$email_id','$phone_no',$ref_by,'$date')");
	$id=mysql_insert_id();
		//$_SESSION['id']=$id;
  //if(mysql_query("update sv_user_order set cardno=$cardno,exp_month=$exp_month,exp_year='$exp_year',terms=$terms where order_id='$oid'")) 
  if(mysql_query("insert into sv_user_order set uid=".$id.",name='".$name."',address='".$_SESSION['address']."',city='".$_SESSION['city']."',state='".$_SESSION['state']."',post_code='".$_SESSION['zip']."',services=".$_SESSION['service'].",serviceprice=".$_SESSION['serviceprice'].",date='".date("Y-m-d")."',phone_no='".$_SESSION['phone_no']."',price=".$_SESSION['price'].",lotsize=".$_SESSION['lotsize'].",tax=".$_SESSION['tax'].",startweek=".$_SESSION['startweek'].",cleanup_req=".$_SESSION['cleanup'].",gatecode='".$_SESSION['gatecode']."',notes='".$_SESSION['notes']."',agree=".$_SESSION['agree'].",cardno=$cardno,exp_month=$exp_month,exp_year='$exp_year',terms=$terms,bfirstname='$bfirstname',blastname='$blastname',baddress='$baddress',bcity='$bcity',bstate='$bstate',bzip='$bzip',payment_mode='creditcard',payment_status='$paymentstatus'")) 
  {	
  	
	echo "Inserted";
  }
 else
 {
	echo "Server Error";
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