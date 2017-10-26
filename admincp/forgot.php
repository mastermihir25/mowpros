<!DOCTYPE html>
<html>


<head>
	<title>Framework | Forgot Password Form</title>
	<meta name="robots" content="noindex, follow" />
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1" />
    <link href="css/bootstrap.css" rel="stylesheet" />
	<script type="text/javascript" src="../js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="css/framework-all.css">
    <link rel="shortcut icon" href="favicon.ico" />

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/framework.js"></script>
	<script type="text/javascript" src="validation.js"></script>
		    <link href="css/style.css" rel="stylesheet" />

</head>
<body style="background-color:#337ab7" class="" data-style="bg:#1F2C36" style="overflow:auto; height:100%">
<?php
if(isset($_REQUEST['msg']))
	$msg=$_REQUEST['msg'];
else
	$msg="";
?>

<div class="container">
	<div class="col-lg-4 col-md-4 col-sm-4"></div>
	<div class="col-lg-4 col-md-4 col-sm-4 login text-center">
<form class="form" action="javascript:forgot();">
	<h3 class="txt-center">FORGOT MY PASSWORD</h3>

	<input class="form-control" type="" name="user" id="user" placeholder="User Name" data-style="mb:7">
	<div class="err" style="color:red;" id="err_id"></div>

	<button class="btn btn-primary txt-center" style="margin-top:10px;" type="submit" >Reset</button>
	<div class="err-msg" id="err_id"><?php echo $msg;?></div>
	<p data-style="mt:20" style="padding-top:10px;">Remembered your password ? <a href="index.php">Go Back</a></p>
</form>
</div>
				<div class="col-lg-4 col-md-4 col-sm-4"></div>

</div>

</body>


</html>