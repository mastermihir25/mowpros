var xmlHttp
xmlHttp=GetXmlHttpObject()

//Login Function Start Here
function login(str)
{
	var msg="";
	var uname=document.getElementById("uname").value;
	var pwd=document.getElementById("pwd").value;
	if(uname=="" && pwd=="")
		msg="Enter User name and Password";
	else if(uname!="" && pwd=="")
		msg="Enter Password";
	else if(uname=="" && pwd!="")
		msg="Enter User Name";
	else
		msg="";		
	if(msg=="")
	{
		var url="login_code.php?uname="+uname+"&pwd="+pwd;
		xmlHttp.onreadystatechange=function()
 		{	   
			if(xmlHttp.readyState==4 && xmlHttp.status==200)
  			{
  				var msg=xmlHttp.responseText.trim();
				 
  				if(msg=="welcome")
  					window.location="dashboard.php";
  				else
					document.getElementById("err_id").innerHTML=msg;
			}
		}
		xmlHttp.open("POST",url,true);
		xmlHttp.send(null);
	}
	else
		document.getElementById("err_id").innerHTML=msg;
}
//Forgot Password Start Here
function forgot()
{	
	var error="";
	var user=document.getElementById("user").value;
	if(user=="")
		document.getElementById("err_id").innerHTML="Enter User Name";
	else
	{
		document.getElementById("err_id").innerHTML="";
		var url="forget_password_code.php?user="+user;	
		xmlHttp.onreadystatechange=function()
		{		
			if(xmlHttp.readyState==4 && xmlHttp.status==200)
			{	
				var msg=xmlHttp.responseText.trim();
				window.location="forgot.php?msg="+msg;	
				//document.getElementById("err_id").innerHTML=msg;	
			}
		}
	}
	xmlHttp.open("POST",url,true);
	xmlHttp.send(null);
}
//Change password start here
// Validation for current password
function currpass(str)
{
	 var error="";
    if(str.length==0)
    {
		error="* Enter Current Password";
	    document.getElementById("curpwd1").innerHTML="Enter Current Password";
    }
    else
		document.getElementById("curpwd1").innerHTML=""; 
	 return error;
}
function newpass(str)
{	
    var error="";
    if(str.length==0)
    {
		error="Enter New Password";
	    document.getElementById("newpwd1").innerHTML="Enter New Password";
    }
	
	 else
		document.getElementById("newpwd1").innerHTML=""; 
	 return error;
}
 //Validation for Match Password & Confirm Password
function conpass(str)
{
	var error="";
	if (str.length==0)
   {
		error="Enter Confirm Password";
    	document.getElementById("conpwd1").innerHTML="Enter Confirm Password";
	} 
	
	else
		document.getElementById("conpwd1").innerHTML="";
	return error;
}
function change_pass()
{
	var msg="";
	var curpwd=document.getElementById("curpwd").value;
	var newpwd=document.getElementById("newpwd").value;
	var conpwd=document.getElementById("conpwd").value;
	msg+=currpass(curpwd);
	msg+=newpass(newpwd);
	msg+=conpass(conpwd);
	if (newpwd != conpwd) 
	{
        alert("Passwords do not match.");
     }
	 else{
	if(msg=="")
	{
		var url="change_pwd_code.php?curpwd="+curpwd+"&newpwd="+newpwd+"&conpwd="+conpwd;
		xmlHttp.onreadystatechange=function () 
		{
			if (xmlHttp.readyState==4 && xmlHttp.status==200) 
			{
				var msg=xmlHttp.responseText.trim();
				if(msg=="success")
				{
					var msg="success";
					window.location="change_pwd.php?msg="+msg;				
				}
				else if(msg=="Invalid")
				{
					var msg="Invalid";
					window.location="change_pwd.php?msg="+msg;	
				}
			}
		} 		
		xmlHttp.open("post",url,true);
		xmlHttp.send(null);
	}
	}
}

