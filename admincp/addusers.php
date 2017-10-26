<?php include("../connection.php"); ?>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<?php 
$uid=0;
if(isset($_REQUEST['uid']))
	{		
		$uid=mysql_real_escape_string($_REQUEST['uid']);
		$res=mysql_query("select * from sv_user_profile where signup_id='$uid'");
		$row=mysql_num_rows($res);
		if($row==0)
	 	{
		  $signup_id="";
		  $name="";
		  $password="";
		  $firstname="";
		  $lastname="";
		  $phone_no="";
		  /*$city="";
		  $address="";	*/	  
		  $email="";
		  $ref_by="";
		  $role="";
		  /*$pin_code="";
		  $gender="";*/
		  $oid="";
		  $address="";	
			$city="";	
			$state="";		 
			$date=date("Y-m-d H:i:s");	
			$phone_no="";
			$post_code="";	
			$lotsize=0;		
			$price=0;	
			$tax=0;	
			$payment_status="pending";	
			$startweek=1;	
			$cleanup_req="";
			$service="";
			$serviceprice=0;
			$gatecode="";
			$notes="";	
			$agree="";	
			$cardno="";	
			$exp_month="";	
			$exp_year="";	
			$terms="";	
			$bfirstname="";	
			$blastname="";	
			$baddress="";	
			$bcity="";	
			$bstate="";	
			$bzip="";
			$status="";
			
			$firstprice=0;
			$price1tax=0;
			$secondprice=0;
			$price2tax=0;	
		}
		else
		{			
			$fet=mysql_fetch_array($res);	
			$name=mysql_real_escape_string($fet['user_name']);	
			$password=mysql_real_escape_string($fet['password']);
		  	$firstname=mysql_real_escape_string($fet['firstname']);
		  	$lastname=mysql_real_escape_string($fet['lastname']);
			$phone_no=mysql_real_escape_string($fet['phone_no']);	
			/*$city=mysql_real_escape_string($fet['city']);	
			$address=mysql_real_escape_string($fet['address']);	*/
			$email=mysql_real_escape_string($fet['email_id']);	
			$ref_by=mysql_real_escape_string($fet['ref_by']);
			$role=mysql_real_escape_string($fet['role']);
			/*$pin_code=mysql_real_escape_string($fet['pin_code']);	
			$gender=mysql_real_escape_string($fet['gender']);*/	
			$res2=mysql_query("select * from sv_user_order where uid=".$_REQUEST['uid']);
			$row=mysql_fetch_array($res2);	
			$oid=$row['order_id'];
			$address=$row['address'];	
			$city=$row['city'];	
			$state=$row['state'];		 
			$date=$row['date'];	
			$phone_no=$row['phone_no'];
			$post_code=$row['post_code'];	
			$lotsize=$row['lotsize'];		
			$price=$row['price'];	
			$tax=$row['tax'];	
			$payment_status=$row['payment_status'];	
			$startweek=$row['startweek'];	
			$cleanup_req=$row['cleanup_req'];	
			$service=$row['services'];
			$serviceprice=$row['serviceprice'];
			$gatecode=$row['gatecode'];
			$notes=$row['notes'];	
			$agree=$row['agree'];	
			$cardno=$row['cardno'];	
			$exp_month=$row['exp_month'];	
			$exp_year=$row['exp_year'];	
			$terms=$row['terms'];	
			$bfirstname=$row['bfirstname'];	
			$blastname=$row['blastname'];	
			$baddress=$row['baddress'];	
			$bcity=$row['bcity'];	
			$bstate=$row['bstate'];	
			$bzip=$row['bzip'];
			$status=$row['status'];
			$firstprice=0;
			$secondprice=0;
			if($lotsize>0 && $lotsize<=6000)
			{
				$firstprice=25;
				$secondprice=35;
			}
			else if($lotsize>6000 && $lotsize<=8000)
			{
				$firstprice=27;
				$secondprice=37;
			}
			else if($lotsize>8000 && $lotsize<=11000)
			{
				$firstprice=30;
				$secondprice=40;
			}
			else if($lotsize>11000 && $lotsize<=13000)
			{
				$firstprice=35;
				$secondprice=45;
			}
			else if($lotsize>11000 && $lotsize<=13000)
			{
				$firstprice=35;
				$secondprice=45;
			}
			else if($lotsize>13000 && $lotsize<=16000)
			{
				$firstprice=40;
				$secondprice=50;
			}	
			$price1tax=number_format((($firstprice*8.25)/100),2);
			$price2tax=number_format((($secondprice*8.25)/100),2);
		}		
	}
	else
	{
		$signup_id="";
		$name="";
		$password="";
		$firstname="";
		$lastname="";
		$phone_no="";
		/*$city="";
		$address="";*/
		$email="";
		$ref_by="";
		$role="";
		/*$pin_code="";
		$gender="";*/
		$oid="";
		$address="";	
		$city="";	
		$state="";		 
		$date=date("Y-m-d H:i:s");	
		$phone_no="";
		$post_code="";	
		$lotsize=0;		
		$price=0;	
		$tax=0;	
		$payment_status="pending";	
		$startweek=1;	
		$cleanup_req="";
		$service="";
		$serviceprice=0;
		$gatecode="";
		$notes="";	
		$agree="";	
		$cardno="";	
		$exp_month="";	
		$exp_year="";	
		$terms="";	
		$bfirstname="";	
		$blastname="";	
		$baddress="";	
		$bcity="";	
		$bstate="";	
		$bzip="";	
		
		$firstprice=0;
		$price1tax=0;
		$secondprice=0;
		$price2tax=0;
		$status=0;
	}
	$page = 'users';

