<?php include("../connection.php"); ?>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>



  <body class="splash-index">
   
<?php include("top_menu.php") ?>

 <?php include("side_menu.php") ?>

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

							 
							
					 
				<div class="col-lg-2 col-md-2 col-sm-2"></div>
				</div>

			<div id="page-inner"> 
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Customers
                             <?php
                            if($_SESSION['role']==1 || $_SESSION['role']==2)
							{
							?><div style="float:right">
                            <a href="excel.php" target="_blank" class="btn btn-success" style="padding:5px 12px;" >Export</a> &nbsp;&nbsp; <a href="addusers.php?action=add" class="btn btn-success" style="padding:5px 12px;" >Add Customer</a>
                            
                             </div>
                             <?php
							}
							?>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive" id="customer_table">
                                <table class="table table-striped table-bordered table-hover"  id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Sno</th>
											<!--<th>User Name</th>-->
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Address</th>
											<th>Email Id</th>
											<th>Phone No</th>
                                           	<th>State</th>
                                            <th>City</th>											
											<th>Zip code</th>
                                            <th>New Add/Order</th>
                                            <th>View</th>
                                            
                                            <?php /*?><th>Referred By</th>
											
											<th>Gender</th><?php */?>
											<th>Edit</th>
											<th>Delete</th>
                                        </tr>
                                    </thead>
									<tbody>
									<?php		
									$sno=0;
									if($_SESSION['role']==1 || $_SESSION['role']==2)
									{
									$res=mysql_query("select p.signup_id,p.user_name,p.firstname,p.lastname,p.email_id,p.phone_no,p.ref_by,o.address,o.city,o.state,o.post_code from sv_user_profile p,sv_user_order o where p.signup_id=o.uid ORDER BY signup_id  DESC");
									}
									else
									{
										$res=mysql_query("select p.signup_id,p.user_name,p.firstname,p.lastname,p.email_id,p.phone_no,p.ref_by,o.address,o.city,o.state,o.post_code from sv_user_profile p,sv_user_order o where p.signup_id=o.uid and p.signup_id='".$_SESSION['admin_id']."' ORDER BY p.signup_id DESC");
									}
									while($row=mysql_fetch_array($res))
									{
										$sno++;
										$signup_id=mysql_real_escape_string($row['signup_id']);
										$name=mysql_real_escape_string($row['user_name']);	
										/*$gender=mysql_real_escape_string($row['gender']);
										if($gender==1)
											$gender="male";
										else if($gender==2)
											$gender="female";*/
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
											<?php /*?><td><?php echo $name; ?></td><?php */?>
                                            <td><?php echo $row['firstname']; ?></td>
                                            <td><?php echo $row['lastname']; ?></td>
                                            <td><?php echo $row['address']; ?></td>
											<td><?php echo $row['email_id']; ?></td>
											<td><?php echo $row['phone_no']; ?></td>
                                           	<td><?php echo $row['state']; ?></td>
                                            <td><?php echo $row['city']; ?></td>								<td><?php echo $row['post_code']; ?></td>
                                            <?php /*?><td><?php echo $ref_by; ?></td>
											
											<td><?php echo $gender; ?></td><?php */?>
                                            <td><a href="addorder.php?uid=<?php echo $signup_id;?>"><img src="images/add_order.png"  alt="New Address/Order" title="New Address/Order" ></a></td>
                                            <td><a href="addusers.php?action=view&uid=<?php echo $signup_id;?>"><img src="images/view.png"  alt="view" title="view" ></a></td>
											<td><a href="addusers.php?action=edit&uid=<?php echo $signup_id;?>"><img src="images/file_edit.png"  alt="update" title="update" ></a></td>
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
    
</body>
   </html>
