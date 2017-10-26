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
                            Order
                        </h1>
						<ol class="breadcrumb">
					  <li><a href="#">Home</a></li>
					  <li><a href="#">Order</a></li>
					</ol>		
		</div>
            <div id="page-inner">
<?php
if(isset($_REQUEST['msg']))
	$msg=$_REQUEST['msg'];
else
	$msg="";
?>
<div class="err" style="color:red" id="err_id"><?php echo $msg;?></div>	
		
            <div id="page-inner"> 
               
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Order
                             <div style="float:right">
                             <a href="reorder.php" class="btn btn-success" style="padding:5px 12px;" >Add Order</a>
                             </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                           <th>Sno</th>
										    <th>User_Name</th>
											<th>Phone_No</th>
											<th>City</th>
											<th>Address</th>
											<!--<th>Services</th>
											<th>Sub_Services</th>-->
											<!--<th>Order_ID</th>-->
											<th>Order_Date</th>
											<!--<th>Order_Time</th>
											<th>Requirement</th>-->
											<th>Postal_Code</th>
											<th>Price</th>
											<!--<th>Payment_Mode</th>
											<th>Payment_Status</th>
											<th>Currency_Code</th>-->
                                            <th>Start Week</th>
                                            <th>Cleanup</th>
                                            <th>Note</th>
                                            <!--<th>Agree</th>
                                            <th>Terms Agree</th>-->
                                            <th>Re-Order</th>
                                        </tr>
                                    </thead>
									<tbody>
									<?php
									$sno=0;
									if($_SESSION['admin_id']==1)
									{
										$res1=mysql_query("select * from sv_user_order ORDER BY order_id DESC");
									}
									else
									{
										
									$res1=mysql_query("select * from sv_user_order where uid=".$_SESSION['admin_id']." ORDER BY order_id DESC");
									}
									if(mysql_num_rows($res1)>0)
									{
									while($row=mysql_fetch_array($res1))
									{
										$sno++;
										$phone_no=mysql_real_escape_string($row['phone_no']);
										$services_id=mysql_real_escape_string($row['services']);
										$sub_services_id=mysql_real_escape_string($row['sub_services']);
										$order_date=mysql_real_escape_string(date("d-m-Y",strtotime($row['date'])));
										$query=mysql_fetch_array(mysql_query("select user_name from sv_user_profile where signup_id='".$row['uid']."'"));
										/*$services=mysql_fetch_array(mysql_query("select * from sv_services where services_id='$services_id'"));
										$sub_services=mysql_fetch_array(mysql_query("select * from sv_services_sub where sid='$sub_services_id'"));*/

									?>									
										<tr>
											<td><?php echo $sno; ?></td>
											<td><?php echo $query['user_name'];?></td>
											<td><?php echo $row['phone_no']; ?></td>
											<td><?php echo $row['city']; ?></td>
											<td><?php echo $row['address']; ?></td>
											<?php /*?><td><?php echo $services['services_name']; ?></td>
											<td><?php echo $sub_services['services_sub_name']; ?></td><?php */?>
											<?php /*?><td><?php echo $row['order_id'];?></td><?php */?>
											<td><?php echo $order_date; ?></td>
											<?php /*?><td><?php echo $row['order_time']; ?></td>
											<td><?php echo $row['requirement']; ?></td><?php */?>
											<td><?php echo $row['post_code']; ?></td>
											<td><?php echo $row['price']; ?></td>
											<?php /*?><td><?php echo $row['payment_mode']; ?></td>
											<td><?php echo $row['payment_status']; ?></td>
											<td><?php echo $currency_code; ?></td><?php */?>
                                            <td><?php echo $row['startweek']; ?><?php if($row['startweek']==1){ echo 'st';}elseif($row['startweek']==2){ echo 'nd';}elseif($row['startweek']==3){ echo 'rd';}elseif($row['startweek']==4){ echo 'th';} ?> Week</td>
											<td><?php if($row['cleanup_req']==1){ echo "No";}else{ echo "Yes";} ?></td>
                                            <td><?php echo $row['notes']; ?></td>
                                            <?php /*?><td><?php if($row['agree']==1){ echo "Yes";}else{ echo "No";} ?></td>
                                            <td><?php if($row['terms']==1){ echo "Yes";}else{ echo "No";} ?></td><?php */?>
                                            <td><a href="reorder.php?oid=<?php echo $row['order_id'];?>"><img src="images/reorder.png"  alt="update" title="update" ></a></td>
											
										</tr>
										<?php }
									}?>		
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
        
</body>

</html>