?>


  <body class="splash-index">
   
<?php include("top_menu.php") ?>

 <?php include("side_menu.php") ?>
<script language="javascript">
function changestatus(id,val)
{
	var oid=id;
	var status=val;
	if(status==1)
	{
		var cmsg="Are you sure to De-activate this order ?";
	}
	else
	{
		var cmsg="Are you sure to activate this order ?";
	}
	var confirmation = confirm(cmsg);
		
	if(confirmation)
	{
	var url="customer_add.php";
	xmlHttp.onreadystatechange=function () 
	{
		if (xmlHttp.readyState==4 && xmlHttp.status==200) 
		{
			var msg=xmlHttp.responseText;
			//alert(msg);
			if(msg==1 && status==1)
			{
				alert("Successfully De-activate this order.");
			}
			else if(msg==1 && status==0)
			{
				alert("Successfully Activate this order.");
			}
			window.location.reload();
		}
	} 		
	xmlHttp.open("post",url,true);
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlHttp.send("oid="+oid+"&status="+status+"&action=statuschange");
	}
}

</script>
<div id="page-wrapper" >
		
		  <div class="header"> 
                        <h1 class="page-header">
                            Customer Details
                        </h1>
						<ol class="breadcrumb">
					  <li><a href="#">Home</a></li>
					  <li><a href="#">Customer</a></li>
					  
					</ol>		
		</div>
              
             
				
				<!--	<div class="err-msg" id="err_id"><?php //echo $msg;?></div>	</div>-->

							 
							
					 
				 

			<div id="page-inner"> 
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Customers
                              <?php
                            if($_SESSION['role']==2 && $_REQUEST['action']=='edit')
							{
							?><div style="float:right">
                            <a href="javascript:void(0);" class="btn btn-<?php if($status==0){ echo 'danger';}else{ echo 'success';}?>" style="padding:5px 12px;" onClick="changestatus('<?php echo $oid;?>','<?php if($status==0){ echo 1;}else{ echo 0;}?>')" ><?php if($status==0){ echo 'De-active Order';}else{ echo 'Active Order';}?></a>                            
                             </div>
                             <?php
							}
							?>
                        </div>
                        <div class="panel-body">
                        <div class="text-center">
						<?php
                        if(isset($_REQUEST['msg']))
                        {
                            $msg=$_REQUEST['msg'];
                                if($msg=="Updated")
                                {
                                      echo '<div class="succ-msg">Updated Successfully.</div>';
                                }
                                else if($msg=="Deleted")
                                    {
                                      echo '<div class="succ-msg">Deleted Successfully</div>';		
                                    }
                        }
                        else
                            $msg="";
                        ?>
                        </div>
                            <form class="form-large"  accept-charset="UTF-8" method="post">

				<!--<div class="col-lg-2 col-md-2 col-sm-2"></div>
				<div class="col-lg-8 col-md-8 col-sm-8 table-bg">-->
                <div class="col-lg-12 col-md-12 col-sm-12 table-bg">
                <input type="hidden" id="uid" name="uid" value="<?php echo $uid;?>">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="form-group" style="border-bottom: 1px solid;">
					<label>User Detail</label>
                    </div>
                </div>
				<div class="col-lg-3 col-md-3 col-sm-3" >
					<div class="form-group">
						<label>User Name</label>
					<input type="text" id="name" required="required" class="form-control" name="name" value="<?php echo $name;?>" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
					</div>
				</div>
                
                <div class="col-lg-3 col-md-3 col-sm-3" >
					<div class="form-group">
						<label>Password</label>
					<input type="password" id="password" required="required" class="form-control" name="password" value="<?php echo $password;?>" <?php if(isset($_REQUEST['uid'])) {	?>readonly<?php }		?>>
					</div>
				</div>
                
                <div class="col-lg-3 col-md-3 col-sm-3" >
					<div class="form-group">
						<label>First Name</label>
					<input type="text" id="firstname" required="required" class="form-control" name="firstname" value="<?php echo $firstname;?>" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
					</div>
				</div>
                <div class="col-lg-3 col-md-3 col-sm-3" >
					<div class="form-group">
						<label>Last Name</label>
					<input type="text" id="lastname" required="required" class="form-control" name="lastname" value="<?php echo $lastname;?>" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Email Id</label>	
					<input type="text" id="email_id" required="required" class="form-control" name="email_id" value="<?php echo $email;?>" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
				</div></div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Phone No</label>	
					<input type="text" id="phone_no" required="required" class="form-control" name="phone_no" value="<?php echo $phone_no;?>" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Referred By</label>	
				<select id="ref_by" class="form-control" required  <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>> 
					<option value="0" <?php { if($ref_by=="0") echo "selected='selected'"; }?>>Postal Mailer</option>
                    <option value="1" <?php { if($ref_by=="1") echo "selected='selected'"; }?>>Door Flyer</option>
                    <option value="2" <?php { if($ref_by=="2") echo "selected='selected'"; }?>>MowPros Customer</option>
                    <option value="3" <?php { if($ref_by=="3") echo "selected='selected'"; }?>>Internet Website</option>
					 
				</select>
				</div>	
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Role</label>	
				<select id="role" name="role" class="form-control" required  <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>> 
					<option value="0" <?php { if($role=="0") echo "selected='selected'"; }?>>End User</option>
                    <?php
					if($_SESSION['role']==2)
					{
					?>
                    <option value="1" <?php { if($role=="1") echo "selected='selected'"; }?>>Employee</option>				
                    <?php
					}
					?>
				</select>
				</div>	
                </div>
				 <div class="col-lg-12 col-md-12 col-sm-12">
					<div class="form-group" style="border-bottom: 1px solid;">
					<label>Order Detail</label>
                    </div>
                </div>
				<div class="col-lg-3 col-md-3 col-sm-3" >
					<div class="form-group">
						<label>Address</label>
					<input type="text" id="address" required="required" class="form-control" name="address" value="<?php echo $address;?>" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
					</div>
				</div>
                <div class="col-lg-3 col-md-3 col-sm-3" >
					<div class="form-group">
						<label>City</label>
					<input type="text" id="city" required="required" class="form-control" name="city" value="<?php echo $city;?>" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>State</label>	
					<input type="text" id="state" required="required" class="form-control" name="state" value="<?php echo $state;?>" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
				</div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Zip</label>	
					<input type="text" id="post_code" required="required" class="form-control" name="post_code" value="<?php echo $post_code;?>" onBlur="getpricequote(document.getElementById('address').value,document.getElementById('city').value,document.getElementById('state').value,document.getElementById('post_code').value);" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
				</div>
                </div>
                <?php /*?><div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Phone No</label>	
					<input type="text" id="phone_no" required="required" class="form-control" name="phone_no" value="<?php echo $phone_no;?>"  <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
					</div>
				</div><?php */?>
                <div class="col-lg-12 col-md-12 col-sm-12">
					<div class="form-group" style="border-bottom: 1px solid;">
					<label>Price Quote</label>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Lot Size</label>	
					<input type="text" id="lotsize" required="required" class="form-control" name="lotsize" value="<?php echo $lotsize;?>" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
				</div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Price</label>	
					<?php /*?><input type="text" id="price" required="required" class="form-control" name="price" value="<?php echo $price;?>"><?php */?>
                    <div id="pricedetail"><input name="price" id="price" value="<?php echo $firstprice;?>" checked="checked" type="radio" onClick="document.getElementById('tax').value=document.getElementById('price1tax').innerHTML;"  <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>$<span id="firstprice"><?php echo $firstprice;?></span>.00 Weekly (+$<span id="price1tax"><?php echo $price1tax;?></span> tax)<br />
                    <input name="price" id="price1" value="<?php echo $secondprice;?>"  type="radio" onClick="document.getElementById('tax').value=document.getElementById('price2tax').innerHTML;"  <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>$<span id="secondprice"><?php echo $secondprice;?></span>.00 Weekly (+$<span id="price2tax"><?php echo $price2tax;?></span> tax)
                    </div>
				</div>
                </div>
               
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Tax</label>	
					<input type="text" id="tax" required="required" class="form-control" name="tax" value="<?php echo $tax;?>" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
				</div>
                </div>
                 <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Additional Service Price</label>	
					<input type="text" id="serviceprice" required="required" class="form-control" name="serviceprice" value="<?php echo $serviceprice;?>" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
                     
				</div>
                </div>
               <div class="col-lg-12 col-md-12 col-sm-12">
					<div class="form-group" style="border-bottom: 1px solid;">
					<label>First Service</label>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Start Week</label>	
					<?php
