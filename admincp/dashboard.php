<!DOCTYPE html>
 
<?php include("../connection.php");
@session_start();
if(!isset($_SESSION['user_name']))
	header("Location:index.php");
else
{		
	$user_name=mysql_real_escape_string($_SESSION['user_name']);			
	//$res=mysql_fetch_array(mysql_query("select * from sv_admin_login where user_name='$user_name'"));
	//$uname=mysql_real_escape_string($res['user_name']);
	$uname=mysql_real_escape_string($_SESSION['user_name']);		
}	
$page = 'dashboard';
?>


<html xmlns="http://www.w3.org/1999/xhtml">

<body>
    <div id="wrapper">
        <?php include("top_menu.php") ?>
       
	    <!-- /. NAV SIDE  -->
		<?php include("side_menu.php") ?>
	   
		<div id="page-wrapper">
		  <div class="header"> 
                        <h1 class="page-header">
                            Dashboard <small>Summary of your App</small>
                        </h1>
						<ol class="breadcrumb">
					  <li><a href="#">Home</a></li>
					  <li><a href="#">Dashboard</a></li>
					 
					</ol> 
									
		</div>
            <div id="page-inner">

                <!-- /. ROW  -->
				<?php 
				$curr_date=date("Y/m/d");
				$query=mysql_query("select * from sv_user_profile where date='$curr_date'");
				$num=mysql_num_rows($query);
				$query1=mysql_query("select * from sv_user_order where date='$curr_date'");
				$num1=mysql_num_rows($query1);
				$query2=mysql_query("select * from sv_user_profile");
				$num2=mysql_num_rows($query2);
				$query3=mysql_query("select * from sv_user_order");
				$num3=mysql_num_rows($query3);
				?>
                <div class="row">
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder blue">
                            <div class="panel-left pull-left blue">
							<i class="fa fa-user fa-5x" aria-hidden="true"></i>
                                
                            </div>
                             <div class="panel-right">
								<h3><?php echo $num; ?></h3>
                               <strong> Today Customers &nbsp;</strong>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder blue">
                              <div class="panel-left pull-left blue">
                                <i class="fa fa-sort fa-5x"></i>
								</div>
                                
                            <div class="panel-right">
								<h3><?php echo $num1; ?></h3>
                               <strong> Today Services</strong>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder blue">
                            <div class="panel-left pull-left blue">
                                <i class="fa fa-users fa-5x"></i>
                               
                            </div>
                             <div class="panel-right">
								<h3><?php echo $num2; ?></h3>
                               <strong> Total no of Customers</strong>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder blue">
                            <div class="panel-left pull-left blue">
                                <i class="fa fa-shopping-cart fa-5x"></i>
                                
                            </div>
                             <div class="panel-right">
								<h3><?php echo $num3; ?></h3>
                               <strong> Total No of Services</strong>

                            </div>
                        </div>
                    </div>
                </div>
			
			
			<!DOCTYPE HTML>
<?php /*?><html>
<?php

 include("../connection.php");
$curr_date=date("Y-m-d");

$last_date1=date('Y-m-d',strtotime("-1 days"));
$last_date2=date('Y-m-d',strtotime("-2 days"));
$last_date3=date("Y-m-d", strtotime("-3 days"));
$last_date4=date("Y-m-d", strtotime("-4 days"));
$last_date5=date("Y-m-d", strtotime("-5 days"));
$last_date6=date("Y-m-d", strtotime("-6 days"));


$date1=mysql_num_rows(mysql_query("select * from sv_user_order where date='$curr_date'"));
$date2=mysql_num_rows(mysql_query("select * from sv_user_order where date='$last_date1'"));
$date3=mysql_num_rows(mysql_query("select * from sv_user_order where date='$last_date2'"));
$date4=mysql_num_rows(mysql_query("select * from sv_user_order where date='$last_date3'"));
$date5=mysql_num_rows(mysql_query("select * from sv_user_order where date='$last_date4'"));
$date6=mysql_num_rows(mysql_query("select * from sv_user_order where date='$last_date5'"));
$date7=mysql_num_rows(mysql_query("select * from sv_user_order where date='$last_date6'"));

$javas="{ label: '$last_date6', y: $date7 },";
$javas.="{ label: '$last_date5', y: $date6 },";
$javas.="{ label: '$last_date4', y: $date5 },";
$javas.="{ label: '$last_date3', y: $date4 },";
$javas.="{ label: '$last_date2', y: $date3 },";
$javas.="{ label: '$last_date1', y: $date2 },";
$javas.="{ label: '$curr_date', y: $date1 },";


?>
<h3 class="chart-title">Last 7 Days Order Report</h3>
<head>  
	<script type="text/javascript">
	window.onload = function () {
			var dps = [
		<?php echo $javas;?>
		];
		
		var chart = new CanvasJS.Chart("chartContainer",
		{
			
			 
			title:{
				//text: "Last 7 Days Order Report",
				fontSize:20,
				titleFontFamily: "Open Sans, sans-serif"
			},
			
                        animationEnabled: true,
			axisX:{

				gridColor: "Silver",
				tickColor: "silver"
				//valueFormatString: "DD/MMM"

			},                        
                        toolTip:{
                          shared:true
                        },
			theme: "theme2",
			axisY: {
				gridColor: "Silver",
				tickColor: "silver"
			},
			legend:{
				verticalAlign: "center",
				horizontalAlign: "right"
			},
			data: [
			{        
				type: "line",
				showInLegend: true,
				lineThickness: 2,
				name: "Orders",
				markerType: "square",
				color: "#F08080",
				dataPoints: dps
			}			
			],
			axisX: {
        title: "Last 7 days",
       // titleFontFamily: "comic sans ms"
      },
			axisY: {
        title: "No of Orders",
        //titleFontFamily: "comic sans ms"
      },
          legend:{
            cursor:"pointer",
            itemclick:function(e){
              if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
              	e.dataSeries.visible = false;
              }
              else{
                e.dataSeries.visible = true;
              }
              chart.render();
            }
          }
		});

chart.render();
}
</script>
<script type="text/javascript" src="js/canvasjs.min.js"></script>
</head>
<body>
	<div id="chartContainer" style="height: 300px; width: 100%;">
	</div>
</body>
</html><?php */?>

		
	

			
                <!-- /. ROW  -->
				
		<div class="col-lg-12" style="margin-top:50px;">		
		<?php include("footer.php") ?></div>
        	</footer>
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    
</body>

</html>