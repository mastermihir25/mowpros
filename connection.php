<?php if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
//if you got any deprecated error in above php 5.5 version just uncomment below line to hide those warnings
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

date_default_timezone_set('Asia/Kolkata');
	$con=mysql_connect("localhost","avani","avani");
	if(!$con)
		die('Could not connect: ' . mysql_error());
	mysql_select_db("mowpros",$con);
?>