function checkFile(str)
{
	var error="";		
		var file = document.getElementById("logo").files[0];
		var file12 = document.getElementById("logo").files[0].size;		
		var filename = file.name;
		var fileSize = file.size / 1024 / 1024;
		
		var dotPosition = filename.lastIndexOf(".");
        var FileExt = filename.substring(dotPosition);
		
		var FileExt=FileExt.toLowerCase();
		var allow=3/1024/1024;
		if ( (FileExt != ".jpg" && FileExt != ".jpeg" && FileExt != ".png"))
        {
		
            //var error = "File type : "+ FileExt+"\n\n";
			 var error = "";
            //error += "Size: " + fileSize + " MB \n\n";
            error += "select only png,jpg or jpeg image format";
			document.getElementById("logo_err").innerHTML=error;
		}
		else if(file12 > 1048576)
		{
			error="only allowed less than 1 MB";
			document.getElementById("logo_err").innerHTML=error;
		}
		else if(fileSize <allow)
		{
			error="only allowed less than 1 MB";
			alert(error);
			document.getElementById("logo_err").innerHTML=error;
		}	
	
 return error;
 }
 function checkFile1(str)
{
	var error="";		
		var file = document.getElementById("favicon").files[0];
		var file12 = document.getElementById("favicon").files[0].size;		
		var filename = file.name;
		var fileSize = file.size / 1024 / 1024;
		
		var dotPosition = filename.lastIndexOf(".");
        var FileExt = filename.substring(dotPosition);
		
		var FileExt=FileExt.toLowerCase();
		var allow=3/1024/1024;
		if ( (FileExt != ".jpg" && FileExt != ".jpeg" && FileExt != ".png" && FileExt != ".ico"))
        {
            //var error = "File type : "+ FileExt+"\n\n";
			 var error = "";
            //error += "Size: " + fileSize + " MB \n\n";
            error += "select only png,jpg,jpeg & ico image format";
			document.getElementById("favicon_err").innerHTML=error;
		}
		else if(file12 > 1048576)
		{
			error="only allowed less than 1 MB";
			document.getElementById("favicon_err").innerHTML=error;
		}
		else if(fileSize <allow)
		{
			error="only allowed less than 1 MB";
			alert(error);
			document.getElementById("favicon_err").innerHTML=error;
		}	
	
 return error;
 } 
 function adminname(str)
{
	var error="";	
	if(str=="")
	{
		
		var error="Enter User Name";		
		document.getElementById("admin_name_err").innerHTML=error;
	}	
	else
	{
		error="";
		document.getElementById("admin_name_err").innerHTML="";
	}
return error;
 }
 function emailid(str)
{
	var error="";	
	if(str=="")
	{
		var error="Enter Email";
		document.getElementById("email_err").innerHTML="Enter Email";
	}	
	else
	{
		error="";
		document.getElementById("email_err").innerHTML="";
	}
return error;
 }
 function sitename(str)
{
	var error="";	
	if(str=="")
	{
		var error="Enter Site Name";
		document.getElementById("site_name_err").innerHTML="Enter Site Name";
	}	
	else
	{
		error="";
		document.getElementById("site_name_err").innerHTML="";
	}
return error;
 }
 function sitedesc(str)
{
	var error="";	
	if(str=="")
	{
		var error="Enter Site Description";
		document.getElementById("site_desc_err").innerHTML="Enter Site Description";
	}	
	else
	{
		error="";
		document.getElementById("site_desc_err").innerHTML="";
	}
return error;
 }
 function sitekwd(str)
{
	var error="";	
	if(str=="")
	{
		var error="Enter Site Keyword";
		document.getElementById("site_kwd_err").innerHTML="Enter Site Keyword";
	}	
	else
	{
		error="";
		document.getElementById("site_kwd_err").innerHTML="";
	}
return error;
 }
 function siteurl(str)
{
	var error="";	
	if(str=="")
	{
		var error="Enter Site URL";
		document.getElementById("site_url_err").innerHTML="Enter Site URL";
	}	
	else
	{
		error="";
		document.getElementById("site_url_err").innerHTML="";
	}
return error;
 }
 
 
 function admin_email()
{	
	var msg="";
	var admin_name=document.getElementById("admin_name").value;
	var email=document.getElementById("email_id").value;
	var site_name=document.getElementById("site_name").value;
	var site_desc=document.getElementById("site_desc").value;
	var site_kwd=document.getElementById("keyword").value;
	var site_url=document.getElementById("site_url").value;
	
	var file_name=document.getElementById("logo").value;
	var file_name1=document.getElementById("favicon").value;
	
	var paypal_id=document.getElementById("paypal_id").value;
	var cmode=document.getElementById("cmode").value;
	var smode=document.getElementById("smode").value;

	msg+=adminname(admin_name);
	msg+=emailid(email);
	msg+=sitename(site_name);
	msg+=sitedesc(site_desc);
	msg+=siteurl(site_url);
	
	//msg+=checkFile(file_name);
	//msg+=checkFile1(file_name1);
	if(file_name!=="" && file_name1=="")
		msg+=checkFile(file_name);
	else if(file_name1!=="" && file_name=="")
		msg+=checkFile1(file_name1);
	else if(file_name!=="" && file_name1!=="")
	{
		msg+=checkFile(file_name);
		msg+=checkFile1(file_name1);
	}
	
	
	if(msg=="")
	{
		document.getElementById("admin_s").submit();
	}
}

