<?php @session_start();
include("../connection.php");
$user_nam=mysql_real_escape_string($_REQUEST['uname']);
$pwd=mysql_real_escape_string($_REQUEST['pwd']);
$new_pwd=mysql_real_escape_string(md5($pwd));

/*$result=mysql_query("select * from sv_admin_login where user_name='$user_nam' and password='$new_pwd'");
$row=mysql_num_rows($result);
if($row==0)
{
	
		echo "*Invalid username or Password";
	
}
else
{
	$result=mysql_query("select * from sv_admin_login where user_name='$user_nam' and password='$new_pwd'");
	$row=mysql_fetch_array($result);
	$_SESSION['user_nam']=$row['user_name'];
			echo "welcome"; 
		
	}*/
	
	$result2=mysql_query("select * from sv_user_profile where user_name='$user_nam' and password='$new_pwd'");
	$row2num=mysql_num_rows($result2);
	if($row2num>0)
	{
		$row2=mysql_fetch_array($result2);
		$_SESSION['admin_id']=$row2['signup_id'];
		$_SESSION['user_name']=$row2['user_name'];
		
		$_SESSION['role']=$row2['role'];
			echo "welcome"; 
	}
	else
	{
		echo "*Invalid username or Password";
	}
?>