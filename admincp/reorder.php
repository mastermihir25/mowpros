<?php include("../connection.php");
@session_start();
if(!isset($_SESSION['user_name']))
	header("Location:index.php");
else
{		
	$user_name=mysql_real_escape_string($_SESSION['user_name']);			
	//$res=mysql_fetch_array(mysql_query("select * from sv_admin_login where user_name='$user_name'"));
	//$uname=mysql_real_escape_string($res['user_name']);
	$uname=mysql_real_escape_string($_SESSION['user_name']);	
}	
$page = 'order';

if(isset($_REQUEST['oid']))
{
	$res=mysql_query("select * from sv_user_order where order_id=".$_REQUEST['oid']);
	if(mysql_num_rows($res)>0)
	{
		while($row=mysql_fetch_array($res))
		{
			$order_id=$row['order_id'];	
			$uid=$row['uid'];	
			$name=$row['name'];	
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
		}
	}
}
else
{
	$order_id=0;	
	$uid=0;	
	$name="";	
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
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<body>
    <div id="wrapper">
        <?php include("top_menu.php") ?>
        <!--/. NAV TOP  -->
        <?php include("side_menu.php") ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
		  <div class="header"> 
                        <h1 class="page-header">
                        <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='edit') { ?>Update<?php } else if(isset($_REQUEST['oid'])) { ?>Re<?php }else{ ?>Add<?php }?>-Order
                        </h1>
						<ol class="breadcrumb">
					  <li><a href="#">Home</a></li>
					  <li><a href="#"><?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='edit') { ?>Update<?php } else if(isset($_REQUEST['oid'])) { ?>Re<?php } else{ ?>Add<?php }?>-Order</a></li>
					</ol>		
		</div>
            <div id="page-inner">
<?php
if(isset($_REQUEST['msg']))
	$msg=$_REQUEST['msg'];
else
	$msg="";