//Postal Code Function Start Here	
function postal_code_funct(str)
{	
	var pcode=document.getElementById("pcode").value;	
	
	if(str=="add")
	{
		if(pcode=="")
		{
			msg="Enter Postal Code";	
			alert(msg);
		}
		else
		{ var url="postal_code_add.php?pcode="+pcode+"&action="+str;}
	}
	else if(str=="update") 
	{
		if(pcode=="")
		{
			msg="Enter Postal Code";	
			alert(msg);
		}
		else {
		var hid=document.getElementById("hid").value;
		var url="postal_code_add.php?pcode="+pcode+"&action="+str+"&hid="+hid;}
		
	}	
		xmlHttp.onreadystatechange=function()
 	        {	   
				if(xmlHttp.readyState==4 && xmlHttp.status==200)
  				{
  					var msg=xmlHttp.responseText.trim();  					
					if(msg=="Inserted")
					{				
						var msg="Inserted";
						//document.getElementById("err_id").innerHTML="* Company Quality Deleted";
						window.location="postal_code.php?msg="+msg;
				
					}
					else if(msg=="Updated")
					{
						var msg="Updated";
						window.location="postal_code.php?msg="+msg;
					}					
				}
			}
			xmlHttp.open("POST",url,true);
			xmlHttp.send(null);
		
}	
function postal_code_del(str)
{
	var r=confirm("Do you want to Delete?");
	if(r==true)
	{
		var url="postal_code_add.php?hid="+str+"&action=delete";
		xmlHttp.onreadystatechange=function()
		{   
			if(xmlHttp.readyState==4 && xmlHttp.status==200)
			{
				var msg=xmlHttp.responseText.trim();
				if(msg=="Deleted")
					{				
						var msg="Deleted";
						window.location="postal_code.php?msg="+msg;
					}
			}
		}
		xmlHttp.open("POST",url,true);
		xmlHttp.send(null);
	}
}

//Services Function Start Here	
function services_funct(str)
{	
	var sname=document.getElementById("sname").value;	
	if(str=="add")
	{
		if(sname=="")
		{
			msg="Enter Services Name";	
			alert(msg);
		}
		else
		{ var url="services_add.php?sname="+sname+"&action="+str;}
	}
	else if(str=="update") 
	{
		if(sname=="")
		{
			msg="Enter Services Name";	
			alert(msg);
		}
		else {
		var hid=document.getElementById("hid").value;
		var url="services_add.php?sname="+sname+"&action="+str+"&hid="+hid;	}
	}	
		xmlHttp.onreadystatechange=function()
 	        {	   
				if(xmlHttp.readyState==4 && xmlHttp.status==200)
  				{
  					var msg=xmlHttp.responseText.trim();  					
					if(msg=="Inserted")
					{				
						var msg="Inserted";
						//document.getElementById("err_id").innerHTML="* Company Quality Deleted";
						window.location="services.php?msg="+msg;
				
					}
					else if(msg=="Updated")
					{
						var msg="Updated";
						window.location="services.php?msg="+msg;
					}
					else
					{
						document.getElementById("err_id").innerHTML=msg;
					}
				}
			}
			xmlHttp.open("POST",url,true);
			xmlHttp.send(null);
		
}	
function services_del(str)
{
	var r=confirm("Do you want to Delete?");
	if(r==true)
	{
		var url="services_add.php?hid="+str+"&action=delete";
		xmlHttp.onreadystatechange=function()
		{   
			if(xmlHttp.readyState==4 && xmlHttp.status==200)
			{
				var msg=xmlHttp.responseText.trim();
				if(msg=="Deleted")
					{				
						var msg="Deleted";
						window.location="services.php?msg="+msg;
					}
			}
		}
		xmlHttp.open("POST",url,true);
		xmlHttp.send(null);
	}
}

