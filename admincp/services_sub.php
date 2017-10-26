<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include("../connection.php"); ?>
<?php 
if(isset($_REQUEST['sub_id']))
	{		
		$sub_id=mysql_real_escape_string($_REQUEST['sub_id']);
		$res=mysql_query("select * from sv_services_sub where sid='$sub_id'");
		$row=mysql_num_rows($res);
		if($row==0)
	 	{
		  $services_id="";
		  $services_name="";
		  $sub_sname="";
		  $price="";
		}
		else
		{			
			$fet=mysql_fetch_array($res);	
			$sname=mysql_real_escape_string($fet['services_name']);	
			$services_id=mysql_real_escape_string($fet['sid']);
			$query=mysql_fetch_array(mysql_query("select * from sv_services where services_id='$sname'"));
			$services_name=mysql_real_escape_string($query['services_name']);
			$sub_sname=mysql_real_escape_string($fet['services_sub_name']);
			$price=mysql_real_escape_string($fet['price']);
		}		
	}
	else
	{
		$services_id="";
		$services_name="";
		$sub_sname="";
		$price="";
	}
	$page = 'services_sub';

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
                            Services Sub category
                        </h1>
						<ol class="breadcrumb">
					  <li><a href="#">Home</a></li>
					  <li><a href="#">Services sub</a></li>
					</ol>		
		</div>
		<input type="hidden" id="hid" name="hid" value="<?php echo $sub_id;?>">
            <div id="page-inner">
			    <div class="panel-body">
				<div class="text-center">
				<?php
if(isset($_REQUEST['msg']))
{
	$msg=$_REQUEST['msg'];
		if($msg=="Inserted")
		{
		      echo '<div class="succ-msg">Inserted Successfully.</div>';
		}
		else if($msg=="Updated")
			{
		      echo '<div class="succ-msg">Updated Successfully</div>';		
			}
			else if($msg=="Deleted")
			{
		      echo '<div class="succ-msg">Deleted Successfully</div>';		
			}
}
else
	$msg="";
?>
<!--<div class="err-msg" id="err_id"><?php echo $msg;?></div>-->
	</div>
	

					<div class="col-lg-3 col-md-3 col-sm-3"></div>
				<div class="col-lg-6 col-md-6 col-sm-6 table-bg">
                <div class="col-lg-6 col-md-6 col-sm-6">
					<div class="form-group">
					<label>services Name</label>	
				<select id="sname" class="form-control" >
					<option value="">select services</option>
					<?php		
						$res=mysql_query("select * from sv_services");
						while($row=mysql_fetch_array($res))
						{
					?>
					<option value="<?php echo $row['services_id'];?>" <?php if(isset($_REQUEST['sub_id'])) {if($sname==$row['services_id']) echo "selected='selected'";} ?>> <?php echo $row['services_name'];?> </option>
					<?php
						}
					?>
				</select>
				</div>		
				<div class="err" id="sname_err"></div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="form-group">
					<label>Sub Services</label>	
					<input type="text" id="sub_sname" class="form-control" name="sub_sname" value="<?php echo $sub_sname; ?>">
					<div class="err" id="sub_name_err"></div>
					</div>		
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="form-group">
					<label>Price</label>	
					<input type="text" id="price" required="required" class="form-control" name="price" value="<?php echo $price; ?>">
					<div class="err" id="price_err"></div>
					</div>		
				</div>
				
				<div class="col-lg-6 col-md-6 col-sm-6 up-button" >
					<?php if(isset($_REQUEST['sub_id'])) { ?>
					<button type="submit" class="btn btn-primary" onclick="javascript:services_sub_funct('update')">Update</button>
					<?php } else { ?>
					<button type="submit" class="btn btn-primary" onclick="javascript:services_sub_funct('add')">Add</button>
					<?php } ?>
				</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3"></div>
					</div>
		
            <div id="page-inner"> 
               
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Sub Services
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>SNo</th>
											<th>services Name</th>
											<th>Sub services Name</th> 
											<th>Price</th>
											<th>Update</th>
											<th>Delete</th>	
                                        </tr>
                                    </thead>
									<tbody>
									<?php
										$sno=0;
										$res=mysql_query("select * from sv_services_sub order by sid DESC");
										while($row=mysql_fetch_array($res))
										{
											$sno++;
											$services_id=mysql_real_escape_string($row['sid']);				
											$sname=mysql_real_escape_string($row['services_name']);	
											$query=mysql_fetch_array(mysql_query("select * from sv_services where services_id='$sname'"));
											$services_name=mysql_real_escape_string($query['services_name']);	
											$sub_sname=mysql_real_escape_string($row['services_sub_name']);
									?> 
									
										<tr>
											<td><?php echo $sno; ?></td>
											<td><?php echo $services_name; ?></td>
											<td><?php echo $sub_sname; ?></td>
											<td><?php echo $row['price']; ?></td>
											<td><a href="services_sub.php?sub_id=<?php echo $services_id;?>"><img src="images/file_edit.png"  alt="update" title="update" ></a></td>
											<td><a href="javascript:services_sub_del('<?php echo $services_id;?>');"><img src="images/delete.png" alt="" title="delete"></a></td>
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
        
   
</body>

</html>