$currdate=$date;
$monday1=date('d F', strtotime('next monday', strtotime($currdate)));
$monday2=date('d F', strtotime('next monday', strtotime($monday1)));
$monday3=date('d F', strtotime('next monday', strtotime($monday2)));
$monday4=date('d F', strtotime('next monday', strtotime($monday3)));
?>
				<select id="startweek" name="startweek" class="form-control" required  <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>> 
                    <option value="0" <?php { if($startweek==0) echo "selected='selected'"; }?>>Week of <?php echo $monday1;?></option>			 
				 	<option value="1" <?php { if($startweek==1) echo "selected='selected'"; }?>>Week of <?php echo $monday2;?></option>
                    <option value="2" <?php { if($startweek==2) echo "selected='selected'"; }?>>Week of <?php echo $monday3;?></option>
                    <option value="3" <?php { if($startweek==3) echo "selected='selected'"; }?>>Week of <?php echo $monday4;?></option>
				</select>
				</div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Cleanup</label>	
				<select id="cleanup_req" name="cleanup_req" class="form-control" required  <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>> 
					<option value="0" <?php { if($cleanup_req==0) echo "selected='selected'"; }?>>Cleanup</option>
                    <option value="1" <?php { if($cleanup_req==1) echo "selected='selected'"; }?>>No-Cleanup</option>				 
				</select>
				</div>	
                </div>
                
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Gatecode</label>	
				<input type="text" id="gatecode" required="required" class="form-control" name="gatecode" value="<?php echo $gatecode;?>" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
				</div>	
                </div>
                
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Notes</label>	
					<input type="text" id="notes" required="required" class="form-control" name="notes" value="<?php echo $notes;?>" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
				</div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
					<div class="form-group" style="border-bottom: 1px solid;">
					<label>Services</label>
                    </div>
                </div>
                <script language="javascript">
				function getprice(str,action)
				{
					//alert(action);
					var sp=parseInt(document.getElementById('serviceprice').value);
					 
					if(str!='')
					{
						var url="ajax_getprice.php";
						xmlHttp.onreadystatechange=function () 
						{
							if (xmlHttp.readyState==4 && xmlHttp.status==200) 
							{
								var msg=xmlHttp.responseText;
								//alert(msg);
								var arr=msg.split("#");
								
								if(action=='remove' || action==false)
								{
									var totalsp=sp-parseInt(arr[1]);
									//alert(totalsp);
									document.getElementById('serviceprice').value=totalsp;
									
									var servicelist=document.getElementById("serviceslist").innerHTML;
									servicelist=servicelist.replace(arr[0]+" [$"+arr[1]+"]&nbsp;&nbsp;", "");
									document.getElementById("serviceslist").innerHTML=servicelist;
									
								}
								else
								{
									var totalsp=sp+parseInt(arr[1]);
								//alert(totalsp);
								document.getElementById('serviceprice').value=totalsp;
								document.getElementById("serviceslist").innerHTML+=arr[0]+" [$"+arr[1]+"]&nbsp;&nbsp;";
									
								}
							}
						} 		
						xmlHttp.open("post",url,true);
						xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						xmlHttp.send("id="+str);
					}
					
				}
				</script>
                <?php /*?><input type="hidden" name="serviceprice" id="serviceprice" value="<?php echo $serviceprice;?>"><?php */?>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Service</label>	
					<div>
				<?php 
				    $selectedValues = explode(",",$service);
				?>
			 
			<div class="custom-select" id="custom-select">Select Multiple Services</div>
			<div id="custom-select-option-box" >
				 
				<?php $qry="select sb.sid,s.services_id,s.services_name,sb.services_sub_name,sb.price from sv_services_sub as sb left join sv_services as s on sb.services_name=s.services_id";
