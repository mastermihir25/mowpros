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
		 
	}
	$page = 'employe';

?>


  <body class="splash-index">
   
<?php include("top_menu.php") ?>

 <?php include("side_menu.php") ?>

<div id="page-wrapper" >
		
		  <div class="header"> 
                        <h1 class="page-header">
                            Employe Details
                        </h1>
						<ol class="breadcrumb">
					  <li><a href="#">Home</a></li>
					  <li><a href="#">Employe</a></li>
					  
					</ol>		
		</div>
              
             
				
				<!--	<div class="err-msg" id="err_id"><?php //echo $msg;?></div>	</div>-->

							 
							
					 
				 

			<div id="page-inner"> 
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Employe
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
                <input type="hidden" id="hid" name="hid" value="<?php echo $uid;?>">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="form-group" style="border-bottom: 1px solid;">
					<label>Employe Detail</label>
                    </div>
                </div>
				<div class="col-lg-3 col-md-3 col-sm-3" >
					<div class="form-group">
						<label>Employe Name</label>
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
				<select id="ref_by" class="form-control" required="required"  <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>> 
					<option value="0" <?php { if($ref_by=="0") echo "selected='selected'"; }?>>Postal Mailer</option>
                    <option value="1" <?php { if($ref_by=="1") echo "selected='selected'"; }?>>Door Flyer</option>
                    <option value="2" <?php { if($ref_by=="2") echo "selected='selected'"; }?>>MowPros Customer</option>
                    <option value="3" <?php { if($ref_by=="3") echo "selected='selected'"; }?>>Internet Website</option>
					 
				</select>
				</div>	
                </div>
                <input type="hidden" name="role" id="role" value="1" />
                <?php /*?><div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Role</label>	
				<select id="role" name="role" class="form-control" required="required"  <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='view') { echo 'readonly';} ?>> 
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
                </div><?php */?>
				 
				
				 
				
					<div class="col-lg-12 col-md-12 col-sm-12 up-button">
					<!--<button type="submit" class="btn btn-primary" onClick="">Update</button>-->
                    <?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='edit') { ?>
					<button type="button" class="btn btn-primary" onClick="javascript:user_funct('update')">Update</button>
					<?php } else if(isset($_REQUEST['action']) && $_REQUEST['action']=='add') { ?>
					<button type="button" class="btn btn-primary" onClick="javascript:user_funct('add')">Add</button>
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