//Groups Function Start Here	
function groups_funct(str)
{	
	var gname=document.getElementById("gname").value;	
	var teamleader=$('#teamleader').val();
	var eid=$('#eid').val();
	//alert(eid);
	if(str=="add")
	{
		if(gname=="")
		{
			msg="Enter Groups Name";	
			alert(msg);
		}
		else
		{ var url="groups_add.php?gname="+gname+"&teamleader="+teamleader+"&eid="+eid+"&action="+str;}
	}
	else if(str=="update") 
	{
		if(gname=="")
		{
			msg="Enter Groups Name";	
			alert(msg);
		}
		else {
		var hid=document.getElementById("hid").value;
		var url="groups_add.php?gname="+gname+"&teamleader="+teamleader+"&eid="+eid+"&action="+str+"&hid="+hid;	}
	}	
		xmlHttp.onreadystatechange=function()
 	        {	   
				if(xmlHttp.readyState==4 && xmlHttp.status==200)
  				{
  					var msg=xmlHttp.responseText.trim();  					
					if(msg=="Inserted")
					{				
						var msg="Inserted";
						//document.getElementById("err_id").innerHTML="* Company Quality Deleted";
						window.location="groups.php?msg="+msg;
				
					}
					else if(msg=="Updated")
					{
						var msg="Updated";
						window.location="groups.php?msg="+msg;
					}
					else
					{
						document.getElementById("err_id").innerHTML=msg;
					}
				}
			}
			xmlHttp.open("POST",url,true);
			xmlHttp.send(null);
		
}	
function groups_del(str)
{
	var r=confirm("Do you want to Delete?");
	if(r==true)
	{
		var url="groups_add.php?hid="+str+"&action=delete";
		xmlHttp.onreadystatechange=function()
		{   
			if(xmlHttp.readyState==4 && xmlHttp.status==200)
			{
				var msg=xmlHttp.responseText.trim();
				if(msg=="Deleted")
					{				
						var msg="Deleted";
						window.location="groups.php?msg="+msg;
					}
			}
		}
		xmlHttp.open("POST",url,true);
		xmlHttp.send(null);
	}
}

function paymentproceed()
{
	var action='paymentproceed';
	var cardno=document.getElementById("cardno").value;
	var exp_month=document.getElementById("exp_month").value;
	var exp_year=document.getElementById("exp_year").value;
	var cvv=document.getElementById("cvv").value;
	if (document.getElementById('price1').checked) {
	  var price=document.getElementById("price1").value;
	}
	else
	{
	var price=document.getElementById("price").value;
	}
	var tax=document.getElementById("tax").value;
	var serviceprice=document.getElementById("serviceprice").value;
	//alert(price);
	exp_year=exp_year.substr(2, 3);
	if(cardno=="")
	{
		msg="Enter Cardno";	
		alert(msg);
	}
	else if(cvv=='')
	{
		msg="Enter CVV";	
		alert(msg);
	}
	else if(price=="")
	{
		msg="Enter Price";	
		alert(msg);
	}
	else if(tax=="")
	{
		msg="Enter tax";	
		alert(msg);
	}
	else  
	{
		var url="customer_add.php";
	}
	
	xmlHttp.onreadystatechange=function()
	{	   
		if(xmlHttp.readyState==4 && xmlHttp.status==200)
		{
			var msg=xmlHttp.responseText.trim(); 
			//alert(msg);
			console.log(msg);
			if(msg=="pending")
			{				
				alert("Payment can't proceed. Check Card Detail");
			}
			else if(msg=="completed")
			{				
				document.getElementById("payment_status").options[1].selected=true;
				alert("Payment processed successfully");
		
			}
			 
			
		}
	}
	xmlHttp.open("POST",url,true);
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlHttp.send("cardno="+cardno+"&exp_month="+exp_month+"&exp_year="+exp_year+"&cvv="+cvv+"&price="+price+"&tax="+tax+"&serviceprice="+serviceprice+"&action="+action);
}

