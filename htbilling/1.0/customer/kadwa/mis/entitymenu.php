<?php
	require("../info/phpgetlogin.php");
	require("../info/ncryptdcrypt.php");
	require("../info/routine.php");
	if ($_SESSION['changedefaultusersettings'] == 'on')
	{
		$_SESSION['changedefaultusersettings'] = 'off';
	}
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
					<li><a class="navbar" href="../mis/usermenu.php">User Menu</a>
					<li><a class="navbar" href="../mis/selectresponsibility.php">Switch Responsibility</a><br/>
					<li><a class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>
							
	            </ul>
        	</nav>
			<article class="w3-container">
				<section>
				<?php
					$finalreportperiodid_en = fnEncrypt($_SESSION['finalreportperiodid']);
					//HT Billing Master Addition / alteration
					if ($_SESSION["responsibilitycode"] == 123473322 or $_SESSION["responsibilitycode"] == 123473639)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
							if ($_SESSION['lng']=="English")
							{
								echo '<li><a class="servicebar" href="../ip_view/contractor.php">HT Contractor</a>';
								$connection = swapp_connection();
								$query = "select s.seasoncode,s.servicetrhrcategorycode
								,t.servicetrhrcatnameeng,t.servicetrhrcatnameuni
								,t.htservicecode
								,t.transportercategorycode,t.harvestercategorycode
								from servicetrhrseason s,servicetrhrcategory t
								where s.servicetrhrcategorycode=t.servicetrhrcategorycode
								and s.seasoncode=".$_SESSION['yearperiodcode'].
								" order by s.servicetrhrcategorycode";
								$result = oci_parse($connection, $query);
								$r = oci_execute($result);
								while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
								{
									$servicetrhrcategorycode_en=fnEncrypt($row['SERVICETRHRCATEGORYCODE']);
									echo '<li><a class="servicebar" href="../ip_view/subcontractor.php?servicetrhrcategorycode='.$servicetrhrcategorycode_en.'">'.$row['SERVICETRHRCATNAMEENG'].'</a>';
								}
								echo '<li><a class="servicebar" href="../ip_view/bank.php">Bank</a>';
								echo '<li><a class="servicebar" href="../ip_view/htbilltype.php">HT Bill Type</a>';
								echo '<li><a class="servicebar" href="../ip_view/deduction.php">Deduction</a>';
								echo '<li><a class="servicebar" href="../ip_view/deductionpriority.php">Deduction Priority</a>';
								echo '<li><a class="servicebar" href="../ip_view/htfixeddeduction.php">Fixed Deduction</a>';
								echo '<li><a class="servicebar" href="../ip_view/installment.php">Advance Installment</a>';	
								echo '<li><a class="servicebar" href="../ip_view/tyregadirentrate.php">Tyre Gadi Rent</a>';	
								echo '<li><a class="servicebar" href="../ip_view/harvestingrate.php">Harvesting Rate</a>';
								echo '<li><a class="servicebar" href="../ip_view/transportingrateheader.php">Transporting Rate</a>';	
								echo '<li><a class="servicebar" href="../ip_view/htbillperiod.php">Bill Period</a>';
								echo '<li><a class="servicebar" href="../ip_view/paymentrule.php">Payment Rules</a>';
							}
							else
							{
								echo '<li><a class="servicebar" href="../ip_view/contractor.php">तोडणी वाहतूक कंत्राटदार</a>';
								$connection = swapp_connection();
								$query = "select s.seasoncode,s.servicetrhrcategorycode
								,t.servicetrhrcatnameeng,t.servicetrhrcatnameuni
								,t.htservicecode
								,t.transportercategorycode,t.harvestercategorycode
								from servicetrhrseason s,servicetrhrcategory t
								where s.servicetrhrcategorycode=t.servicetrhrcategorycode
								and s.seasoncode=".$_SESSION['yearperiodcode'].
								" order by s.servicetrhrcategorycode";
								$result = oci_parse($connection, $query);
								$r = oci_execute($result);
								while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
								{
									$servicetrhrcategorycode_en=fnEncrypt($row['SERVICETRHRCATEGORYCODE']);
									echo '<li><a class="servicebar" href="../ip_view/subcontractor.php?servicetrhrcategorycode='.$servicetrhrcategorycode_en.'">'.$row['SERVICETRHRCATNAMEUNI'].' सबकंत्राटदार'.'</a>';
								}
								echo '<li><a class="servicebar" href="../ip_view/bank.php">बँक</a>';
								echo '<li><a class="servicebar" href="../ip_view/htbilltype.php">बिल प्रकार</a>';
								echo '<li><a class="servicebar" href="../ip_view/deduction.php">कपाती</a>';
								echo '<li><a class="servicebar" href="../ip_view/deductionpriority.php">कपात क्रम</a>';
								echo '<li><a class="servicebar" href="../ip_view/htfixeddeduction.php">फिक्स्ड रक्कम कपात</a>';
								echo '<li><a class="servicebar" href="../ip_view/installment.php">अॅडव्हान्स हप्ते</a>';	
								echo '<li><a class="servicebar" href="../ip_view/tyregadirentrate.php">टायरगाडी भाडे दर</a>';	
								echo '<li><a class="servicebar" href="../ip_view/harvestingrate.php">तोडणी दर</a>';
								echo '<li><a class="servicebar" href="../ip_view/transportingrateheader.php">वाहतूक अंतर दर</a>';	
								echo '<li><a class="servicebar" href="../ip_view/htbillperiod.php">बिल कालावधी</a>';
								echo '<li><a class="servicebar" href="../ip_view/paymentrule.php">पेमेंट नियम</a>';
							}
						echo '</ul>';
						echo "</p>";
					}
					//HT Billing Transaction Addition / alteration
					else if ($_SESSION["responsibilitycode"] == 123473956 or $_SESSION["responsibilitycode"] == 123474273)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						if ($_SESSION['lng']=="English")
						{
							$drcr_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../mis/submenu.php?drcr='.$drcr_en.'">Deduction Debit</a>';
							$drcr_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../mis/submenu.php?drcr='.$drcr_en.'">Deduction Credit</a>';
							echo '<li><a class="servicebar" href="../ip_view/deductionadjustment.php">Deduction Adjustment</a>';
						}
						else
						{
							$drcr_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../mis/submenu.php?drcr='.$drcr_en.'">कपाती नावे</a>';
							$drcr_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../mis/submenu.php?drcr='.$drcr_en.'">कपाती जमा</a>';
							echo '<li><a class="servicebar" href="../ip_view/deductionadjustment.php">कपात तडजोड</a>';
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
					// Reports
					else if ($_SESSION["responsibilitycode"] == 123474590)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						echo '<li><a class="servicebar" href="../op_view/periodical_selection.php&categorycode='.$categorycode_en.'">Sugar Memo Sale Summary</a>';	
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