<?php
include('../connection.php');
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
			$row=mysql_fetch_array($qry2);
			return $row['signup_id'];;
		}
		else
		{
			return 0;
		}
	
	
}
$type=mysql_real_escape_string($_REQUEST['action']);
if($type=='add' || $type=='reorder')
{	
	$name=mysql_real_escape_string($_REQUEST['name']);
	$password=mysql_real_escape_string(md5($_REQUEST['password']));
	$firstname=mysql_real_escape_string($_REQUEST['firstname']);
	$lastname=mysql_real_escape_string($_REQUEST['lastname']);
	$email_id=mysql_real_escape_string($_REQUEST['email_id']);
	$phone_no=mysql_real_escape_string($_REQUEST['phone_no']);
	$ref_by=mysql_real_escape_string($_REQUEST['ref_by']);
	$role=mysql_real_escape_string($_REQUEST['role']);
	//$oid=mysql_real_escape_string($_REQUEST['oid']);
	$uid=mysql_real_escape_string($_REQUEST['uid']);
	$name=mysql_real_escape_string($_REQUEST['name']);	
	$address=mysql_real_escape_string($_REQUEST['address']);
	$city=mysql_real_escape_string($_REQUEST['city']);
	$state=mysql_real_escape_string($_REQUEST['state']);
	$post_code=mysql_real_escape_string($_REQUEST['post_code']);
	$phone_no=mysql_real_escape_string($_REQUEST['phone_no']);
	$lotsize=mysql_real_escape_string($_REQUEST['lotsize']);
	$price=mysql_real_escape_string($_REQUEST['price']);
	$tax=mysql_real_escape_string($_REQUEST['tax']);
	$startweek=mysql_real_escape_string($_REQUEST['startweek']);
	$cleanup_req=mysql_real_escape_string($_REQUEST['cleanup_req']);
	$service=mysql_real_escape_string($_REQUEST['service']);
	$serviceprice=mysql_real_escape_string($_REQUEST['serviceprice']);
	$gatecode=mysql_real_escape_string($_REQUEST['gatecode']);
	$notes=mysql_real_escape_string($_REQUEST['notes']);
	$cardno=mysql_real_escape_string($_REQUEST['cardno']);
	$exp_month=mysql_real_escape_string($_REQUEST['exp_month']);
	$exp_year=mysql_real_escape_string($_REQUEST['exp_year']);
	$payment_status=mysql_real_escape_string($_REQUEST['payment_status']);
	$bfirstname=mysql_real_escape_string($_REQUEST['bfirstname']);
	$blastname=mysql_real_escape_string($_REQUEST['blastname']);
	$baddress=mysql_real_escape_string($_REQUEST['baddress']); 
	$bcity=mysql_real_escape_string($_REQUEST['bcity']); 
	$bstate=mysql_real_escape_string($_REQUEST['bstate']); 
	$bzip=mysql_real_escape_string($_REQUEST['bzip']); 
	$agree=mysql_real_escape_string($_REQUEST['agree']); 
	$terms=mysql_real_escape_string($_REQUEST['terms']); 
	$date=date("Y-m-d"); 
	
	/*start payment process */
	/*include '../Gateway.php';

	//declare an instance of the Gateway object to process transactions  - required
	$Gateway = new Gateway();
	
	//fields passed depend on situation, see function for full list of parameters and order they go in. a lot of them are being ignored in this example
	$TransactionType = "sale";
	$PaymentType = "creditcard";
	
	//gateway credentials 
	$GatewayUserName = "flexportdemo";
	$GatewayPassword = "FlexPort123!";
	
	//$CCNumber = "4111111111111111";
	//$CCExpDate = "1420";
	//$CVV = "123"; 
	$CCNumber = $cardno;
	$CCExpDate = $exp_month.$exp_year;
	$CVV = $cvv;
	
	//general transaction request fields
	$Amount = $_SESSION['price'];
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
	
	$payment_status='pending';
	if($pstatus==1)
	{
		$payment_status='completed';
	}*/
	/*end payment process*/
	$repeat=repeatcheck($name,$email_id);
	if($repeat!=0)
	{
		$uid=$repeat;
	}
	else
	{
	$res1=mysql_query("insert into  sv_user_profile set user_name='$name',password='$password',firstname='$firstname',lastname='$lastname',email_id='$email_id',phone_no='$phone_no',ref_by='$ref_by',role='$role'");
	$uid=mysql_insert_id();
	}
	
		  
	$res2=mysql_query("insert into sv_user_order set name='$name',uid='$uid',address='$address',city='$city',state='$state',date='$date',post_code='$post_code',phone_no='$phone_no',lotsize='$lotsize',price='$price',tax='$tax',startweek='$startweek',cleanup_req='$cleanup_req',services='$service',serviceprice='$serviceprice',gatecode='$gatecode',notes='$notes',cardno='$cardno',exp_month='$exp_month',exp_year='$exp_year',payment_status='$payment_status',bfirstname='$bfirstname',blastname='$blastname',baddress='$baddress',bcity='$bcity',bstate='$bstate',bzip='$bzip',agree='$agree',terms='$terms'");
	if($res2)
	{
			echo "Inserted";
	}
	else
	{
		echo "Server Error";
	}
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
	//$oid=mysql_real_escape_string($_REQUEST['oid']);
	$uid=mysql_real_escape_string($_REQUEST['uid']);
	
	$address=mysql_real_escape_string($_REQUEST['address']);
	$city=mysql_real_escape_string($_REQUEST['city']);
	$state=mysql_real_escape_string($_REQUEST['state']);
	$post_code=mysql_real_escape_string($_REQUEST['post_code']);
	$phone_no=mysql_real_escape_string($_REQUEST['phone_no']);
	$lotsize=mysql_real_escape_string($_REQUEST['lotsize']);
	$price=mysql_real_escape_string($_REQUEST['price']);
	$tax=mysql_real_escape_string($_REQUEST['tax']);
	$startweek=mysql_real_escape_string($_REQUEST['startweek']);
	$cleanup_req=mysql_real_escape_string($_REQUEST['cleanup_req']);
	$service=mysql_real_escape_string($_REQUEST['service']);
	$serviceprice=mysql_real_escape_string($_REQUEST['serviceprice']);
	$gatecode=mysql_real_escape_string($_REQUEST['gatecode']);
	$notes=mysql_real_escape_string($_REQUEST['notes']);
	$cardno=mysql_real_escape_string($_REQUEST['cardno']);
	$exp_month=mysql_real_escape_string($_REQUEST['exp_month']);
	$exp_year=mysql_real_escape_string($_REQUEST['exp_year']);
	$payment_status=mysql_real_escape_string($_REQUEST['payment_status']);
	$bfirstname=mysql_real_escape_string($_REQUEST['bfirstname']);
	$blastname=mysql_real_escape_string($_REQUEST['blastname']);
	$baddress=mysql_real_escape_string($_REQUEST['baddress']); 
	$bcity=mysql_real_escape_string($_REQUEST['bcity']); 
	$bstate=mysql_real_escape_string($_REQUEST['bstate']); 
	$bzip=mysql_real_escape_string($_REQUEST['bzip']); 
	$agree=mysql_real_escape_string($_REQUEST['agree']); 
	$terms=mysql_real_escape_string($_REQUEST['terms']); 
	
	mysql_query("update sv_user_profile set user_name='$name',firstname='$firstname',lastname='$lastname',email_id='$email_id',phone_no='$phone_no',ref_by='$ref_by',role='$role' where signup_id='$uid'");
	$sql=mysql_query("update sv_user_order set name='$name',address='$address',city='$city',state='$state',post_code='$post_code',phone_no='$phone_no',lotsize='$lotsize',price='$price',tax='$tax',startweek='$startweek',cleanup_req='$cleanup_req',services='$service',serviceprice='$serviceprice',gatecode='$gatecode',notes='$notes',cardno='$cardno',exp_month='$exp_month',exp_year='$exp_year',payment_status='$payment_status',bfirstname='$bfirstname',blastname='$blastname',baddress='$baddress',bcity='$bcity',bstate='$bstate',bzip='$bzip',agree='$agree',terms='$terms' where uid='$uid'");
	
	
		echo "Updated";
	
}
else if($type=='statuschange')
{
	$oid=$_REQUEST['oid'];
	$status=$_REQUEST['status'];
	mysql_query("update sv_user_order set status=$status where order_id='".$oid."'");
	echo 1;
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
else if($type=='paymentproceed')
{
	/*start payment process */
	include '../Gateway.php';

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
	$CCNumber = $_REQUEST['cardno'];
	$CCExpDate = $_REQUEST['exp_month'].$_REQUEST['exp_year'];
	$CVV = $_REQUEST['cvv'];
	
	//general transaction request fields
	$Amount = $_REQUEST['price']+$_REQUEST['serviceprice'];
	$tax=$_REQUEST['tax'];
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
	
	echo $paymentstatus;
	/*end payment process*/
}
 
?>