function customer_funct(str)
{
	var action=str;
	
	//var oid=document.getElementById("oid").value;
	var uid=document.getElementById("uid").value;
	var name=document.getElementById("name").value;	
	var password=document.getElementById("password").value;
	var firstname=document.getElementById("firstname").value;
	var lastname=document.getElementById("lastname").value;
	var email_id=document.getElementById("email_id").value;
	var phone_no=document.getElementById("phone_no").value;
	var ref_by=document.getElementById("ref_by").value;	
	var role=document.getElementById("role").value;	
			
	var address=document.getElementById("address").value;	
	var city=document.getElementById("city").value;	
	var state=document.getElementById("state").value;	
	var post_code=document.getElementById("post_code").value;	
	var phone_no=document.getElementById("phone_no").value;
	var lotsize=document.getElementById("lotsize").value;
	var price=document.getElementById("price").value;
	var tax=document.getElementById("tax").value;
	var startweek=document.getElementById("startweek").value;
	var cleanup_req=document.getElementById("cleanup_req").value;
	//var service=document.getElementById("service").value;
	var service="";
	 var checks = document.getElementsByName("service[]");
    for (var i=0; i < checks.length; i++) {
        if(checks[i].checked == true)
		{
			service+=checks[i].value+",";
		}
    }
	service=service.slice(0, -1);
	//alert(service);
	var serviceprice=document.getElementById("serviceprice").value;
	var gatecode=document.getElementById("gatecode").value;
	//alert(gatecode);
	var notes=document.getElementById("notes").value;
	var cardno=document.getElementById("cardno").value;
	var exp_month=document.getElementById("exp_month").value;
	var exp_year=document.getElementById("exp_year").value;
	var cvv=document.getElementById("cvv").value;
	var payment_status=document.getElementById('payment_status').value;
	var bfirstname=document.getElementById('bfirstname').value;
	var blastname=document.getElementById('blastname').value;
	var baddress=document.getElementById('baddress').value;
	var bcity=document.getElementById('bcity').value;
	var bstate=document.getElementById('bstate').value;
	var bzip=document.getElementById('bzip').value;
	var agree=document.getElementById('agree').value;
	var terms=document.getElementById('terms').value;
	
	var url="customer_add.php";
	
	xmlHttp.onreadystatechange=function()
	{	   
		if(xmlHttp.readyState==4 && xmlHttp.status==200)
		{
			var msg=xmlHttp.responseText.trim(); 
			//alert(msg);
			console.log(msg);
			if(msg=="Inserted")
			{				
				var msg="Inserted Successfully";
				window.location="users.php?msg="+msg;
			}
			else if(msg=="Updated")
			{				
				var msg="Update Successfully";
				//document.getElementById("err_id").innerHTML="* Company Quality Deleted";
				window.location="users.php?msg="+msg;
		
			}
			 
			
		}
	}
	xmlHttp.open("POST",url,true);
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlHttp.send("uid="+uid+"&name="+name+"&password="+password+"&firstname="+firstname+"&lastname="+lastname+"&email_id="+email_id+"&phone_no="+phone_no+"&ref_by="+ref_by+"&role="+role+"&address="+address+"&city="+city+"&state="+state+"&post_code="+post_code+"&service="+service+"&serviceprice="+serviceprice+"&phone_no="+phone_no+"&lotsize="+lotsize+"&price="+price+"&tax="+tax+"&startweek="+startweek+"&cleanup_req="+cleanup_req+"&gatecode="+gatecode+"&notes="+notes+"&cardno="+cardno+"&exp_month="+exp_month+"&exp_year="+exp_year+"&payment_status="+payment_status+"&bfirstname="+bfirstname+"&blastname="+blastname+"&baddress="+baddress+"&bcity="+bcity+"&bstate="+bstate+"&bzip="+bzip+"&agree="+agree+"&terms="+terms+"&action="+action);
}

function order_funct(str)
{
	//alert(str);
	var action=str;
	
	var oid=document.getElementById("oid").value;
	var name=document.getElementById("name").value;	
	var uid=document.getElementById("uid").value;	
	var address=document.getElementById("address").value;	
	var city=document.getElementById("city").value;	
	var state=document.getElementById("state").value;	
	var post_code=document.getElementById("post_code").value;	
	var phone_no=document.getElementById("phone_no").value;
	var lotsize=document.getElementById("lotsize").value;
	var price=document.getElementById("price").value;
	var tax=document.getElementById("tax").value;
	var startweek=document.getElementById("startweek").value;
	var cleanup_req=document.getElementById("cleanup_req").value;
	var notes=document.getElementById("notes").value;
	var cardno=document.getElementById("cardno").value;
	var exp_month=document.getElementById("exp_month").value;
	var exp_year=document.getElementById("exp_year").value;
	var cvv=document.getElementById("cvv").value;
	var payment_status=document.getElementById('payment_status').value;
	var bfirstname=document.getElementById('bfirstname').value;
	var blastname=document.getElementById('blastname').value;
	var baddress=document.getElementById('baddress').value;
	var bcity=document.getElementById('bcity').value;
	var bstate=document.getElementById('bstate').value;
	var bzip=document.getElementById('bzip').value;
	var agree=document.getElementById('agree').value;
	var terms=document.getElementById('terms').value;
	
	var url="order_add.php";
	
	xmlHttp.onreadystatechange=function()
	{	   
		if(xmlHttp.readyState==4 && xmlHttp.status==200)
		{
			var msg=xmlHttp.responseText.trim(); 
			//alert(msg);
			console.log(msg);
			if(msg=="Inserted")
			{				
				var msg="Inserted";
				window.location="order.php?msg="+msg;
			}
			else if(msg=="Updated")
			{				
				var msg="Updated";
				//document.getElementById("err_id").innerHTML="* Company Quality Deleted";
				window.location="order.php";
		
			}
			
		}
	}
	xmlHttp.open("POST",url,true);
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlHttp.send("oid="+oid+"&name="+name+"&uid="+uid+"&address="+address+"&city="+city+"&state="+state+"&post_code="+post_code+"&phone_no="+phone_no+"&lotsize="+lotsize+"&price="+price+"&tax="+tax+"&startweek="+startweek+"&cleanup_req="+cleanup_req+"&notes="+notes+"&cardno="+cardno+"&exp_month="+exp_month+"&exp_year="+exp_year+"&payment_status="+payment_status+"&bfirstname="+bfirstname+"&blastname="+blastname+"&baddress="+baddress+"&bcity="+bcity+"&bstate="+bstate+"&bzip="+bzip+"&agree="+agree+"&terms="+terms+"&action="+action);
	
 
 	 
}


