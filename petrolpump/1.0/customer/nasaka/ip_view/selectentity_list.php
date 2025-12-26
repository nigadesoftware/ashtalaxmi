<?php
    require("../info/phpgetlogin.php");
    require("../info/ncryptdcrypt.php");
	require_once("../../../../../sqlproc/defaultusersettings.php");

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
						// Opens a connection to a MySQL server
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
						}
						// Iterate through the rows, adding XML nodes for each
						echo '<table style="border=1px solid black; border-radius:10px; padding:0px; padding-top:6px;margin: 0px; font-family:verdana;" align="left" width=500px>';
						echo '<tr style="font-size:14px">';
						echo '<td><label>बिझनेस एनटीटी</br>Business Entity</label></td>';
						echo '</tr>';
						// Opens a connection to a MySQL server
						$connection1=mysqli_connect($hostname, $username, $password, $database);
						// Check connection
						if (mysqli_connect_errno())
						{
						  	die('Communication Error1');
						}
						$defaultentityid = getdefaultentityid($connection1);
						while ($row = @mysqli_fetch_assoc($result))
						{
							echo '<tr style="font-size:13px">';
							$entityglobalgroupid_en = fnEncrypt($row['entityglobalgroupid']);
							$globalgroupid_en = fnEncrypt($row['globalgroupid']);
							$entityid_en = fnEncrypt($row['entityid']);
							echo '<td><a class="servicebar" href="../ip_view/selectsubentity_list.php?entityglobalgroupid='.$entityglobalgroupid_en.'&globalgroupid='.$globalgroupid_en.'&entityid='.$entityid_en.'">'.$row['entityname'].'</BR>('.$row['entityname_eng'].')'.'</A>';
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