$res=mysql_query($qry);
while($row=mysql_fetch_array($res))
{?>
				<div class="custom-select-option">
					<input onChange="getprice(this.value,$(this).prop('checked'));"
						class="custom-select-option-checkbox" type="checkbox"
						name="service[]"  value="<?php echo $row['sid'];?>" <?php if(isset($service) && in_array($row['sid'],$selectedValues)){ echo 'checked="checked"';}?>> <?php echo $row['services_name'];?>(<?php echo $row['services_sub_name'];?>)
				</div>
				<?php
}
?>
			</div>
		</div>
				<?php /*?><!--<select id="service" name="service" class="form-control"  onChange="getprice(this.value)" > 
                <div>
                <option value="0" <?php if(isset($service) && @$service==0){ echo 'selected="selected"';}?>>No Service</option>
                </div>
					<?php $qry="select sb.sid,s.services_id,s.services_name,sb.services_sub_name,sb.price from sv_services_sub as sb left join sv_services as s on sb.services_name=s.services_id";
$res=mysql_query($qry);
while($row=mysql_fetch_array($res))
{?>
<div>
<option value="<?php echo $row['sid'];?>" <?php if(isset($service) && @$service==$row['sid']){ echo 'selected="selected"';}?>><?php echo $row['services_name'];?>(<?php echo $row['services_sub_name'];?>)</option>
</div>
<?php
}
?>		 
				</select>--><?php */?>