function getpricequote(address,city,state,post_code)
{
	var action='getpricequote';
	if(address=="")
	{
		msg="Enter Address";	
		alert(msg);
	}
	else if(city=='')
	{
		msg="Enter city";	
		alert(msg);
	}
	else if(state=="")
	{
		msg="Enter state";	
		alert(msg);
	}
	else if(post_code=="")
	{
		msg="Enter zip code";	
		alert(msg);
	}
	else  
	{
		var url="order_add.php";
		
		xmlHttp.onreadystatechange=function()
	{	   
		if(xmlHttp.readyState==4 && xmlHttp.status==200)
		{
			var msg=xmlHttp.responseText.trim(); 
			//alert(msg);
			console.log(msg);
			 if(msg=='0#0#0.00#0#0.00')
			 {
				 alert("We are not prividing service in this area");
				 document.getElementById('pricedetail').innerHTML='<input type="text" id="price" required="required" class="form-control" name="price" value="">';
			 }
			 else
			 {
				document.getElementById('pricedetail').innerHTML='<input name="price" id="price" value="" checked="checked" type="radio" onClick="document.getElementById(\'tax\').value=document.getElementById(\'price1tax\').innerHTML;">$<span id="firstprice"></span>.00 Weekly (+$<span id="price1tax"></span> tax)<br /><input name="price" id="price1" value=""  type="radio" onClick="document.getElementById(\'tax\').value=document.getElementById(\'price2tax\').innerHTML;">$<span id="secondprice"></span>.00 Weekly (+$<span id="price2tax"></span> tax)';
					
			var arr=msg.split("#");
				 if(arr[0]!="<b>Fatal error</b>:  Uncaught exception 'Exception' with message 'String could not be parsed as XML' in /home/bhoomi/web/mowpros/admincp/order_add.php:105Stack trace:")
				 {
			document.getElementById('lotsize').value=arr[0];
			document.getElementById('price').value=arr[1];
			document.getElementById('firstprice').innerHTML=arr[1];
			document.getElementById('tax').value=arr[2];
			document.getElementById('price1tax').innerHTML=arr[2];
			document.getElementById('price1').value=arr[3];
			document.getElementById('secondprice').innerHTML=arr[3];
			document.getElementById('price2tax').innerHTML=arr[4];
					 }
			 }
			
			
		}
	}
	xmlHttp.open("POST",url,true);
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlHttp.send("address="+address+"&city="+city+"&state="+state+"&post_code="+post_code+"&action="+action);
	}
	
	
}

