<?php include("../connection.php"); ?>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>



<body class="splash-index">
   
<?php include("top_menu.php") ?>

 <?php include("side_menu.php") ?>

<div id="page-wrapper" >
		
		  <div class="header"> 
                        <h1 class="page-header">
                            Services Stop
                        </h1>
						<ol class="breadcrumb">
					  <li><a href="#">Home</a></li>
					  <li><a href="#">Services Stop</a></li>
					  
					</ol>		
		</div>
            <div id="">
			    <div class="panel-body">
				<div class="text-center">
				
				<div class="col-lg-2 col-md-2 col-sm-2"></div>
				</div>

			<div id="page-inner"> 
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Services Stop
                        </div>
                        <script language="javascript">
						function mainservicestatus(oid,status)
						{
							var confirmation = confirm("Are you sure to De-active this service ?");
		
							if(confirmation)
							{
								//alert(oid);
								var stval=0;
								if(status==false)
								{
									stval=1;
								}
								//alert(stval);
								var url="ajax_changestatus.php";
								xmlHttp.onreadystatechange=function () 
								{
									if (xmlHttp.readyState==4 && xmlHttp.status==200) 
									{
										var msg=xmlHttp.responseText;
										//alert(msg);
										if(msg==1)
										{
											alert("Service Status Changed Successfully.");
										}
									}
								}
								xmlHttp.open("post",url,true);
								xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
								xmlHttp.send("oid="+oid+"&stval="+stval+"&action=mainchange");
							}
						}
						function updateservices(oid,price,status)
						{
							alert(oid);
							alert(price);
							alert(status)
							var stval='Active';
							if(status==false)
							{
								stval='De-active';
							}
							var service="";
							 var checks = document.getElementsByName("service["+oid+"][]");
							for (var i=0; i < checks.length; i++) {
								if(checks[i].checked == true)
								{
									service+=checks[i].value+",";
								}
							}
							service=service.slice(0, -1);
							alert(service);
							var confirmation = confirm("Are you sure to "+stval+" this service ?");
		
							if(confirmation)
							{
								var url="ajax_changestatus.php";
								xmlHttp.onreadystatechange=function () 
								{
									if (xmlHttp.readyState==4 && xmlHttp.status==200) 
									{
										var msg=xmlHttp.responseText;
										//alert(msg);
										if(msg==1)
										{
											alert("Service Status Changed Successfully.");
										}
									}
								}
								xmlHttp.open("post",url,true);
								xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
								xmlHttp.send("oid="+oid+"&service="+service+"&price="+price+"&stval="+stval+"&action=subchange");
							}
						}
						</script>
                        <div class="panel-body">
                            <div class="table-responsive" id="customer_table">
                                <table class="table table-striped table-bordered table-hover"  id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Sno</th>
                                            <th>Address</th>
											<th>Services Stop</th>
                                        </tr>
                                    </thead>
									<tbody>
									<?php 
										
									?>
									<?php		
									$sno=0;
									if($_SESSION['role']==1 || $_SESSION['role']==2)
									{
									$res=mysql_query("select p.signup_id,p.user_name,p.firstname,p.lastname,p.email_id,p.phone_no,p.ref_by,o.order_id,o.address,o.city,o.state,o.services,o.post_code,o.lotsize,o.price,o.tax,o.main_service from sv_user_profile p,sv_user_order o where p.signup_id=o.uid ORDER BY signup_id  DESC");
									}
									else
									{
										$res=mysql_query("select p.signup_id,p.user_name,p.firstname,p.lastname,p.email_id,p.phone_no,p.ref_by,o.order_id,o.address,o.city,o.state,o.services,o.post_code,o.lotsize,o.price,o.tax,o.main_service from sv_user_profile p,sv_user_order o where p.signup_id=o.uid and p.signup_id='".$_SESSION['admin_id']."' ORDER BY p.signup_id DESC");
									}
									while($row=mysql_fetch_array($res))
									{
										$sno++;
										$selectedValues = explode(",",$row['services']);
																						
									?>
										<tr>
											<td><?php echo $sno; ?></td>
											<td><?php echo $row['address']; ?></td>
											<?php /*?><td><?php echo $row['services']; ?></td><?php */?>
											<td><input onClick="mainservicestatus(<?php echo $row['order_id'];?>,$(this).prop('checked'))" 	class="custom-select-option-checkbox" type="checkbox"	name="mainservice"  value="<?php echo $row['order_id'];?>" <?php if($row['main_service']==0){ echo 'checked="checked"';}?>>$<?php echo $row['price']; ?>.00 Weekly (+$<?php echo $row['tax']; ?> tax)<br />
												<?php $qry="select sb.sid,s.services_id,s.services_name,sb.services_sub_name,sb.price from sv_services_sub as sb left join sv_services as s on sb.services_name=s.services_id";
												$sres=mysql_query($qry);
												while($srow=mysql_fetch_array($sres))
												{
													?>
												<div class="custom-select-option" id="serviceslist">
													<input onChange="updateservices(<?php echo $row['order_id'];?>,<?php echo $srow['price'];?>,$(this).prop('checked'))"
														class="custom-select-option-checkbox" type="checkbox"	name="service[<?php echo $row['order_id'];?>][]"  value="<?php echo $srow['sid'];?>" <?php if(in_array($srow['sid'],$selectedValues)){ echo 'checked="checked"';}?>> <?php echo $srow['services_name'];?>(<?php echo $srow['services_sub_name'];?>)
												</div>
												<?php
												
												}
												?>
											</td>
										</tr>
									<?php } ?>		
									</tbody>
															
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
                <!-- /. ROW  -->
                   </div>
				   				<?php include("footer.php") ?>

    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->	   
    
