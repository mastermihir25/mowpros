<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include("../connection.php"); 
$page = 'change password';?>

<body>
    <div id="wrapper">
        <?php include("top_menu.php") ?>
        <!--/. NAV TOP  -->
        <?php include("side_menu.php") ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
		  <div class="header"> 
                        <h1 class="page-header">
                            Change Password
                        </h1>
						<ol class="breadcrumb">
					  <li><a href="#">Home</a></li>
					  <li><a href="#">Change Password</a></li>
					</ol>		
		</div>
            <div id="page-inner">
			    <div class="panel-body">
			<div class="text-center">
				<?php
if(isset($_REQUEST['msg']))
{
	$msg=$_REQUEST['msg'];
		if($msg=="Invalid")
		{
		      echo '<div class="err-msg">Enter the valid current password</div>';
		}
		else if($msg=="success")
			{
		      echo '<div class="succ-msg">Password Changed Successfully</div>';		
			}
}

else
	$msg="";
?>
<!--<div class="err-msg" id="err_id"><?php echo $msg;?></div>-->	</div>
				<div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <label>Current Password</label>				
					<input type="password" id="curpwd" class="form-control" name="curpwd" onblur="javascript:currpass(this.value);" value="">
					<div id="curpwd1" class="err" ></div>
				</div>
				 <div class="form-group">
                    <label>New Password</label>				
					<input type="password" id="newpwd" class="form-control" name="newpwd" onblur="javascript:newpwd(this.value);" value="">
					<div id="newpwd1" class="err" ></div>
				</div>
				 <div class="form-group">
                    <label>Confirm Password</label>				
					<input type="password" id="conpwd" class="form-control" name="conpwd" onblur="javascript:conpwd(this.value);" value="">
					<div id="conpwd1" class="err" ></div>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary" onclick="javascript:change_pass()">Set New Password</button>
				</div>
					</div>
					</div>
			
            <div id="page-inner"> 
               
            
                <!-- /. ROW  -->
                   </div>
				   				<?php include("footer.php") ?>

    </div>
             <!-- /. PAGE INNER  -->
            </div>
       
</body>

</html>