//User Function Start Here	
function user_funct(str)
{	
	var name=document.getElementById("name").value;	
	var password='';
	if(typeof document.getElementById("password")!='undefined')
	{
	var password=document.getElementById("password").value;
	}
	var firstname=document.getElementById("firstname").value;	
	var lastname=document.getElementById("lastname").value;	
	var email_id=document.getElementById("email_id").value;	
	var phone_no=document.getElementById("phone_no").value;	
	var ref_by=document.getElementById("ref_by").value;	
	var role=document.getElementById("role").value;	
	/*var city=document.getElementById("city").value;	
	var address=document.getElementById("address").value;	
	var pin_code=document.getElementById("pin_code").value;	
	var gender=document.getElementById("gender").value;	*/

	if(name=="")
	{
			msg="Enter User Name";	
			alert(msg);
	}
	else if(str=='add' && password=='')
	{
		msg="Enter Password";	
			alert(msg);
	}
	else if(email_id=="")
	{
			msg="Enter Email id";	
			alert(msg);
	}
	else if(phone_no=="")
	{
			msg="Enter Phone No";	
			alert(msg);
	}
	else if(str=="add")
	{
		var url="user_add.php?name="+name+"&password="+password+"&firstname="+firstname+"&lastname="+lastname+"&email_id="+email_id+"&phone_no="+phone_no+"&ref_by="+ref_by+"&role="+role+"&action="+str;
		//var url="user_add.php?name="+name+"&email_id="+email_id+"&phone_no="+phone_no+"&city="+city+"&address="+address+"&pin_code="+pin_code+"&gender="+gender+"&action="+str;
	}
	else if(str=="update") 
	{
		var hid=document.getElementById("hid").value;
		//var url="user_add.php?name="+name+"&email_id="+email_id+"&phone_no="+phone_no+"&city="+city+"&address="+address+"&pin_code="+pin_code+"&gender="+gender+"&action="+str+"&hid="+hid;	
		var url="user_add.php?name="+name+"&firstname="+firstname+"&lastname="+lastname+"&email_id="+email_id+"&phone_no="+phone_no+"&ref_by="+ref_by+"&role="+role+"&action="+str+"&hid="+hid;
	}	
		xmlHttp.onreadystatechange=function()
 	        {	   
				if(xmlHttp.readyState==4 && xmlHttp.status==200)
  				{
  					var msg=xmlHttp.responseText.trim(); 
					if(msg=="Inserted")
					{				
						var msg="Inserted";
						window.location="employe.php?msg="+msg;
					}
					if(msg=="Updated")
					{				
						var msg="Updated";
						//document.getElementById("err_id").innerHTML="* Company Quality Deleted";
						window.location="employe.php?msg="+msg;
				
					}
					
				}
			}
			xmlHttp.open("POST",url,true);
			xmlHttp.send(null);
		
}	
function user_del(str)
{
	var r=confirm("Do you want to Delete?");
	if(r==true)
	{
		var url="user_add.php?hid="+str+"&action=delete";
		xmlHttp.onreadystatechange=function()
		{   
			if(xmlHttp.readyState==4 && xmlHttp.status==200)
			{
				var msg=xmlHttp.responseText.trim();
				if(msg=="Deleted")
					{				
						var msg="Deleted";
						window.location="users.php?msg="+msg;
					}
			}
		}
		xmlHttp.open("POST",url,true);
		xmlHttp.send(null);
	}
}
//Services sub Function Start Here	
function subname(str)
{
	var error="";	
	if(str=="")
	{
		error="Enter sub Service Name";
		document.getElementById("sub_name_err").innerHTML=error;
	}	
	else
	{
		error="";
		document.getElementById("sub_name_err").innerHTML="";
	}
return error;
 }
  function snamef(str)
{
	var error="";	
	if(str=="")
	{		
		error="Enter Service Name";		
		document.getElementById("sname_err").innerHTML=error;
	}	
	else
	{
		error="";
		document.getElementById("sname_err").innerHTML="";
	}
return error;
 }
  function pricef(str)
{
	var error="";	
	if(str=="")
	{		
		error="Enter Price";		
		document.getElementById("price_err").innerHTML=error;
	}	
	else
	{
		error="";
		document.getElementById("price_err").innerHTML="";
	}
return error;
 }
 
