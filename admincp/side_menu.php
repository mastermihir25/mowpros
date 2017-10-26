 <?php
date_default_timezone_set("Asia/Kolkata");
include("../connection.php");
			
	?>
  <!-- /. NAV SIDE  -->
  <style>
.active a {
    background:#34495e !important;
    color: #000;
}
</style>
 <nav class="navbar-default navbar-side" role="navigation">
		<div id="sideNav" href="#"><i class="fa fa-caret-right"></i></div>
            <div class="sidebar-collapse">
                <ul class="mainmenu nav" >
	<li class="menuitem <?php if($page=='dashboard'){echo 'active';}?>" >
		<a href="dashboard.php"><span>Dashboard</span></a>
	</li>
    <?php /*if($_SESSION['role']==1 || $_SESSION['role']==2)
	{
*/	?>
	<li class="menuitem <?php if($page=='users'){echo 'active';}?>" >
		<a href="users.php"><span>Customer</span></a>
	</li>
    <li class="menuitem <?php //if($page=='users'){echo 'active';}?>" >
		<a href="services_stop.php"><span>Service Stop</span></a>
	</li>
    <?php
	/*}*/
	?>
	<!--<li class="menuitem <?php //if($page=='order'){echo 'active';}?>" >
		<a href="order.php"><span>Order</span></a>
	</li> -->
     <?php if($_SESSION['role']==2)
	{
	?> 
    <li class="menuitem <?php if($page=='employe'){echo 'active';}?>" >
		<a href="employe.php"><span>Employe</span></a>
	</li>
    <li class="menuitem <?php if($page=='groups'){echo 'active';}?>" >
		<a href="groups.php"><span>Groups</span></a>
	</li>
	<li class="menuitem <?php if($page=='postal'){echo 'active';}?>" >
		<a href="postal_code.php"><span>Postal Code</span></a>
	</li>  
	<li class="menuitem <?php if($page=='services'){echo 'active';}?>" >
		<a href="services.php"><span>Services</span></a>
	</li> 
	<li class="menuitem <?php if($page=='services_sub'){echo 'active';}?>" >
		<a href="services_sub.php"><span>Services Sub category</span></a>
	</li> 
	<li class="menuitem <?php if($page=='cleaner'){echo 'active';}?>" >
		<a href="cleaner.php"><span>Become a Service Provider</span></a>
	</li> 
     
	<?php /*?><li class="menuitem <?php if($page=='pages'){echo 'active';}?>" >		
		<a href="pages.php"><span>Pages</span></a>			
	</li> <?php */?>
	<li class="menuitem <?php if($page=='setting'){echo 'active';}?>" >		
		<a href="setting.php"><span>Setting</span></a>			
	</li> 
	<li class="menuitem <?php if($page=='change password'){echo 'active';}?>" >		
		<a href="change_pwd.php"><span>Change Password</span></a>			
	</li> 
	<?php
	}
	?>
	
</ul>
            </div>

  </nav>
      