<?php include("../connection.php"); ?>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<?php 
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
	$page = 'users';

?>


  <body class="splash-index">
   
<?php include("top_menu.php") ?>

 <?php include("side_menu.php") ?>

<div id="page-wrapper" >
		
		  <div class="header"> 
                        <h1 class="page-header">
                            User Details
                        </h1>
						<ol class="breadcrumb">
					  <li><a href="#">Home</a></li>
					  <li><a href="#">User</a></li>
					  
					</ol>		
		</div>
              <input type="hidden" id="hid" name="hid" value="<?php echo $uid;?>">
            <div id="">
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
				<!--	<div class="err-msg" id="err_id"><?php //echo $msg;?></div>	</div>-->

							 
							<form class="form-large"  accept-charset="UTF-8" method="post">

				<!--<div class="col-lg-2 col-md-2 col-sm-2"></div>
				<div class="col-lg-8 col-md-8 col-sm-8 table-bg">-->
                <div class="col-lg-12 col-md-12 col-sm-12 table-bg">
                <input type="hidden" id="hid" name="hid" value="<?php echo $uid;?>">

				<div class="col-lg-3 col-md-3 col-sm-3" >
					<div class="form-group">
						<label>User Name</label>
					<input type="text" id="name" required="required" class="form-control" name="name" value="<?php echo $name;?>">
					</div>
				</div>
                <?php
				if(!isset($_REQUEST['uid'])) 
				{
				?>
                <div class="col-lg-3 col-md-3 col-sm-3" >
					<div class="form-group">
						<label>Password</label>
					<input type="text" id="password" required="required" class="form-control" name="password" value="<?php echo $password;?>">
					</div>
				</div>
                <?php
				}
				?>
                <div class="col-lg-3 col-md-3 col-sm-3" >
					<div class="form-group">
						<label>First Name</label>
					<input type="text" id="firstname" required="required" class="form-control" name="firstname" value="<?php echo $firstname;?>">
					</div>
				</div>
                <div class="col-lg-3 col-md-3 col-sm-3" >
					<div class="form-group">
						<label>Last Name</label>
					<input type="text" id="lastname" required="required" class="form-control" name="lastname" value="<?php echo $lastname;?>">
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Email Id</label>	
					<input type="text" id="email_id" required="required" class="form-control" name="email_id" value="<?php echo $email;?>">
				</div></div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Phone No</label>	
					<input type="text" id="phone_no" required="required" class="form-control" name="phone_no" value="<?php echo $phone_no;?>">
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Referred By</label>	
				<select id="ref_by" class="form-control" required="required" > 
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
				<select id="role" name="role" class="form-control" required="required" > 
					<option value="0" <?php { if($role=="0") echo "selected='selected'"; }?>>End User</option>
                    <option value="1" <?php { if($role=="1") echo "selected='selected'"; }?>>Employee</option>				 
				</select>
				</div>	
                </div>
				<?php /*?><div class="col-lg-3 col-md-3 col-sm-3" >
					<div class="form-group">
					<label>City</label>	
					<input type="text" id="city" required="required" class="form-control" name="city" value="<?php echo $city;?>">
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3" >
					<div class="form-group">
					<label>Address</label>	
					<input type="text" id="address" required="required" class="form-control" name="address" value="<?php echo $address;?>">
				</div>	</div>
				<div class="col-lg-3 col-md-3 col-sm-3" >
					<div class="form-group">
					<label>Pin Code</label>	
					<input type="text" id="pin_code" required="required" class="form-control" name="pin_code" value="<?php echo $pin_code;?>">
				</div>	
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="form-group">
					<label>Gender</label>	
				<select id="gender" class="form-control" required="required" > 
					<option value="">select gender</option>	
					<option value="1" <?php { if($gender=="1") echo "selected='selected'"; }?>>male</option>
					<option value="2" <?php { if($gender=="2") echo "selected='selected'"; }?>>female</option>
				</select>
				</div>	
				</div><?php */?>
				
					<div class="col-lg-4 col-md-4 col-sm-4 up-button">
					<!--<button type="submit" class="btn btn-primary" onClick="">Update</button>-->
                    <?php if(isset($_REQUEST['uid'])) { ?>
					<button type="button" class="btn btn-primary" onClick="javascript:user_funct('update')">Update</button>
					<?php } else { ?>
					<button type="button" class="btn btn-primary" onClick="javascript:user_funct('add')">Add</button>
					<?php } ?>
					</div>
				</div>
					</form>
					 
				<div class="col-lg-2 col-md-2 col-sm-2"></div>
				</div>

			<div id="page-inner"> 
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Users
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover"  id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Sno</th>
											<th>User Name</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
											<th>Email Id</th>
											<th>Phone No</th>
                                            <th>Referred By</th>
											<?php /*?><th>City</th>
											<th>Address</th>
											<th>Pin code</th>
											<th>Gender</th><?php */?>
											<th>Update</th>
											<th>Delete</th>
                                        </tr>
                                    </thead>
									<tbody>
									<?php		
									$sno=0;
									$res=mysql_query("select * from sv_user_profile ORDER BY signup_id DESC");
									while($row=mysql_fetch_array($res))
									{
										$sno++;
										$signup_id=mysql_real_escape_string($row['signup_id']);
										$name=mysql_real_escape_string($row['user_name']);	
										$gender=mysql_real_escape_string($row['gender']);
										if($gender==1)
											$gender="male";
										else if($gender==2)
											$gender="female";
										$ref_by=mysql_real_escape_string($row['ref_by']);
										if($ref_by==0)
											$ref_by="Postal Mailer";
										else if($ref_by==1)
											$ref_by="Door Flyer";
										else if($ref_by==2)
											$ref_by="MowPros Customer";
										else if($ref_by==3)
											$ref_by="Internet Website";													
									?> 
									
										<tr>
											<td><?php echo $sno; ?></td>
											<td><?php echo $name; ?></td>
                                            <td><?php echo $row['firstname']; ?></td>
                                            <td><?php echo $row['lastname']; ?></td>
											<td><?php echo $row['email_id']; ?></td>
											<td><?php echo $row['phone_no']; ?></td>
                                            <td><?php echo $ref_by; ?></td>
											<?php /*?><td><?php echo $row['city']; ?></td>
											<td><?php echo $row['address']; ?></td>
											<td><?php echo $row['pin_code']; ?></td>
											<td><?php echo $gender; ?></td><?php */?>
											<td><a href="users.php?uid=<?php echo $signup_id;?>"><img src="images/file_edit.png"  alt="update" title="update" ></a></td>
											<td><a href="javascript:user_del('<?php echo $signup_id;?>');"><img src="images/delete.png" alt="" title="delete"></a></td>
										</tr>
										<?php } ?>		
									</tbody>
															
                                </table>
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
         <!-- /. PAGE WRAPPER  -->	   
   

   </html>
