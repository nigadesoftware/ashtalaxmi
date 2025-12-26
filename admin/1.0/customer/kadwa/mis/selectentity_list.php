<?php
    require("../info/phpgetlogin.php");
    require("../info/ncryptdcrypt.php");
	require_once("../../../../../sqlproc/defaultusersettings.php");
    require("../info/routine.php");
    if (isset($_GET['finalreportperiodid']))
    {
    	$finalreportperiodid = fnDecrypt($_GET['finalreportperiodid']);
		$_SESSION["finalreportperiodid"]=$finalreportperiodid;
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"></meta>
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<link rel="stylesheet" href="../css/w3.css">
		<title>Entity</title>
		<style type="text/css">
			body
			{
				background-color: #fff;
			}
			header
			{
				background-color: #fff;
				min-height: 38px;
				color: #070;
				font-family: Arial;
				font-size: 19px;
			}
			nav
			{
				width: 300px;
				float: left;
				list-style-type: none;
				font-family: verdana;
				font-size: 15px;
				color: #fc8;
				line-height: 30px;
			}
			a
			{
				color: #f48;
			}
			article
			{
				background-color: #fff;
				display: table;
				margin-left: 0px;
				padding-left: 10px;
				font-family: Verdana;
				font-size: 15px;
			}
			section
			{
				margin-left: 0px;
				margin-right: 15px;
				float: left;
				text-align: left;
				color: #111;
				line-height: 23px;
			}
			footer
			{
				float: bottom;
				color: #eee;
				font-family: verdana;
				font-size: 12px;
			}
			div
			{
				float:left;
			}
			ul
			{
				line-height: 30px;
			}
		</style>
		<script src="../js/1.11.0/jquery.min.js">
 		 </script>
 		 <script>
 			$(document).ready(function(){
			 setInterval(function(){cache_clear()},360000);
			 });
			 function cache_clear()
			{
			 window.location.reload(true);
			}
		</script>
	</head>
	<body>
		<nav "w3-container">
			<ul class="navbar">
				<li><a class="navbar" href="../mis/usermenu.php">Menu</a>
			</ul>
		</nav>
		<article class="w3-container">
			<section>
					<?php
						require("../info/phpsqlajax_dbinfo.php");
						/*// Opens a connection to a MySQL server
						$connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
						// Check connection
						if (mysqli_connect_errno())
						{
						  	die('Communication Error1');
						}
						$query = "SELECT g.entityglobalgroupid,g.globalgroupid,e.entityid,e.entityname,e.entityname_eng FROM vw_entity e,entityglobalgroup g where e.entityid=g.entityid and e.active=1 and g.active=1 order by e.entityname asc";						  	
						mysqli_query($connection,'SET NAMES UTF8');
						$result = mysqli_query($connection, $query); 
						if (!$result)
						{
							die('Communication Error2');
						}*/
						$connection=swapp_connection();
						$query = "select t.legalentitycode,t.legalentitynameeng,t.legalentitynameuni from nst_nasaka_finance.legalentity t order by t.legalentitycode";
						$result = oci_parse($connection, $query);
						$r = oci_execute($result);
						if (!$result)
						{
							die('Communication Error2');
						}
						// Iterate through the rows, adding XML nodes for each
						echo '<table style="border=1px solid black; border-radius:10px; padding:0px; padding-top:6px;margin: 0px; font-family:verdana;" align="left" width=500px>';
						echo '<tr style="font-size:14px">';
						echo '<td><label>बिझनेस एनटीटी</br>Business Entity</label></td>';
						echo '</tr>';
						while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
						{
							echo '<tr style="font-size:13px">';
							$legalentitycode_en = fnEncrypt($row['LEGALENTITYCODE']);
							echo '<td><a class="servicebar" href="../mis/selectfinancialyear.php?legalentitycode='.$legalentitycode_en.'">'.$row['LEGALENTITYNAMEUNI'].'</BR>('.$row['LEGALENTITYNAMEENG'].')'.'</a>';
							echo '</tr>';
						}
						echo "</table>";
					?>
			</section>
		</article>
		<footer>
		</footer>
	</body>
</html>