<?php
    require("../info/phpgetlogin.php");
    require("../info/ncryptdcrypt.php");
	require_once("../../../../../sqlproc/defaultusersettings.php");
	require("../info/routine.php");
	$legalentitycode_de = fnDecrypt($_GET['legalentitycode']);
	$_SESSION["legalentitycode"] = $legalentitycode_de;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"></meta>
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<link rel="stylesheet" href="../css/w3.css">
		<title>Financila Year</title>
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
		<script src="http://ajax.googleapi_mysqls.com/ajax/libs/jquery/1.11.0/jquery.min.js">
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
						//$connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
						// Check connection
						$connection = swappdb_connection();
						/*if (mysqli_connect_errno())
						  {
						  	echo "Communication Error";
						  }*/
						  	//$query = "SELECT d.yearperiodcode,d.periodname_eng FROM finalreportperiod d where d.active=1 and finalreportperiodcategoryid=134578598 order by perioddatetimefrom desc";						  	
							  $query = "SELECT * FROM yearperiod d order by crushingfromdatetime desc";						  	
							  /* //mysqli_query($connection,'SET NAMES UTF8');
							$result = oci_parse($connection, $query); $r = oci_execute($result);
							if (!$result)
							{
							  die('Communication Error');
							} */
							$result = oci_parse($connection, $query); $r = oci_execute($result);
                			$r = oci_execute($result);

							// Iterate through the rows, adding XML nodes for each
							echo '<table style="border=1px solid black; border-radius:10px; padding:0px; padding-top:6px;margin: 0px; font-family:verdana;" align="left" width=500px>';
                        	echo '<tr style="font-size:14px">';
                            echo '<td><label>आर्थिक वर्ष</br>Crushing Season</label></td>';
                        	echo '</tr>';
							// Opens a connection to a MySQL server
							/*$connection1=mysqli_connect($hostname, $username, $password, $database);
							// Check connection
							if (mysqli_connect_errno())
						  	{
						  		echo "Communication Error";
						  	}*/
							//$defaultyearperiodcode = getdefaultyearperiodcode($connection1);
							while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
							{
								echo '<tr style="font-size:13px">';
                        		$yearperiodcode_en = fnEncrypt($row['YEARPERIODCODE']);
								echo '<td><a class="servicebar" href="../sqlproc/selectedfinancialyear.php?yearperiodcode='.$yearperiodcode_en.'">'.$row['PERIODNAME_ENG'].'</a>';
                        		//echo '<td><a class="servicebar" href="../mis/entitymenu.php?yearperiodcode='.$yearperiodcode_en.'">'.$row['PERIODNAME'].' ('.$row['PERIODNAME_ENG'].')</A>';
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