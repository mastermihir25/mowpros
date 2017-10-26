<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include("../connection.php"); ?>
<?php 
if(isset($_REQUEST['services_id']))
	{		
		$services_id=mysql_real_escape_string($_REQUEST['services_id']);
		$res=mysql_query("select * from sv_services where services_id='$services_id'");
		$row=mysql_num_rows($res);
		if($row==0)
	 	{
		  $services_id="";
		  $services_name="";
		}
		else
		{			
			$fet=mysql_fetch_array($res);	
			$services_name=mysql_real_escape_string($fet['services_name']);	
		}		
	}
	else
	{
		$services_id="";
		$services_name="";
	}
	$page = 'services';
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
                            Services
                        </h1>
						<ol class="breadcrumb">
					  <li><a href="#">Home</a></li>
					  <li><a href="#">Services</a></li>
					</ol>		
		</div>
		<input type="hidden" id="hid" name="hid" value="<?php echo $services_id;?>">
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
<!--<div class="err-msg" id="err_id"><?php echo $msg;?></div>-->	</div>
				<div class="col-lg-3 col-md-3 col-sm-3"></div>
				<div class="col-lg-6 col-md-6 col-sm-6 table-bg">
                <div class="form-group">
                    <label>services Name</label>				
					<input type="text" id="sname" class="form-control" name="sname" value="<?php echo $services_name;?>">
				</div>
				<div class="text-center">
					<?php if(isset($_REQUEST['services_id'])) { ?>
					<button type="button" class="btn btn-primary" onclick="javascript:services_funct('update')">Update</button>
					<?php } else { ?>
					<button type="button" class="btn btn-primary" onclick="javascript:services_funct('add')">Add</button>
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
                             Services
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>SNo</th>
											<th>services Name</th>
											<th>Update</th>
											<th>Delete</th>	
                                        </tr>
                                    </thead>
									<tbody>
									<?php
										$sno=0;
										$res=mysql_query("select * from sv_services ORDER BY services_id DESC");
										while($row=mysql_fetch_array($res))
										{
											$sno++;
											$services_id=mysql_real_escape_string($row['services_id']);				
											$services_name=mysql_real_escape_string($row['services_name']);				
									?> 
									
										<tr>
											<td><?php echo $sno; ?></td>
											<td><?php echo $services_name; ?></td>
											<td><a href="services.php?services_id=<?php echo $services_id;?>"><img src="images/file_edit.png"  alt="update" title="update" ></a></td>
											<td><a href="javascript:services_del('<?php echo $services_id;?>');"><img src="images/delete.png" alt="" title="delete"></a></td>
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