<style>
.custom-select {
background: #FFF url(images/downward-arrow.png) no-repeat center right 10px;
display: inline-block;
padding: 5px 15px;
border: 1px solid #ccc;
color: #555;
border-radius: 2px;
width: 200px;
cursor:pointer;
    
}
div#custom-select-option-box {
    background: #FFF;
    border: 1px solid #ccc;
    color: #555;
    border-radius: 2px;
    position:absolute;
    z-index:1;
    display:none;
}
button.search.btn {
   	background: #4c4c4c;
    border: 1px solid #ccc;
    color: #555;
    border-radius: 2px;
    padding: 8px 40px;
    margin-top: 20px;
    font-size: 0.9em;
}
.custom-select-option {
    width: 200px;
    padding: 5px 15px;
    margin: 1px 0px;
    cursor:pointer;
}
.custom-select-option:hover {
    
}
.result-list {
    padding-bottom: 20px;
    color: #4d4d4d;
    line-height: 25px;
}
.result-list-heading {
    font-style: italic;
    color: #717171;
    text-decoration: underline;
}
</style>
<script>
	$("#custom-select").on("click", function() {
		$("#custom-select-option-box").toggle();
	});
	function toggleFillColor(obj) {
		$("#custom-select-option-box").show();
		if ($(obj).prop('checked') == true) {
			$(obj).parent().css("background", '#FFF');
		} else {
			$(obj).parent().css("background", '#FFF');
		}
	}
	$(".custom-select-option").on("click", function(e) {
		var checkboxObj = $(this).children("input");
		if ($(e.target).attr("class") != "custom-select-option-checkbox") {
			if ($(checkboxObj).prop('checked') == true) {
				$(checkboxObj).prop('checked', false);
				getprice($(checkboxObj).val(),'remove');
			} else {
				$(checkboxObj).prop("checked", true);
				getprice($(checkboxObj).val(),'add');
			}
		}
		toggleFillColor(checkboxObj);
		 
		
	});

	$("body")
			.on(
					"click",
					function(e) {
						if (e.target.id != "custom-select"
								&& $(e.target).attr("class") != "custom-select-option") {
							$("#custom-select-option-box").hide();
						}
					});
