<?php include("../connection.php");
				
// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');
	
// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

 
$res=mysql_query("select signup_id,user_name,firstname,lastname,ref_by,email_id,p.phone_no,order_id,o.address,o.city,state,post_code,o.date,lotsize,price,tax,payment_status,startweek,cleanup_req,gatecode,notes,cardno,exp_month,exp_year,terms,bfirstname,blastname,baddress,bcity,bstate,bzip from sv_user_profile as p,sv_user_order as o where signup_id=uid ORDER BY signup_id"); 
$headerinfo = 'Id,Username,Firstname,Lastname,Reference By,Email_id,Phone No,Order Id,Address,City,State,Zip,Date,Lotsize,Price,Tax,Payment Status,Startweek,Cleanup Required,Gatecode,Notes,Cardno,Exp Month,Exp Year,Terms,Bill Firstname,Bill Lastname,Bill Address,Bill City,Bill State,Bill Zip';
if(mysql_num_rows($res)>0)
{
	fputcsv($output,explode(',',$headerinfo));

	while($row=mysql_fetch_assoc($res))
	{ 
		//unset($row['password']);
		fputcsv($output, $row);
	}
}


 ?>  