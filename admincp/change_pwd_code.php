<?php
	include("../connection.php");
	@session_start();
	/*user not login and access this page it will going to index page*/
	if(!isset($_SESSION['user_name']))
		header("Location: index.php");
	/*user after login access this page*/
	else
	{
		$curpwd=mysql_real_escape_string($_REQUEST['curpwd']);
		$new=mysql_real_escape_string($_REQUEST['newpwd']);
		$conpwd=mysql_real_escape_string($_REQUEST['conpwd']);
		$user_name = mysql_real_escape_string($_SESSION["user_name"]);
		$genre = mysql_real_escape_string(md5($curpwd));
		$new_pass = mysql_real_escape_string(md5($new));
		//$result = mysql_query("SELECT * FROM sv_admin_login where user_name='$user_name' and password='$genre'");
		$result = mysql_query("SELECT * FROM sv_user_profile where user_name='$user_name' and password='$genre'");
		$row=mysql_fetch_array($result);
		if($row=="")
			echo "Invalid";
		else
		{
			//mysql_query("UPDATE sv_admin_login SET password='$new_pass' where user_name='$user_name'");
			mysql_query("UPDATE sv_user_profile SET password='$new_pass' where user_name='$user_name'");
				echo "success";
		}
	}
?>