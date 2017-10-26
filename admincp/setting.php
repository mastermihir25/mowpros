<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include("../connection.php"); ?>
<?php 
	$res=mysql_query("select * from sv_admin_login");
	$row=mysql_num_rows($res);
		if($row==0)
	 	{
			$admin_id="";
			$email_id="";	
			$admin_name="";
			$site_name="";
			$logo="";
			$favicon="";
			$site_desc="";
			$keyword="";
			$site_url="";
			$paypal_id="";
			$cmode="";
			$smode="";
		}
		else
		{			
			$fet=mysql_fetch_array($res);	
			$admin_id=mysql_real_escape_string($fet['admin_id']);
			$email_id=mysql_real_escape_string($fet['email_id']);
			$admin_name=mysql_real_escape_string($fet['user_name']);	
			$site_name=mysql_real_escape_string($fet['site_name']);
			$logo=mysql_real_escape_string($fet['logo']);		
			$favicon=mysql_real_escape_string($fet['favicon']);	
			$site_desc=mysql_real_escape_string($fet['site_desc']);	
			$keyword=mysql_real_escape_string($fet['keyword']);
			$site_url=mysql_real_escape_string($fet['site_url']);
			$paypal_id=mysql_real_escape_string($fet['paypal_id']);
			$cmode=mysql_real_escape_string($fet['currency_mode']);
			$smode=mysql_real_escape_string($fet['paypal_site_mode']);
		}	
		$page = 'setting';
?>

<body>
    <div id="wrapper">
        <?php include("top_menu.php") ?>
        <!--/. NAV TOP  -->
        <?php include("side_menu.php") ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
		  <div class="header"> 
                        <h1 class="page-header">
                            Setting
                        </h1>
						<ol class="breadcrumb">
					  <li><a href="#">Home</a></li>
					  <li><a href="#">Setting</a></li>
					</ol>		
		</div>
            <div id="page-inner">
			    <div class="panel-body">
			<div class="text-center">
				<?php
if(isset($_REQUEST['msg']))
{
	$msg=$_REQUEST['msg'];
		if($msg=="Inserted")
		{
		      echo '<div class="succ-msg">Inserted Successfully</div>';
		}
		else if($msg=="Updated")
			{
		      echo '<div class="succ-msg">Updated Successfully</div>';		
			}
}
else
	$msg="";
?>					</div>

