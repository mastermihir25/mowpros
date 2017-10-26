<?php	
	@session_start();
	include("../connection.php");
	$user = mysql_real_escape_string($_REQUEST['user']);
	//$result=mysql_query("select * from sv_admin_login where user_name = '$user'");
	$result=mysql_query("select * from sv_user_profile where user_name = '$user'");
	$row = mysql_fetch_array($result);
	$email_id=mysql_real_escape_string($row['email_id']);
	$rowcount = mysql_num_rows($result);
	//email id not match then.
	if ($rowcount== 0)
	{
     echo "Invalid User";
	}	
	/*Checking start*/
	else
	{			
					//set the random id length 
					$random_id_length = 8;
					//generate a random id encrypt it and store it in $rnd_id 
					$rnd_id = crypt(uniqid(rand(),1));
					//to remove any slashes that might have come 
					$rnd_id = strip_tags(stripslashes($rnd_id));
					//Removing any . or / and reversing the string 
					$rnd_id = str_replace(".","",$rnd_id); 
					$rnd_id = strrev(str_replace("/","",$rnd_id));
					//finally I take the first 8 characters from the $rnd_id 
					$rnd_id = substr($rnd_id,0,$random_id_length);
					$pas=md5($rnd_id);
					//if(mysql_query("update sv_admin_login set password='$pas' where user_name='$user'"))
					if(mysql_query("update sv_user_profile set password='$pas' where user_name='$user'"))
					{
						if($email_id!='')
						{
							$query1=mysql_fetch_array(mysql_query("select * from sv_admin_login"));	
							$logo=mysql_real_escape_string($query1['logo']);
							$imgSrc=$query1['site_url']."/admincp/admin-logo/$logo";							
								//$imgSrc   = $query1['logo'];
								$site_name = mysql_real_escape_string($query1['site_name']);
								$subject = 'Password Details'; 
								$user_name = mysql_real_escape_string($user); 
								$randam_no = mysql_real_escape_string($rnd_id); 
								$message = '<!DOCTYPE HTML>'. 
								'<head>'. 
								'<meta http-equiv="content-type" content="text/html">'. 
								
							'</head>'. 
							'<body>'. 
							'<div id="header" style="width: 80%;height: 60px;margin: 0 auto;padding: 10px;color: #fff;text-align: center;background-color: #E0E0E0;font-family: Open Sans,Arial,sans-serif;">'. 
							'<img height="50" width="220" style="border-width:0" src="'.$imgSrc.'" title="'.$site_name.'">'. 
							'</div>'. 

							'<div id="outer" style="width: 80%;margin: 0 auto;margin-top: 10px;">'.  
							'<div id="inner" style="width: 78%;margin: 0 auto;background-color: #fff;font-family: Open Sans,Arial,sans-serif;font-size: 13px;font-weight: normal;line-height: 1.4em;color: #444;margin-top: 10px;">'. 
							      '<p>'.$site_name.' - '.$subject.'</p>'.
					       '<p> User Name - '.$user_name.'</p>'. 
   					       '<p> Password - '.$randam_no.'</p>'. 
							'</div>'.   
						'</div>'. 

			'<div id="footer" style="width: 80%;height: 40px;margin: 0 auto;text-align: center;padding: 10px;font-family: Verdena;background-color: #E2E2E2;">'. 
						  'All rights reserved @ '.$site_name. 
					'</div>'. 
				'</body>'; 

			/*EMAIL TEMPLATE ENDS*/ 

			$to      = mysql_real_escape_string($email_id);              // give to email address 
			$subject = 'Account Details - '.$site_name;  //change subject of email 
			$from    = mysql_real_escape_string($query1['email_id']);                              // give from email address 

			// mandatory headers for email message, change if you need something different in your setting. 
			$headers  = "From: " . $from . "\r\n"; 

			$headers .= "MIME-Version: 1.0\r\n"; 
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n"; 

			// Remember, mail function may not work in PHP localhost setup but the email template can be used anywhere like (PHPmailer, swiftmailer, PHPMail classes etc.) 
			// Sending mail 
			if(mail($to, $subject, $message, $headers)) 
			{ 
				echo 'Mail Send'; 
			} 
		}
						
}
    								
	}		
?>