function services_sub_funct(str)
{	
    var msg="";
	var sname=document.getElementById("sname").value;	
	var sub_sname=document.getElementById("sub_sname").value;	
	var price=document.getElementById("price").value;	
	msg+=snamef(sname);
	msg+=subname(sub_sname);	
	msg+=pricef(price);	
	/*if(sname=="" && sub_sname=="" && price=="")
	{
		msg+=snamef(sname);
		msg+=subname(sub_sname);	
		msg+=pricef(price);	
	}
	else if(sname!="" && sub_sname=="" && price=="")
	{
		msg+=subname(sub_sname);			
	}
	else if(sname=="" && sub_sname!="" && price=="")
	{
		msg+=snamef(sname);
	}
	else if(sname=="" && sub_sname=="" && price!="")
	{
		msg+=pricef(price);	
	}
	else if(sname!="" && sub_sname!="" && price=="")
	{
		msg+=pricef(price);	
	}
	else 
		msg="";*/
	if(msg=="")
	{
		if(str=="add")
		{
			var url="services_sub_add.php?sname="+sname+"&sub_sname="+sub_sname+"&price="+price+"&action="+str;
		}
		else if(str=="update") 
		{
			var hid=document.getElementById("hid").value;
			var url="services_sub_add.php?sname="+sname+"&sub_sname="+sub_sname+"&price="+price+"&action="+str+"&hid="+hid;		
		}
	}	
		xmlHttp.onreadystatechange=function()
 	        {	   
				if(xmlHttp.readyState==4 && xmlHttp.status==200)
  				{
  					var msg=xmlHttp.responseText.trim();  
					if(msg=="Inserted")
					{				
						var msg="Inserted";						
						window.location="services_sub.php?msg="+msg;
				
					}
					else if(msg=="Updated")
					{
						var msg="Updated";
						window.location="services_sub.php?msg="+msg;
					}
					else
					{
						document.getElementById("err_id").innerHTML=msg;
					}
				}
			}
			xmlHttp.open("POST",url,true);
			xmlHttp.send(null);
		
}	
function services_sub_del(str)
{
	var r=confirm("Do you want to Delete?");
	if(r==true)
	{
		var url="services_sub_add.php?hid="+str+"&action=delete";
		xmlHttp.onreadystatechange=function()
		{   
			if(xmlHttp.readyState==4 && xmlHttp.status==200)
			{
				var msg=xmlHttp.responseText.trim();
				if(msg=="Deleted")
					{				
						var msg="Deleted";
						window.location="services_sub.php?msg="+msg;
					}
			}
		}
		xmlHttp.open("POST",url,true);
		xmlHttp.send(null);
	}
}
//Become a cleaner Function Start Here	
function cleaner(str)
{	
	var email=document.getElementById("email").value;
	var cemail=document.getElementById("cemail").value;
	var fname=document.getElementById("fname").value;	
	var lname=document.getElementById("lname").value;
	var mob_no=document.getElementById("mob_no").value;
	var post_code=document.getElementById("post_code").value;
	var exp=document.getElementById("exp").value;
	var paid_work=document.getElementById("paid_work").value;
	var gender=document.getElementById("gender").value;
	var dob=document.getElementById("datepicker").value;
	var nation=document.getElementById("nation").value;
	var address=document.getElementById("address").value;
	var suburb=document.getElementById("suburb").value;
	var abt=document.getElementById("abt").value;
	if(str=="add")
	{
		var hid=document.getElementById("hid").value;
		var url="cleaner_add.php?email="+email+"&cemail="+cemail+"&fname="+fname+"&lname="+lname+"&mob_no="+mob_no+"&post_code="+post_code+"&exp="+exp+"&paid_work="+paid_work+"&gender="+gender+"&dob="+dob+"&nation="+nation+"&address="+address+"&suburb="+suburb+"&abt="+abt+"&action="+str+"&hid="+hid;
	}
	xmlHttp.onreadystatechange=function()
 	        {	   
				if(xmlHttp.readyState==4 && xmlHttp.status==200)
  				{
  					var msg=xmlHttp.responseText.trim();  					
					if(msg=="Updated")
					{				
						var msg="Updated";
						//document.getElementById("err_id").innerHTML="* Order Inserted";
						window.location="cleaner.php?msg="+msg;				
					}					
					else
					{
						document.getElementById("err_id").innerHTML=msg;
					}
				}
			}
			xmlHttp.open("POST",url,true);
			xmlHttp.send(null);
		
}	

function cleaner_del(str)
{
	var r=confirm("Do you want to Delete?");
	if(r==true)
	{
		var url="cleaner_add.php?hid="+str+"&action=delete";
		xmlHttp.onreadystatechange=function()
		{   
			if(xmlHttp.readyState==4 && xmlHttp.status==200)
			{
				var msg=xmlHttp.responseText.trim();
				if(msg=="Deleted")
					{				
						var msg="Deleted";
						window.location="cleaner.php?msg="+msg;
					}
			}
		}
		xmlHttp.open("POST",url,true);
		xmlHttp.send(null);
	}
}

// Pages code Start Here
function pages_funct()
{
	var pname=document.getElementById("pname").value;
	var page_content=document.getElementById("content").value;
	document.getElementById("form_s").submit();
	
}




function GetXmlHttpObject()
{
var xmlHttp=null;
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttp=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    var aVersions = [ "MSXML2.XMLHttp.5.0",
        "MSXML2.XMLHttp.4.0","MSXML2.XMLHttp.3.0",
        "MSXML2.XMLHttp","Msxm12.XMLHTTP","Microsoft.XMLHttp"];

    for (var i = 0; i < aVersions.length; i++) 
	 {
        try {
            var xmlHttp = new ActiveXObject(aVersions[i]);
            return xmlHttp;
            } 
		catch (oError) 
		   {
            //Do nothing
           }
    }
    }
  catch (e)
    {
    }
  }
  if (xmlHttp==null)
  {
  alert ("Your browser does not support AJAX!");
  return;
  } 
return xmlHttp;
}