<?php
	require("../info/phpgetlogin.php");
	require("../info/ncryptdcrypt.php");
	require("../info/routine.php");
	require_once("../../../../../info/crypto.php");
	if ($_SESSION['changedefaultusersettings'] == 'on')
	{
		$_SESSION['changedefaultusersettings'] = 'off';
	}
	header('location: ../admin/index.php');
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
					<li><a class="navbar" href="../mis/selectfinancialyear.php">Change Season Year</a>
					<li><a class="navbar" href="../mis/selectresponsibility.php">Switch Responsibility</a><br/>
					<?php
						$custid = new crypto;
						$customerid_en = $custid->Encrypt($_SESSION['factorycode']);
						$basefolder_en = $custid->Encrypt($_SESSION['basefolder']);
						echo '<li><a class="navbar" href="../../../../../mis/selectmodule.php?customerid='.$customerid_en.'&basefolder='.$basefolder_en.'">Switch Module</a><br/>';
					?>

					<li><a class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>
							
	            </ul>
        	</nav>
			<article class="w3-container">
				<section>
				<?php
					$finalreportperiodid_en = fnEncrypt($_SESSION['finalreportperiodid']);
					//Agriculture Functional
					if ($_SESSION["responsibilitycode"] == 123480930)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						if ($_SESSION['lng']=="English")
						{
							echo '<li><a class="servicebar" href="../ip_view/swapp_table.php">Form Generation</a>';
							echo '<li><a class="servicebar" href="../ip_view/form.php">Form Registration</a>';
							echo '<li><a class="servicebar" href="../ip_view/report.php">Report Registration</a>';
						}
						else
						{
							echo '<li><a class="servicebar" href="../ip_view/swapp_table.php">फॉर्म बनविणे</a>';
							echo '<li><a class="servicebar" href="../ip_view/form.php">फॉर्म नोंदणी</a>';
							echo '<li><a class="servicebar" href="../ip_view/report.php">रिपोर्ट नोंदणी</a>';
						}
					}
					//Agriculture Form start
					$connection=swapp_connection();
					$query = "select t.formcode
							,t.formname_eng
							,t.formname_uni
							,t.formfile
							from nst_nasaka_db.form t
							,nst_nasaka_db.formresponsibilitydetail r
							where t.formcode=r.formcode 
							and t.moduleid=421632541 
							and responsibilitycode=".$_SESSION['responsibilitycode']."
							and ismenu=1 order by sequencenumber";
			      	$result = oci_parse($connection, $query);
            		$row1 = oci_execute($result);			       
					while ($row1 = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
					{
						$formcode_en= fnEncrypt($row1['FORMCODE']);
						$query1 = "select r.parametername,r.parametervalue
							from nst_nasaka_db.form t
							,nst_nasaka_db.formparameterdetail r
							where t.formcode=r.formcode 
							and t.moduleid=421632541 
							and t.formcode=".$row1['FORMCODE'].
							" and ismenu=1 order by serialnumber";
						$result1 = oci_parse($connection, $query1);
						$row2 = oci_execute($result1);
						$para='formcode='.fnEncrypt($row1['FORMCODE']);			       
						while ($row2 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
						{
							$para=$para.'&'.$row2['PARAMETERNAME'].'='.fnEncrypt($row2['PARAMETERVALUE']);
						}
							if ($_SESSION['lng']=="English")
							{
								echo '<li><a class="servicebar" href="../ip_view/'.$row1['FORMFILE'].'.php?'.$para. '">'.$row1['FORMNAME_ENG'].'</a>';
							}
							else
							{
								echo '<li><a class="servicebar" href="../ip_view/'.$row1['FORMFILE'].'.php?'.$para.'">'.$row1['FORMNAME_UNI'].'</a>';	
							}
					}
					/* if (($_SESSION["responsibilitycode"] == 123981247 or $_SESSION["responsibilitycode"] == 123981564) and $_SESSION['yearperiodcode']!=20212022)
					{
						echo '<li><a class="servicebar" href="../ip_view/plantationheader.php">ऊस नोंद</a>';	
					} */
					if ($_SESSION["responsibilitycode"] == 123980930 or $_SESSION["responsibilitycode"] == 123981247 or $_SESSION["responsibilitycode"] == 123981564)
					{
						//echo '<li><a class="servicebar" href="../ip_view/farmer.php">शेतकरी</a>';	
					}
					if ($_SESSION["responsibilitycode"] == 123981887 or $_SESSION["responsibilitycode"] == 123981564)
					{
						$activitycode_en = fnEncrypt('3');
						echo '<li><a class="servicebar" href="../mis/submenu.php?activitycode='.$activitycode_en.'">तोड स्लीप</a>';
					}
					//Agriculture Form end
					//Agriculture Report start
					$connection=swapp_connection();
					$query = "select t.reportcode
							,t.reportname_eng
							,t.reportname_uni
							from nst_nasaka_db.report t
							,nst_nasaka_db.reportresponsibilitydetail r
							where t.reportcode=r.reportcode 
							and responsibilitycode=".$_SESSION['responsibilitycode']." 
							and moduleid=".$_SESSION['mismoduleid'].
							" order by sequencenumber";
			      	 $result = oci_parse($connection, $query);
            		 $row1 = oci_execute($result);			       
					while ($row1 = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
					{
						$reportcode_en= fnEncrypt($row1['REPORTCODE']);
						if ($_SESSION['lng']=="English")
						{
						 	echo '<li><a class="servicebar" href="../op_view/report_selection.php?reportcode='.$reportcode_en. '">'.$row1['REPORTNAME_ENG'].'</a>';
						}
						else
						{
						 	echo '<li><a class="servicebar" href="../op_view/report_selection.php?reportcode='.$reportcode_en.'">'.$row1['REPORTNAME_UNI'].'</a>';	
						}
					}
					if ($_SESSION["responsibilitycode"] == 123981564)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						if ($_SESSION['lng']=="English")
						{
							echo '<li><a class="servicebar" href="../ip_view/slipboy.php?flag='.fnEncrypt('Query').'">Slip Boy</a>';
						}
						else
						{
							echo '<li><a class="servicebar" href="../ip_view/slipboy.php?flag='.fnEncrypt('Query').'">स्लीप बाॅय</a>';
						}
						echo '</ul>';
						echo "</p>";
					}
					//Agriculture Report end
					//Weighbridge Transaction Addition / alteration
					if ($_SESSION["responsibilitycode"] == 123478394 or $_SESSION["responsibilitycode"] == 123478711)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						if ($_SESSION['lng']=="English")
						{
							//$tokenbasecode_en = fnEncrypt('1');
							//echo '<li><a class="servicebar" href="../ip_view/caneyardtoken.php?tokenbasecode='.$tokenbasecode_en.'">Vehicle Caneyard Token</a>';
							//$tokenbasecode_en = fnEncrypt('2');
							//echo '<li><a class="servicebar" href="../ip_view/caneyardtoken.php?tokenbasecode='.$tokenbasecode_en.'">Tyregadi Caneyard Token</a>';
							//$activitycode_en = fnEncrypt('2');
							//echo '<li><a class="servicebar" href="../mis/submenu.php?activitycode='.$activitycode_en.'&">Field Slip</a>';
							echo '<li><a class="servicebar" href="../ip_view/settings.php">Shift Settings</a>';
							$activitycode_en = fnEncrypt('1');
							$weightcategorycode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../mis/submenu.php?activitycode='.$activitycode_en.'&vehiclecategorycode='.$vehiclecategorycode_en.'">Load Weight</a>';
							$weightcategorycode_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../mis/submenu.php?activitycode='.$activitycode_en.'&vehiclecategorycode='.$vehiclecategorycode_en.'">Empty Weight</a>';
							//$weightcategorycode_en = fnEncrypt('3');
							//echo '<li><a class="servicebar" href="../mis/submenu.php?activitycode='.$activitycode_en.'&vehiclecategorycode='.$vehiclecategorycode_en.'">Manual Weight</a>';
							$weightcategorycode_en = fnEncrypt('4');
							echo '<li><a class="servicebar" href="../mis/submenu.php?activitycode='.$activitycode_en.'&vehiclecategorycode='.$vehiclecategorycode_en.'">Weight Slip</a>';
							$reportcategorycode_en=fnEncrypt('1');
							echo '<li><a class="servicebar" href="../ip_view/vehiclelist.php?reportcategorycode='.$reportcategorycode_en.'">Pending for Token List</a>';
							$reportcategorycode_en=fnEncrypt('2');
							echo '<li><a class="servicebar" href="../ip_view/vehiclelist.php?reportcategorycode='.$reportcategorycode_en.'">Pending for Load Weight List</a>';
							$reportcategorycode_en=fnEncrypt('3');
							echo '<li><a class="servicebar" href="../ip_view/vehiclelist.php?reportcategorycode='.$reportcategorycode_en.'">Pending for Empty Weight List</a>';
						}
						else
						{
							//$tokenbasecode_en = fnEncrypt('1');
							//echo '<li><a class="servicebar" href="../ip_view/caneyardtoken.php?tokenbasecode='.$tokenbasecode_en.'">वाहन केनयार्ड टोकन</a>';
							//$tokenbasecode_en = fnEncrypt('2');
							//echo '<li><a class="servicebar" href="../ip_view/caneyardtoken.php?tokenbasecode='.$tokenbasecode_en.'">टायरगाडी केनयार्ड टोकन</a>';
							//$activitycode_en = fnEncrypt('2');
							//echo '<li><a class="servicebar" href="../mis/submenu.php?activitycode='.$activitycode_en.'&">फिल्ड स्लीप</a>';
							echo '<li><a class="servicebar" href="../ip_view/settings.php">शिफ्ट सेटिंग</a>';
							$activitycode_en = fnEncrypt('1');
							$weightcategorycode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../mis/submenu.php?activitycode='.$activitycode_en.'&weightcategorycode='.$weightcategorycode_en.'">भरगाडी वजन</a>';
							$weightcategorycode_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../mis/submenu.php?activitycode='.$activitycode_en.'&weightcategorycode='.$weightcategorycode_en.'">रिकामीगाडी वजन</a>';
							//$weightcategorycode_en = fnEncrypt('3');
							//echo '<li><a class="servicebar" href="../mis/submenu.php?activitycode='.$activitycode_en.'&weightcategorycode='.$weightcategorycode_en.'">मॅन्यूअल वजनस्लीप</a>';
							$weightcategorycode_en = fnEncrypt('4');
							echo '<li><a class="servicebar" href="../mis/submenu.php?activitycode='.$activitycode_en.'&weightcategorycode='.$weightcategorycode_en.'">वजन स्लीप</a>';
							$reportcategorycode_en=fnEncrypt('1');
							echo '<li><a class="servicebar" href="../ip_view/vehiclelist.php?reportcategorycode='.$reportcategorycode_en.'">टोकनसाठी पेंडींग यादी</a>';
							$reportcategorycode_en=fnEncrypt('2');
							echo '<li><a class="servicebar" href="../ip_view/vehiclelist.php?reportcategorycode='.$reportcategorycode_en.'">भरगाडीसाठी पेंडींग यादी</a>';
							$reportcategorycode_en=fnEncrypt('3');
							echo '<li><a class="servicebar" href="../ip_view/vehiclelist.php?reportcategorycode='.$reportcategorycode_en.'">रिकामीगाडीसाठी पेंडींग यादी</a>';
							$weightcategorycode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../ip_view/otherweight.php?weightcategorycode='.$weightcategorycode_en.'">इतर मटेरियल भरगाडी वजन</a>';
							$weightcategorycode_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../ip_view/otherweight.php?weightcategorycode='.$weightcategorycode_en.'">इतर मटेरियल रिकामीगाडी वजन</a>';

						}
						echo '</ul>';
						echo "</p>";
					}
					//Weighbridge Shift Incharge
					if ($_SESSION["responsibilitycode"] == 123982204)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						if ($_SESSION['lng']=="English")
						{
							$tokenbasecode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../ip_view/caneyardtoken.php?tokenbasecode='.$tokenbasecode_en.'">Vehicle Caneyard Token</a>';
							$tokenbasecode_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../ip_view/caneyardtoken.php?tokenbasecode='.$tokenbasecode_en.'">Tyregadi Caneyard Token</a>';
							$activitycode_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../mis/submenu.php?activitycode='.$activitycode_en.'&">Field Slip</a>';
							echo '<li><a class="servicebar" href="../ip_view/settings.php">Shift Settings</a>';
							$activitycode_en = fnEncrypt('1');
							/* $weightcategorycode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../mis/submenu.php?activitycode='.$activitycode_en.'&vehiclecategorycode='.$vehiclecategorycode_en.'">Load Weight</a>';
							$weightcategorycode_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../mis/submenu.php?activitycode='.$activitycode_en.'&vehiclecategorycode='.$vehiclecategorycode_en.'">Empty Weight</a>';*/
							$weightcategorycode_en = fnEncrypt('3');
							echo '<li><a class="servicebar" href="../mis/submenu.php?activitycode='.$activitycode_en.'&vehiclecategorycode='.$vehiclecategorycode_en.'">Manual Weight</a>';
						}
						else
						{
							$tokenbasecode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../ip_view/caneyardtoken.php?tokenbasecode='.$tokenbasecode_en.'">वाहन केनयार्ड टोकन</a>';
							$tokenbasecode_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../ip_view/caneyardtoken.php?tokenbasecode='.$tokenbasecode_en.'">टायरगाडी केनयार्ड टोकन</a>';
							$activitycode_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../mis/submenu.php?activitycode='.$activitycode_en.'&">फिल्ड स्लीप</a>';
							echo '<li><a class="servicebar" href="../ip_view/settings.php">शिफ्ट सेटिंग</a>';
							$activitycode_en = fnEncrypt('1');
							/* $weightcategorycode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../mis/submenu.php?activitycode='.$activitycode_en.'&weightcategorycode='.$weightcategorycode_en.'">भरगाडी वजन</a>';
							$weightcategorycode_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../mis/submenu.php?activitycode='.$activitycode_en.'&weightcategorycode='.$weightcategorycode_en.'">रिकामीगाडी वजन</a>'; */
							$weightcategorycode_en = fnEncrypt('3');
							echo '<li><a class="servicebar" href="../mis/submenu.php?activitycode='.$activitycode_en.'&weightcategorycode='.$weightcategorycode_en.'">मॅन्यूअल वजनस्लीप</a>';
						}
						echo '</ul>';
						echo "</p>";
					}
					$finalreportperiodid_en = fnEncrypt($_SESSION['finalreportperiodid']);
					//HT Billing Transaction Addition / alteration
					if ($_SESSION["responsibilitycode"] == 123473956 or $_SESSION["responsibilitycode"] == 123474273)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						/* if ($_SESSION['lng']=="English")
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
						} */
						echo '</ul>';
						echo "</p>";
					}
					/* //HT Billing officer alteration 123474907
					else if ($_SESSION["responsibilitycode"] == 123474907)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						echo '<li><a class="servicebar" href="../ip_view/saleinvoiceheader.php">HT Billing Invoice</a>';
						echo '</ul>';
						echo "</p>";
					} */
					
				?>					
				</section>
			</article>
			<footer>
				<div class="copyright">Copyright &copy;2020 Nigade Software Technologies (opc) Private Limited</div>
			</footer>
	</body>
</html>