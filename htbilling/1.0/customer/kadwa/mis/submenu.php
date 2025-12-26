<?php
	require("../info/phpgetlogin.php");
	require("../info/ncryptdcrypt.php");
	require("../info/routine.php");
	if ($_SESSION['changedefaultusersettings'] == 'on')
	{
		$_SESSION['changedefaultusersettings'] = 'off';
	}
    $drcr_de = fnDecrypt($_GET['drcr']);
?>
<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8"></meta>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="../css/w3.css">
		<title>Entity Menu</title>
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
				font-family: arial;
				font-size: 19px;
			}
			nav
			{
				width: 300px;
				float: left;
				list-style-type: none;
				font-family: verdana;
				font-size: 15px;
				color: #000;
				line-height: 30px;
			}
			article
			{
				background-color: #fff;
				display: table;
				margin-left: 0px;
				padding-left: 10px;
				font-family: verdana;
				font-size: 15px;
			}
			section
			{
				margin-left: 0px;
				margin-right: 15px;
				float: left;
				text-align: left;
				color: #fc8;
				line-height: 23px;
			}
			a.navbar
			{
				color: #f48;
			}
			a.servicebar
			{
				color: #080;
			}
			footer
			{
				float: bottom;
				color: #000;
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
			 setInterval(function(){cache_clear()},3600000);
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
	                <li><a class="navbar" href="../../../../../index.php">Home</a><br/>
					<li><a class="navbar" href="../mis/entitymenu.php">Entity Menu</a>
					<li><a class="navbar" href="../mis/usermenu.php">User Menu</a>
					<li><a class="navbar" href="../mis/selectresponsibility.php">Switch Responsibility</a><br/>
					<li><a class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>
							
	            </ul>
        	</nav>
			<article class="w3-container">
				<section>
				<?php
					$finalreportperiodid_en = fnEncrypt($_SESSION['finalreportperiodid']);
					//HT Billing Transaction Addition / alteration
					if ($_SESSION["responsibilitycode"] == 123473956 or $_SESSION["responsibilitycode"] == 123474273)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						$drcr_en = fnEncrypt($drcr_de);
						if ($_SESSION['lng']=="English")
						{
							$connection = swapp_connection();
							$query = "select t.deductioncode
                            ,t.deductionnameeng
                            ,t.deductionnameuni 
							from deduction t
							where t.deductioncode in 
							(select d.deductioncode 
							from deductionbasedetail d)
              				order by t.deductioncode";
							$result = oci_parse($connection, $query);
							$r = oci_execute($result);
							while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
							{
								$deductioncode_en = fnEncrypt($row['DEDUCTIONCODE']);
								$deductionbasecode_en = fnEncrypt($row['DEDUCTIONBASECODE']);
								echo '<li><a class="servicebar" href="../ip_view/deductionclaim.php?drcr='.$drcr_en.'&deductioncode='.$deductioncode_en.'">'.$row['DEDUCTIONNAMEENG'].' Deduction'.'</a>';
							}
							//echo '<li><a class="servicebar" href="../ip_view/deductionadjustment.php">Deduction Adjustment</a>';
						}
						else
						{
							$connection = swapp_connection();
							$query = "select t.deductioncode
                            ,t.deductionnameeng
                            ,t.deductionnameuni 
							from deduction t 
							where t.deductioncode in 
							(select d.deductioncode 
							from deductionbasedetail d)
              				order by t.deductioncode";
							$result = oci_parse($connection, $query);
							$r = oci_execute($result);
							while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
							{
								$deductioncode_en = fnEncrypt($row['DEDUCTIONCODE']);
								$deductionbasecode_en = fnEncrypt($row['DEDUCTIONBASECODE']);
								echo '<li><a class="servicebar" href="../ip_view/deductionclaim.php?drcr='.$drcr_en.'&deductioncode='.$deductioncode_en.'">'.$row['DEDUCTIONNAMEUNI'].' कपाती'.'</a>';
							}
                            //echo '<li><a class="servicebar" href="../ip_view/deductionadjustment.php">कपात तडजोड</a>';
						}
						echo '</ul>';
						echo "</p>";
					}
					//HT Billing officer alteration 123474907
					else if ($_SESSION["responsibilitycode"] == 123474907)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						echo '<li><a class="servicebar" href="../ip_view/saleinvoiceheader.php">HT Billing Invoice</a>';
						echo '</ul>';
						echo "</p>";
					}
				?>					
				</section>
			</article>
			<footer>
				<div class="copyright">Copyright &copy;2020 Nigade Software Technologies (opc) Private Limited</div>
			</footer>
	</body>
</html>