?>
<script language="javascript">
function getuservalue(str)
{
	//alert(str);
	if(str!="")
	{
		var url="user_add.php?id="+str+"&action=getval";
		 
	}
	
	xmlHttp.onreadystatechange=function()
 	        {	   
				if(xmlHttp.readyState==4 && xmlHttp.status==200)
  				{
  					var msg=xmlHttp.responseText.trim(); 
					//alert(msg);
					document.getElementById('name').value=msg;
					
				}
			}
			xmlHttp.open("POST",url,true);
			xmlHttp.send(null);
	
			
}
</script>
<div class="err" style="color:red" id="err_id"><?php echo $msg;?></div>	
		
            <div id="page-inner"> 
               
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='edit') { ?>Update<?php } else if(isset($_REQUEST['oid'])) { ?>Re<?php } else{ ?>Add<?php }?>-Order
                        </div>
                        <div class="panel-body">
                            <div class="text-center">
                 <form class="form-large"  accept-charset="UTF-8" method="post">

				<!--<div class="col-lg-2 col-md-2 col-sm-2"></div>
				<div class="col-lg-8 col-md-8 col-sm-8 table-bg">-->
                <!--<div class="col-lg-12 col-md-12 col-sm-12 table-bg">-->
                <input type="hidden" id="oid" name="oid" value="<?php echo $order_id;?>">
				<input type="hidden" id="name" name="name" value="<?php echo $name;?>">
                <div class="col-lg-12 col-md-12 col-sm-12">
					<div class="form-group" style="border-bottom: 1px solid;">
					<label>Order Detail</label>
                    </div>
                </div>

				<div class="col-lg-3 col-md-3 col-sm-3" >
					<div class="form-group">
						<label>User Name</label>
					<select id="uid" name="uid" class="form-control" required="required" onChange="getuservalue(this.value);" >
                    <?php
					$res=mysql_query("select * from sv_user_profile");
					if(mysql_num_rows($res)>0)
					{
						while($row=mysql_fetch_array($res))
						{
					 ?>
					<option value="<?php echo $row['signup_id'];?>" <?php { if($uid==$row['signup_id']) echo "selected='selected'"; }?> ><?php echo $row['user_name'];?></option>
                    <?php
						}
					}
						?>					 
				</select>
					</div>
				</div>
               
                <div class="col-lg-3 col-md-3 col-sm-3" >
					<div class="form-group">
						<label>Address</label>
					<input type="text" id="address" required="required" class="form-control" name="address" value="<?php echo $address;?>">
					</div>
				</div>
                <div class="col-lg-3 col-md-3 col-sm-3" >
					<div class="form-group">
						<label>City</label>
					<input type="text" id="city" required="required" class="form-control" name="city" value="<?php echo $city;?>">
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>State</label>	
					<input type="text" id="state" required="required" class="form-control" name="state" value="<?php echo $state;?>">
				</div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Zip</label>	
					<input type="text" id="post_code" required="required" class="form-control" name="post_code" value="<?php echo $post_code;?>" onblur="getpricequote(document.getElementById('address').value,document.getElementById('city').value,document.getElementById('state').value,document.getElementById('post_code').value);">
				</div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Phone No</label>	
					<input type="text" id="phone_no" required="required" class="form-control" name="phone_no" value="<?php echo $phone_no;?>" >
					</div>
				</div>
                <div class="col-lg-12 col-md-12 col-sm-12">
					<div class="form-group" style="border-bottom: 1px solid;">
					<label>Price Quote</label>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Lot Size</label>	
					<input type="text" id="lotsize" required="required" class="form-control" name="lotsize" value="<?php echo $lotsize;?>">
				</div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Price</label>	
					<?php /*?><input type="text" id="price" required="required" class="form-control" name="price" value="<?php echo $price;?>"><?php */?>
                    <div id="pricedetail"><input name="price" id="price" value="" checked="checked" type="radio" onClick="document.getElementById('tax').value=document.getElementById('price1tax').innerHTML;">$<span id="firstprice"></span>.00 Weekly (+$<span id="price1tax"></span> tax)<br />
                    <input name="price" id="price1" value=""  type="radio" onClick="document.getElementById('tax').value=document.getElementById('price2tax').innerHTML;">$<span id="secondprice"></span>.00 Weekly (+$<span id="price2tax"></span> tax)
                    </div>
				</div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Tax</label>	
					<input type="text" id="tax" required="required" class="form-control" name="tax" value="<?php echo $tax;?>">
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
				<select id="startweek" name="startweek" class="form-control" required="required" > 
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
				<select id="cleanup_req" name="cleanup_req" class="form-control" required="required" > 
					<option value="0" <?php { if($cleanup_req==0) echo "selected='selected'"; }?>>Cleanup</option>
                    <option value="1" <?php { if($cleanup_req==1) echo "selected='selected'"; }?>>No-Cleanup</option>				 
				</select>
				</div>	
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Notes</label>	
					<input type="text" id="notes" required="required" class="form-control" name="notes" value="<?php echo $notes;?>">
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
					<input type="text" id="cardno" required="required" class="form-control" name="cardno" value="<?php echo $cardno;?>">
				</div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Exp. Month</label>	
					 
                    <select id="exp_month" size="1" name="exp_month" required="required" class="form-control">
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
					<select id="exp_year" required="required" class="form-control" name="exp_year" >
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
					<input type="text" id="cvv"  class="form-control" name="cvv" value="" />
				</div>
                </div>
                
                
				 <script language="javascript">
				 function asperabove()
				 {
					 if(document.getElementById('asprev').checked)
					 {
						 
						 document.getElementById('baddress').value=document.getElementById('address').value;
						 document.getElementById('bcity').value=document.getElementById('city').value;
						 document.getElementById('bstate').value=document.getElementById('state').value;
						 document.getElementById('bzip').value=document.getElementById('post_code').value;
					 }
					 else
					 {
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
					<input type="text" id="bfirstname" required="required" class="form-control" name="bfirstname" value="<?php echo $bfirstname;?>">
				</div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Last name</label>	
					<input type="text" id="blastname" required="required" class="form-control" name="blastname" value="<?php echo $blastname;?>">
				</div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Address</label>	
					<input type="text" id="baddress" required="required" class="form-control" name="baddress" value="<?php echo $baddress;?>">
				</div>
                </div>
	 			<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>City</label>	
					<input type="text" id="bcity" required="required" class="form-control" name="bcity" value="<?php echo $bcity;?>">
				</div>
                </div>
	 			<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>State</label>	
					<input type="text" id="bstate" required="required" class="form-control" name="bstate" value="<?php echo $bstate;?>">
				</div>
                </div>
	 			<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Zip</label>	
					<input type="text" id="bzip" required="required" class="form-control" name="bzip" value="<?php echo $bzip;?>">
				</div>
                </div>
	 			<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="form-group" style="border-bottom: 1px solid;">
					<label>Terms</label>
                    </div>
                </div>
	        <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Clean Agree</label>				 
                    <input id="agree" name="agree" onclick=" if(this.value==0){this.value=1;}else{this.value=0;}"  value="<?php echo $agree;?>"  type="checkbox"  required="required" class="form-control"  <?php { if($agree==1) echo "checked"; }?>>
				</div>
                </div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Terms & Condition</label>				 
                    <input id="terms" name="terms" onclick=" if(this.value==0){this.value=1;}else{this.value=0;}"  value="<?php echo $terms;?>"  type="checkbox"  required="required" class="form-control"  <?php { if($terms==1) echo "checked"; }?>>
				</div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Payment Status</label>	
				<select id="payment_status" name="payment_status" class="form-control" required="required" style="padding:0px 0px !important;" > 
					<option value="pending" <?php { if($payment_status=="pending") echo "selected='selected'"; }?>>Pending</option>
                    <option value="completed" <?php { if($payment_status=="completed") echo "selected='selected'"; }?>>Completed</option>				 
				</select>
				</div>	
                </div>
				 
				
					<div class="col-lg-12 col-md-12 col-sm-12 up-button">
					<!--<button type="submit" class="btn btn-primary" onClick="">Update</button>-->
                    <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='edit') { ?>
					<button type="button" class="btn btn-primary" onClick="javascript:order_funct('update')">Update</button>
					<?php } else if(isset($_REQUEST['oid'])) { ?>
					<button type="button" class="btn btn-primary" onClick="javascript:order_funct('reorder')">Re-order</button>
					<?php }
					else
					{ ?>
					<button type="button" class="btn btn-primary" onClick="javascript:order_funct('add')">Add</button>
					 <?php
					}
					?>
					</div>
				
					</form>
                            </div>
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
        
</body>

</html>