</body>
   </html>
   
<script language="javascript">
				function getprice(str,action)
				{
					//alert(action);
					//var sp=parseInt(document.getElementById('serviceprice').value);
					 
					if(str!='')
					{
						var url="ajax_getprice.php";
						xmlHttp.onreadystatechange=function () 
						{
							if (xmlHttp.readyState==4 && xmlHttp.status==200) 
							{
								var msg=xmlHttp.responseText;
								//alert(msg);
								var arr=msg.split("#");
								
								if(action=='remove' || action==false)
								{
									var totalsp=sp-parseInt(arr[1]);
									//alert(totalsp);
									//document.getElementById('serviceprice').value=totalsp;
									
									var servicelist=document.getElementById("serviceslist").innerHTML;
									servicelist=servicelist.replace(arr[0]+" [$"+arr[1]+"]&nbsp;&nbsp;", "");
									document.getElementById("serviceslist").innerHTML=servicelist;
									
								}
								else
								{
									var totalsp=sp+parseInt(arr[1]);
								//alert(totalsp);
								//document.getElementById('serviceprice').value=totalsp;
								document.getElementById("serviceslist").innerHTML+=arr[0]+" [$"+arr[1]+"]&nbsp;&nbsp;";
									
								}
							}
						} 		
						xmlHttp.open("post",url,true);
						xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						xmlHttp.send("id="+str);
					}
					
				}
</script>
<script>
	$("#custom-select").on("click", function() {
		$("#custom-select-option-box").toggle();
	});
	function toggleFillColor(obj) {
		$("#custom-select-option-box").show();
		if ($(obj).prop('checked') == true) {
			$(obj).parent().css("background", '#FFF');
		} else {
			$(obj).parent().css("background", '#FFF');
		}
	}
	$(".custom-select-option").on("click", function(e) {
		var checkboxObj = $(this).children("input");
		if ($(e.target).attr("class") != "custom-select-option-checkbox") {
			if ($(checkboxObj).prop('checked') == true) {
				$(checkboxObj).prop('checked', false);
				getprice($(checkboxObj).val(),'remove');
			} else {
				$(checkboxObj).prop("checked", true);
				getprice($(checkboxObj).val(),'add');
			}
		}
		toggleFillColor(checkboxObj);
		 
		
	});

	$("body")
			.on(
					"click",
					function(e) {
						if (e.target.id != "custom-select"
								&& $(e.target).attr("class") != "custom-select-option") {
							$("#custom-select-option-box").hide();
						}
					});
</script>