<!--<div class="err-msg" id="err_id"><?php echo $msg;?></div>	</div>-->
		<input type="hidden" id="id" name="id" value="<?php echo $admin_id;?>">
		<form class="" name="admin_s" id="admin_s" method="post" enctype="multipart/form-data" action="admin_setting_addcode.php" >
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="form-group">
						<label>User Name</label>				
						<input type="text" id="admin_name" class="form-control" name="admin_name" value="<?php echo $admin_name;?>">
					</div>
					<div class="err" id="admin_name_err"></div>
				</div>

				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="form-group">
						<label>Email ID</label>				
						<input type="text" id="email_id" class="form-control" name="email_id" value="<?php echo $email_id;?>">
					</div>
					<div class="err" id="email_err"></div>
				</div>
							
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="form-group">
						<label>Site Name</label>				
						<input type="text" id="site_name" class="form-control" name="site_name" value="<?php echo $site_name;?>">
					</div>
					<div class="err" id="site_name_err"></div>
				</div>
				
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="form-group">
						<label>Site Logo</label>	
					<div class="err" style="padding:10px;" id="">[Please select an image 200px / 51px]</div>
						
						<input type="file" id="logo" class="form-control" name="logo" value="<?php echo ""?>">
						<?php
						if($logo=="") { ?>		
						<a href="#"><img src="admin-logo/default-logo.png" alt="" style="margin-top:20px;"></a>
						<?php
							}
							else
							{
							?>
						<a href="admin-logo/<?php echo $logo;?>" target="blank"><img src="admin-logo/<?php echo $logo;?>" alt="" style="margin-top:20px;"></a>
						<?php } ?>	
					</div>
					<div class="err" id="logo_err"></div>
				</div>
				
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="form-group">
						<label>Site  Favicon</label>	
						<div style="padding:10px;" class="err" id="">[Please select an image 16px / 24px]</div>							
						<input  type="file" id="favicon" class="form-control" name="favicon" value="<?php echo ""?>" onclick="javascript:checkFile1()">
						<?php
						if($favicon=="") { 	?>				
						<?php } else { ?>
				<a href="admin-logo/<?php echo $favicon;?>" target="blank"><img src="admin-logo/<?php echo $favicon;?>" alt=""  style="margin-top:20px;"></a>
					<?php
						}
					?>	
					</div>
					<div class="err" id="favicon_err"></div>
				</div>
				
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="form-group">
						<label>Site  Description</label>				
						<input type="text" id="site_desc" class="form-control" name="site_desc" value="<?php echo $site_desc?>">
					</div>
					<div class="err" id="site_desc_err"></div>
				</div>
				
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="form-group">
						<label>Site Keyword</label>				
						<input type="text" id="keyword" class="form-control" name="keyword" value="<?php echo $keyword;?>">
					</div>
					<div class="err" id="site_kwd_err"></div>
				</div>
				
				<div class="col-lg-4 col-md-4 col-sm-4"></div>
				
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="form-group">
						<label>Site Url</label>				
						<input type="text" id="site_url" class="form-control" name="site_url" value="<?php echo $site_url;?>">
					</div>
					<div class="err" id="site_url_err"></div>
				</div>
				
				
				<div class="col-lg-12 col-md-12 col-sm-12">
					<h3 class="text-center">Paypal Setting</h3>
						<div class="col-lg-4 col-md-4 col-sm-4">
						<label>Paypal Id</label>	
							<input type="text" id="paypal_id" class="form-control" name="paypal_id" value="<?php echo $paypal_id;?>">
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4">
							<label>Currency Mode</label>	
								<select id="cmode" name="cmode" class="form-control">
									<option value="">Select currency</option>
									<option value="USD" <?php { if($cmode=="USD") echo "selected='selected'"; }?>>USD</option>
									<option value="CZK" <?php { if($cmode=="CZK") echo "selected='selected'"; }?>>CZK</option>
									<option value="DKK" <?php { if($cmode=="DKK") echo "selected='selected'"; }?>>DKK</option>
									<option value="HKD" <?php { if($cmode=="HKD") echo "selected='selected'"; }?>>HKD</option>
									<option value="HUF" <?php { if($cmode=="HUF") echo "selected='selected'"; }?>>HUF</option>
									<option value="ILS" <?php { if($cmode=="ILS") echo "selected='selected'"; }?>>ILS</option>
									<option value="JPY" <?php { if($cmode=="JPY") echo "selected='selected'"; }?>>JPY</option>
									<option value="MXN" <?php { if($cmode=="MXN") echo "selected='selected'"; }?>>MXN</option>
									<option value="NZD" <?php { if($cmode=="NZD") echo "selected='selected'"; }?>>NZD </option>
									<option value="NOK" <?php { if($cmode=="NOK") echo "selected='selected'"; }?>>NOK</option>
									<option value="PHP" <?php { if($cmode=="PHP") echo "selected='selected'"; }?>>PHP</option>
									<option value="PLN" <?php { if($cmode=="PLN") echo "selected='selected'"; }?>>PLN</option>
									<option value="SGD" <?php { if($cmode=="SGD") echo "selected='selected'"; }?>>SGD</option>
									<option value="SEK" <?php { if($cmode=="SEK") echo "selected='selected'"; }?>>SEK</option>
									<option value="CHF" <?php { if($cmode=="CHF") echo "selected='selected'"; }?>>CHF</option>																	
									<option value="THB " <?php { if($cmode=="THB") echo "selected='selected'"; }?>>THB</option>

									<option value="AUD" <?php { if($cmode=="AUD") echo "selected='selected'"; }?>>AUD</option>
									<option value="CAD" <?php { if($cmode=="CAD") echo "selected='selected'"; }?>>CAD</option>
									<option value="EUR" <?php { if($cmode=="EUR") echo "selected='selected'"; }?>>EUR</option>
									<option value="GBP" <?php { if($cmode=="GBP") echo "selected='selected'"; }?>>GBP</option>
									
									<option value="BRL" <?php { if($cmode=="BRL") echo "selected='selected'"; }?>>BRL </option>
									<option value="MYR" <?php { if($cmode=="MYR") echo "selected='selected'"; }?>>MYR</option>
									<option value="NOK" <?php { if($cmode=="NOK") echo "selected='selected'"; }?>>NOK</option>
									<option value="TWD" <?php { if($cmode=="TWD") echo "selected='selected'"; }?>>TWD</option>
									<option value="TRY" <?php { if($cmode=="TRY") echo "selected='selected'"; }?>>TRY</option>
		  
										
									<option value="DZD" <?php { if($cmode=="DZD") echo "selected='selected'"; }?>>DZD </option>
									<option value="AZN" <?php { if($cmode=="AZN") echo "selected='selected'"; }?>>AZN</option>
									<option value="BHD" <?php { if($cmode=="BHD") echo "selected='selected'"; }?>>BHD</option>
									<option value="BYR" <?php { if($cmode=="BYR") echo "selected='selected'"; }?>>BYR</option>
									<option value="BAM" <?php { if($cmode=="BAM") echo "selected='selected'"; }?>>BAM</option>
									<option value="BWP" <?php { if($cmode=="BWP") echo "selected='selected'"; }?>>BWP</option>
									<option value="BND" <?php { if($cmode=="BND") echo "selected='selected'"; }?>>BND </option>
									<option value="BGN" <?php { if($cmode=="BGN") echo "selected='selected'"; }?>>BGN</option>
									<option value="KYD" <?php { if($cmode=="KYD") echo "selected='selected'"; }?>>KYD</option>
									<option value="COP" <?php { if($cmode=="COP") echo "selected='selected'"; }?>>COP</option>
									<option value="XOF" <?php { if($cmode=="XOF") echo "selected='selected'"; }?>>XOF</option>
									<option value="XAF" <?php { if($cmode=="XAF") echo "selected='selected'"; }?>>XAF</option>
									
									<option value="CDF" <?php { if($cmode=="CDF") echo "selected='selected'"; }?>>CDF </option>
									<option value="HRK" <?php { if($cmode=="HRK") echo "selected='selected'"; }?>>HRK</option>
									<option value="CYP" <?php { if($cmode=="CYP") echo "selected='selected'"; }?>>CYP</option>
									<option value="CZK" <?php { if($cmode=="CZK") echo "selected='selected'"; }?>>CZK</option>
									<option value="DKK" <?php { if($cmode=="DKK") echo "selected='selected'"; }?>>DKK</option>
									<option value="DJF" <?php { if($cmode=="DJF") echo "selected='selected'"; }?>>DJF</option>
									<option value="EGP" <?php { if($cmode=="EGP") echo "selected='selected'"; }?>>EGP </option>
									<option value="ERN" <?php { if($cmode=="ERN") echo "selected='selected'"; }?>>ERN</option>
									<option value="INR" <?php { if($cmode=="INR") echo "selected='selected'"; }?>>INR</option>
									<option value="IDR" <?php { if($cmode=="IDR") echo "selected='selected'"; }?>>IDR</option>
									<option value="IRR" <?php { if($cmode=="IRR") echo "selected='selected'"; }?>>IRR</option>
									<option value="RSD" <?php { if($cmode=="RSD") echo "selected='selected'"; }?>>RSD</option>
		  
		  
		  
		  
								</select>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4">
							<label>Paypal site Mode</label>	
								<select id="smode" name="smode" class="form-control">
									<option value="">Select</option>
									<option value="test" <?php { if($smode=="test") echo "selected='selected'"; }?>>Test</option>
									<option value="live" <?php { if($smode=="live") echo "selected='selected'"; }?>>Live</option>
								</select>
						</div>
				</div>

				
				
				<div class="col-lg-12" style="margin-top:25px;">
					<button type="button" class="btn btn-primary" onclick="javascript:admin_email()">Save</button>
				</div>
				
				
				
			</form>

		
            <div id="page-inner"> 
               
                               </div>
							 <?php include("footer.php") ?>

    </div>
             <!-- /. PAGE INNER  -->
            </div>
          
</body>

</html>
