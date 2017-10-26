<?php
include('../connection.php');
@session_start();
$user=$_SESSION['user_name'];
$dat=mysql_real_escape_string(date('Y-m-d H:i:s'));

$email_id=mysql_real_escape_string($_POST['email_id']);
$admin_name=mysql_real_escape_string($_POST['admin_name']);
$site_name=mysql_real_escape_string($_POST['site_name']);
//$logo=$_POST['logo'];
//$favicon=$_POST['favicon'];
$site_desc=mysql_real_escape_string($_POST['site_desc']);
$keyword=mysql_real_escape_string($_POST['keyword']);
$site_url=mysql_real_escape_string($_POST['site_url']);

$paypal_id=mysql_real_escape_string($_POST['paypal_id']);
$cmode=mysql_real_escape_string($_POST['cmode']);
$smode=mysql_real_escape_string($_POST['smode']);

$res=mysql_query("select * from sv_admin_login");
$row=mysql_num_rows($res);

if($row==0)
{
	$file_name = $_FILES['logo']['name'];
	$random_digit=rand(0000,9999);
	if($file_name!="")
	{		
		$file_name= $random_digit.$file_name;
 		$path= "admin-logo/" .$file_name;
		move_uploaded_file($_FILES['logo']["tmp_name"],$path);
	}
	else 
		$file_name="";
	
	$file_name1 = $_FILES['favicon']['name'];
	$random_digit1=rand(0000,9999);
	if($file_name1!="")
	{		
		$file_name1= $random_digit1.$file_name1;
 		$path= "admin-logo/" .$file_name1;
		move_uploaded_file($_FILES['favicon']["tmp_name"],$path);
	}
	else 
		$file_name1="";
	
	if(mysql_query("insert into sv_admin_login(email_id,user_name,site_name,logo,site_desc,keyword,favicon,site_url,paypal_id,currency_mode,paypal_site_mode)values('$email_id','$admin_name','$site_name','$file_name','$site_desc','$keyword','$file_name1','$site_url','$paypal_id','$cmode','$smode')"))
		echo "Inserted";
	else
		echo "Server Error";
}
else
{
	$fet=mysql_fetch_array($res);
	$id=mysql_real_escape_string($fet['admin_id']);
	//$old_file=$fet['logo'];
	$old_file="admin-logo/".$fet['logo'];
	$file_name = $_FILES['logo']['name'];
	$random_digit=rand(0000,9999);
		if($file_name!="")
		{		
			$file_name= $random_digit.$file_name;
 			$path= "admin-logo/" .$file_name;
			move_uploaded_file($_FILES['logo']["tmp_name"],$path);
			if (file_exists($old_file))
			{
   		 		unlink($old_file);
			}
		}
	 	else 
		{
			//$file_name=$old_file;	
			$old_file=mysql_real_escape_string($fet['logo']);
			$file_name=mysql_real_escape_string($old_file);
		}
	
	//$old_file1=$fet['favicon'];
	$old_file1="admin-logo/".$fet['favicon'];
	$file_name1 = $_FILES['favicon']['name'];
	$random_digit1=rand(0000,9999);
		if($file_name1!="")
		{		
			$file_name1= $random_digit1.$file_name1;
 			$path= "admin-logo/" .$file_name1;
			move_uploaded_file($_FILES['favicon']["tmp_name"],$path);
			if (file_exists($old_file1))
			{
   		 		unlink($old_file1);
			}
		}
	 	else 
		{
			//$file_name1=$old_file1;	
			$old_file1=mysql_real_escape_string($fet['favicon']);
			$file_name1=mysql_real_escape_string($old_file1);
		}
	
		if(mysql_query("update sv_admin_login set email_id='$email_id',user_name='$admin_name',site_name='$site_name',logo='$file_name',site_desc='$site_desc',keyword='$keyword',favicon='$file_name1',site_url='$site_url',paypal_id='$paypal_id',currency_mode='$cmode',paypal_site_mode='$smode' where admin_id='$id'")) 
		?>
		<script type="text/javascript">
		var msg="Updated";
		window.location="setting.php?msg="+msg;				
		</script>
		<?php
		//header('Location:setting.php');
}
?>