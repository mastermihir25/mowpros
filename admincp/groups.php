<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include("../connection.php"); ?>
<?php 
if(isset($_REQUEST['gid']))
	{		
		$gid=mysql_real_escape_string($_REQUEST['gid']);
		$res=mysql_query("select * from sv_groups where gid='$gid'");
		$row=mysql_num_rows($res);
		if($row==0)
	 	{
		  $gid="";
		  $group_name="";
		  $earr=array();
		  $teamleader="";
		}
		else
		{			
			$fet=mysql_fetch_array($res);	
			$gname=mysql_real_escape_string($fet['gname']);	
			$earr=explode(",",$fet['eid']);
			$teamleader=mysql_real_escape_string($fet['teamleader']);	
		}		
	}
	else
	{
		$gid="";
		$gname="";
		$earr=array();
		$teamleader='';
	}
	$page = 'groups';
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
                            Groups
                        </h1>
						<ol class="breadcrumb">
					  <li><a href="#">Home</a></li>
					  <li><a href="#">Groups</a></li>
					</ol>		
		</div>
		<input type="hidden" id="hid" name="hid" value="<?php echo $gid;?>">
            <div id="page-inner">
			    
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
<div class="err-msg" id="err_id"></div>	
			<?php if(isset($_REQUEST['gid']) || isset($_REQUEST['action']))
			{
				?>
				<div class="col-lg-3 col-md-3 col-sm-3"></div>
				<div class="col-lg-6 col-md-6 col-sm-6 table-bg">
                <div class="form-group">
                    <label>Group Name</label>				
					<input type="text" id="gname" class="form-control" name="gname" value="<?php echo $gname;?>">
				</div>
                <div class="form-group">
                    <label>Teamleader</label>				
					<select id="teamleader" name="teamleader" class="form-control"  > 
                 
					<?php $qry="select * from sv_user_profile where role=1 ORDER BY signup_id  DESC";
$res=mysql_query($qry);
while($row=mysql_fetch_array($res))
{?>
<option value="<?php echo $row['signup_id'];?>" <?php if($teamleader==$row['signup_id']){ echo 'selected="selected"';}?>><?php echo $row['user_name'];?></option>
<?php
}
?>		 
				</select>
				</div>
                <div class="form-group">
                    <label>Employees</label>				
					<select id="eid" name="eid" class="form-control" multiple="multiple" size="5" > 
                 
					<?php $qry="select * from sv_user_profile where role=1 ORDER BY signup_id  DESC";
$res=mysql_query($qry);
while($row=mysql_fetch_array($res))
{?>
<option value="<?php echo $row['signup_id'];?>" <?php if(in_array($row['signup_id'],$earr)){ echo 'selected="selected"';}?>><?php echo $row['user_name'];?></option>
<?php
}
?>		 
				</select>
				</div>
				<div class="text-center">
					<?php if(isset($_REQUEST['gid'])) { ?>
					<button type="button" class="btn btn-primary" onclick="javascript:groups_funct('update')">Update</button>
					<?php } else { ?>
					<button type="button" class="btn btn-primary" onclick="javascript:groups_funct('add')">Add</button>
					<?php } ?>
				</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3"></div>
            <?php
			}
			?>
					</div>
			

		
            <div id="page-inner"> 
               
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Groups
                             <div style="float:right">
                            <a href="groups.php?action=add" class="btn btn-success" style="padding:5px 12px;" >Add Group</a>
                            
                             </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>GNo</th>
											<th>Group Name</th>
                                            <!--<th>Emp Id's</th>-->
											<th>Update</th>
											<th>Delete</th>	
                                        </tr>
                                    </thead>
									<tbody>
									<?php
										$sno=0;
										$res=mysql_query("select * from sv_groups ORDER BY gid DESC");
										while($row=mysql_fetch_array($res))
										{
											$sno++;
											$gid=mysql_real_escape_string($row['gid']);				
											$gname=mysql_real_escape_string($row['gname']);	
											$eid=mysql_real_escape_string($row['eid']);
											$trow=mysql_fetch_array(mysql_query('select user_name from sv_user_profile where signup_id='.$row['teamleader']));
											$teamleader=$trow['user_name'];				
									?> 
									
										<tr>
											<td><?php echo $sno; ?></td>
											<td><?php echo $gname; ?></td>
                                            <td><?php echo $teamleader; ?></td>
                                            <?php /*?><td><?php echo $eid; ?></td><?php */?>
											<td><a href="groups.php?gid=<?php echo $gid;?>"><img src="images/file_edit.png"  alt="update" title="update" ></a></td>
											<td><a href="javascript:groups_del('<?php echo $gid;?>');"><img src="images/delete.png" alt="" title="delete"></a></td>
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