</script>
				</div>	
                </div>
                
                <div class="col-lg-9 col-md-9 col-sm-9">
					<div class="form-group" id="serviceslist">
					<label>Additional Services</label><br />	
					<?php
					$qry="select sb.sid,s.services_id,s.services_name,sb.services_sub_name,sb.price from sv_services_sub as sb left join sv_services as s on sb.services_name=s.services_id";
					$res=mysql_query($qry);
					while($row=mysql_fetch_array($res))
					{
						if(isset($service) && in_array($row['sid'],$selectedValues))
						{
						echo $row['services_name']."(".$row['services_sub_name'].") [$".$row['price']."]&nbsp;&nbsp;";
						}
					}
					?>
				</div>
                </div>
                
                <div class="col-lg-12 col-md-12 col-sm-12">
					<div class="form-group" style="border-bottom: 1px solid;">
					<label>Credit Detail</label>
                    </div>
                </div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>CCNumber</label>	
					<input type="text" id="cardno" required="required" class="form-control" name="cardno" value="<?php echo $cardno;?>" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
				</div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Exp. Month</label>	
					 
                    <select id="exp_month" size="1" name="exp_month" required class="form-control" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
                    <option value="01" <?php { if($exp_month==01) echo "selected='selected'"; }?>>Jan - 01</option>
                    <option value="02" <?php { if($exp_month==02) echo "selected='selected'"; }?>>Feb - 02</option>
                    <option value="03" <?php { if($exp_month==03) echo "selected='selected'"; }?>>Mar - 03</option>
                    <option value="04" <?php { if($exp_month==04) echo "selected='selected'"; }?>>Apr - 04</option>
                    <option value="05" <?php { if($exp_month==05) echo "selected='selected'"; }?>>May - 05</option>
                    <option value="06" <?php { if($exp_month==06) echo "selected='selected'"; }?>>Jun - 06</option>
                    <option value="07" <?php { if($exp_month==07) echo "selected='selected'"; }?>>Jul - 07</option>
                    <option value="08" <?php { if($exp_month==08) echo "selected='selected'"; }?>>Aug - 08</option>
                    <option value="09" <?php { if($exp_month==09) echo "selected='selected'"; }?>>Sep - 09</option>
                    <option value="10" <?php { if($exp_month==10) echo "selected='selected'"; }?>>Oct - 10</option>
                    <option value="11" <?php { if($exp_month==11) echo "selected='selected'"; }?>>Nov - 11</option>
                    <option value="12" <?php { if($exp_month==12) echo "selected='selected'"; }?>>Dec - 12</option>
                    </select>
				</div>
                </div> 
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Exp. Year</label>	
					<select id="exp_year" required class="form-control" name="exp_year"  <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
                    <?php
						for($i=date('Y');$i<date('Y')+10;$i++)
						{
							?>
							<option value="<?php echo $i;?>"  <?php { if($exp_year==$i) echo "selected='selected'"; }?>><?php echo $i;?></option>
							<?php
						}
						?>
					</select>
				</div>
                </div> 	
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>CVV</label>	
					<input type="text" id="cvv"  class="form-control" name="cvv" value=""  <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>/>
				</div>
                </div>
                
                
				 <script language="javascript">
				 function asperabove()
				 {
					 if(document.getElementById('asprev').checked)
					 {
						 
						 document.getElementById('bfirstname').value=document.getElementById('firstname').value;
						 document.getElementById('blastname').value=document.getElementById('lastname').value;
						 document.getElementById('baddress').value=document.getElementById('address').value;
						 document.getElementById('bcity').value=document.getElementById('city').value;
						 document.getElementById('bstate').value=document.getElementById('state').value;
						 document.getElementById('bzip').value=document.getElementById('post_code').value;
					 }
					 else
					 {
						 document.getElementById('bfirstname').value="";
						 document.getElementById('blastname').value="";
						 document.getElementById('baddress').value="";
						 document.getElementById('bcity').value="";
						 document.getElementById('bstate').value="";
						 document.getElementById('bzip').value="";
					 }
				 }
				 </script>
                <div class="col-lg-12 col-md-12 col-sm-12">
					<div class="form-group" style="border-bottom: 1px solid;">
					<label>Billing Information <input type="checkbox" id="asprev" name="asprev" onClick="asperabove()">As Per Above</label>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>First name</label>	
					<input type="text" id="bfirstname" required="required" class="form-control" name="bfirstname" value="<?php echo $bfirstname;?>" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
				</div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Last name</label>	
					<input type="text" id="blastname" required="required" class="form-control" name="blastname" value="<?php echo $blastname;?>" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
				</div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Address</label>	
					<input type="text" id="baddress" required="required" class="form-control" name="baddress" value="<?php echo $baddress;?>" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
				</div>
                </div>
	 			<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>City</label>	
					<input type="text" id="bcity" required="required" class="form-control" name="bcity" value="<?php echo $bcity;?>" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
				</div>
                </div>
	 			<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>State</label>	
					<input type="text" id="bstate" required="required" class="form-control" name="bstate" value="<?php echo $bstate;?>" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
				</div>
                </div>
	 			<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Zip</label>	
					<input type="text" id="bzip" required="required" class="form-control" name="bzip" value="<?php echo $bzip;?>" <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
				</div>
                </div>
	 			<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="form-group" style="border-bottom: 1px solid;">
					<label>Terms</label>
                    </div>
                </div>
	        <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Cleanup Agree</label>				 
                    <input id="agree" name="agree" onClick=" if(this.value==0){this.value=1;}else{this.value=0;}"  value="<?php echo $agree;?>"  type="checkbox"  required="required" class="form-control"  <?php { if($agree==1) echo "checked"; }?>  <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
				</div>
                </div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Terms & Condition</label>				 
                    <input id="terms" name="terms" onClick=" if(this.value==0){this.value=1;}else{this.value=0;}"  value="<?php echo $terms;?>"  type="checkbox"  required="required" class="form-control"  <?php { if($terms==1) echo "checked"; }?>  <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>>
				</div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Payment Status</label>	
				<select id="payment_status" name="payment_status" class="form-control" required style="padding:0px 0px !important;"  <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>> 
					<option value="pending" <?php { if($payment_status=="pending") echo "selected='selected'"; }?>>Pending</option>
                    <option value="completed" <?php { if($payment_status=="completed") echo "selected='selected'"; }?>>Completed</option>				 
				</select>
				</div>	
                </div>
                <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='add') { ?>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Payment Proceed?</label><br /><input type="checkbox" id="papro" name="papro" onClick="paymentproceed()">
                    </div>
                </div>
                <?php
				}
				?>
				 
				
					<div class="col-lg-12 col-md-12 col-sm-12 up-button">
					<!--<button type="submit" class="btn btn-primary" onClick="">Update</button>-->
                    <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='edit') { ?>
					<button type="button" class="btn btn-primary" onClick="javascript:customer_funct('update')">Update</button>
					<?php } else if(isset($_REQUEST['action']) && $_REQUEST['action']=='add') { ?>
					<button type="button" class="btn btn-primary" onClick="javascript:customer_funct('add')">Add</button>
					<?php 
					}
					else
					{
					 ?>
                     <button type="button" class="btn btn-primary" onClick="document.location.go(-1);">Back</button>
                     <?php
					}
					?>
					</div>
				</div>
					</form>
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
                <!-- /. ROW  -->
                   </div>
				   				<?php include("footer.php") ?>

    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->	   
   

   </html>
