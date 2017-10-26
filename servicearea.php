<?php include('connection.php');
?>
<script type="text/javascript">
<?php
$qry="select * from sv_postal_code";
$res=mysql_query($qry)or die("Error In Query");
$pc="";
$i=0;
while($row=mysql_fetch_array($res))
{
	$i++;
	
	if(mysql_num_rows($res)==$i)
	{
		$pc.="'".$row['postal_code']."'";
	}
	else
	{
		$pc.="'".$row['postal_code']."',";
	}
}

?>
function checkZipCodes() {var zipCodes = [<?php echo $pc;?>];var zip = document.getElementById("zip").value;var found = false;for (i = 0; i < zipCodes.length && !found; i++) {if (zipCodes[i] === zip) {found = true;}}if (found) {
document.getElementById("checkZipFound").style.display = "block";
document.getElementById("checkZipNotFound").style.display = "none";
} else {
document.getElementById("checkZipFound").style.display = "none";
document.getElementById("checkZipNotFound").style.display = "block";
}}</script>
<div>
<div style="color:#606; font-size:24px;"><p><span>Service Area</span></p></div><div id="checkZip">Please enter your zip code: <input id="zip" onkeypress="javascript:if (event.which || event.keyCode){if ((event.which == 13) || (event.keyCode == 13)) {event.cancelBubble=true; event.returnValue=false; checkZipCodes();}};" type="text"><input value="Go" onclick="checkZipCodes()" type="button"><p></p><div id="checkZipFound" style="display:none;">Yes, we service your area.<p><a onclick="navigation('getaquote')" style="color:#606; text-decoration:underline; cursor:pointer;"  >Get a Quote</a></p></div><div id="checkZipNotFound" style="display:none;">We do not yet service your area, but we're expanding and may be there next year.</div></div>
</div>