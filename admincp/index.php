<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include("../connection.php");
	if(isset($_SESSION['user_name']))
	header("Location:dashboard.php");
?>

<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MowPros</title>
	<!-- Bootstrap Styles-->
     <link href="css/bootstrap.css" rel="stylesheet" />
	<script type="text/javascript" src="../js/bootstrap.min.js"></script>
     <!-- FontAwesome Styles-->
    <link href="css/font-awesome.css" rel="stylesheet" />
     <!-- Morris Chart Styles-->
   
        <!-- Custom Styles-->
    <link href="css/custom-styles.css" rel="stylesheet" />

     <!-- Google Fonts-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
     <!-- TABLE STYLES-->
    <link href="js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
	<script type="text/javascript" src="validation.js"></script>
	    <link href="css/style.css" rel="stylesheet" />

</head>
<style>
body{background-color:#337ab7;}
#page-wrapper{box-shadow:none;}

</style>
				<div class="container">
				<div class="col-lg-4 col-md-4 col-sm-4"></div>
				<div class="col-lg-4 col-md-4 col-sm-4 login text-center">
			   		 <div class="header"> 
					 	<h1>Admin Panel </h1>
					  </div>
						<form action="javascript:login();" method="post" class="text-center">
								<input class="form-control" type="user" name="uname" id="uname" placeholder="Username" data-style="mb:7">
								<input class="form-control" style="margin-top:10px;" type="password" name="pwd" id="pwd" placeholder="Password" data-style="mb:7">
							<br>
								<a href="forgot.php">Forgot My Password</a>

							<?php if(isset($_REQUEST['msg'])) 	
								$msg=$_REQUEST['msg'];
								else
								$msg="";?>
							<div class="err" style="color:red" id="err_id"><?php echo $msg;?></div>	
							<button type="submit" style="margin-top:20px;" class="btn btn-primary">Login to my account</button>
					</form>	   
	   
	   </div>
	   
	   				<div class="col-lg-4 col-md-4 col-sm-4"></div>

	   	</div>
	   
	   </div>
	   
         <!-- /. PAGE   -->
     <!-- /. WRAPPER  -->
    <!-- JS Scripts-->
    <!-- jQuery Js -->
    <script src="js/jquery-1.10.2.js"></script>
      <!-- Bootstrap Js -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Metis Menu Js -->
    <script src="js/jquery.metisMenu.js"></script>
     <!-- DATA TABLE SCRIPTS -->
    <script src="js/dataTables/jquery.dataTables.js"></script>
    <script src="js/dataTables/dataTables.bootstrap.js"></script>
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
    </script>
         <!-- Custom Js -->
    <script src="js/custom-scripts.js"></script>

</html>
