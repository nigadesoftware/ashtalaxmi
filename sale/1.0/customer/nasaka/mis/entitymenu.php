<?php
	require("../info/phpgetlogin.php");
	require("../info/ncryptdcrypt.php");
	require("../info/swapproutine.php");
	require_once("../../../../../info/crypto.php");
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
				font-family: siddhanta;
				font-size: 18px;
			}
			section
			{
				margin-left: 0px;
				margin-right: 15px;
				float: left;
				text-align: left;
				color: #080;
				line-height: 23px;
			}
			a.navbar
			{
				color: #000;
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
					if (in_array($_SESSION["responsibilitycode"],array(123464501,123465856,123466521,123467421,123468567,123468884,123457106)))
					{
						$goodscategorycode_en = fnEncrypt('1');
					}
					elseif (in_array($_SESSION["responsibilitycode"],array(123469201,123469518,123469835,123470152,123470469,123470786)))
					{
						$goodscategorycode_en = fnEncrypt('2');
					}
					//sugar Sale Master Addition / alteration
					if ($_SESSION["responsibilitycode"] == 123464501 or $_SESSION["responsibilitycode"] == 123465856)
					{
						$goodscategorycode_en = fnEncrypt('1');
						echo "<p>";
						echo '<ul class="servicebar">';
							echo '<li><a class="servicebar" href="../ip_view/finishedgoods.php?goodscategorycode='.$goodscategorycode_en.'">Goods</a>';
							echo '<li><a class="servicebar" href="../ip_view/goodstaxrate.php?goodscategorycode='.$goodscategorycode_en.'">Tax Rate</a>';
							echo '<li><a class="servicebar" href="../ip_view/goodspurchaser.php?goodscategorycode='.$goodscategorycode_en.'">Goods Purchaser</a>';
							echo '<li><a class="servicebar" href="../ip_view/policycompany.php">Insurance Company</a>';
						echo '</ul>';
						echo "</p>";
					}
					//Sugar Sale Transaction Addition / alteration
					else if ($_SESSION["responsibilitycode"] == 123466521 or $_SESSION["responsibilitycode"] == 123467421)
					{
						$goodscategorycode_en = fnEncrypt('1');
						echo "<p>";
						echo '<ul class="servicebar">';
							echo '<li><a class="servicebar" href="../ip_view/goodssalepermission.php?goodscategorycode='.$goodscategorycode_en.'">Release Order</a>';
							echo '<li><a class="servicebar" href="../ip_view/saletenderheader.php?goodscategorycode='.$goodscategorycode_en.'">Sugar Sale Tender</a>';
							echo '<li><a class="servicebar" href="../ip_view/salequotationheader.php?goodscategorycode='.$goodscategorycode_en.'">Sugar Sale Quotation</a>';
							echo '<li><a class="servicebar" href="../ip_view/saleorderheader.php?goodscategorycode='.$goodscategorycode_en.'">Sugar Sale Order</a>';
							echo '<li><a class="servicebar" href="../ip_view/salememoheader.php?goodscategorycode='.$goodscategorycode_en.'">Sugar Sale Memo</a>';
							echo '<li><a class="servicebar" href="../ip_view/saleinvoiceheader.php?goodscategorycode='.$goodscategorycode_en.'">Sugar Sale Invoice</a>';
						echo '</ul>';
						echo "</p>";
					}
					//Sugar Sale Godown Transaction Addition / alteration
					else if ($_SESSION["responsibilitycode"] == 123471103 or $_SESSION["responsibilitycode"] == 123471420 or $_SESSION["responsibilitycode"]==123468884)
					{
						$goodscategorycode_en = fnEncrypt('1');
						echo "<p>";
						echo '<ul class="servicebar">';
						echo '<li><a class="servicebar" href="../ip_view/saleinvoiceheader.php?goodscategorycode='.$goodscategorycode_en.'">Sugar Godown Gatepass</a>';
						echo '<li><a class="servicebar" href="../ip_view/salememoheader.php?goodscategorycode='.$goodscategorycode_en.'">Sugar Sale Memo</a>';
						$transactiontypecode_en = fnEncrypt('1');						
						echo '<li><a class="servicebar" href="../ip_view/godowntransaction.php?goodscategorycode='.$goodscategorycode_en.'&transactiontypecode='.$transactiontypecode_en.'">Production</a>';
						$transactiontypecode_en = fnEncrypt('3');						
						echo '<li><a class="servicebar" href="../ip_view/godowntransaction.php?goodscategorycode='.$goodscategorycode_en.'&transactiontypecode='.$transactiontypecode_en.'">Transfer Out</a>';
						$transactiontypecode_en = fnEncrypt('4');						
						echo '<li><a class="servicebar" href="../ip_view/godowntransaction.php?goodscategorycode='.$goodscategorycode_en.'&transactiontypecode='.$transactiontypecode_en.'">Transfer In</a>';
						$transactiontypecode_en = fnEncrypt('5');						
						echo '<li><a class="servicebar" href="../ip_view/godowntransaction.php?goodscategorycode='.$goodscategorycode_en.'&transactiontypecode='.$transactiontypecode_en.'">Reprocess</a>';
						echo '<li><a class="servicebar" href="../ip_view/godowninsurance.php">Godown Sugar Insurance</a>';
						$categorycode_en = fnEncrypt('16');
						echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?goodscategorycode='.$goodscategorycode_en.'&categorycode='.$categorycode_en.'">DateWise Production Sale Register</a>';
						echo '</ul>';
						echo "</p>";
					}
					else if ($_SESSION["responsibilitycode"] == 123457106)
					{
						$goodscategorycode_en = fnEncrypt('1');
						echo "<p>";
						echo '<ul class="servicebar">';
						echo '<li><a class="servicebar" href="../ip_view/saleinvoiceheader.php?goodscategorycode='.$goodscategorycode_en.'">Sugar Sale Invoice</a>';
						echo '</ul>';
						echo "</p>";
					}
					//Sugar sale officer alteration 123468884

					// Reports
					else if ($_SESSION["responsibilitycode"] == 123468567)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						$goodscategorycode_en = fnEncrypt('1');
						$categorycode_en = fnEncrypt('4');
						echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?goodscategorycode='.$goodscategorycode_en.'&categorycode='.$categorycode_en.'">Sugar Memo Sale Summary</a>';	
						$categorycode_en = fnEncrypt('3');
						echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?goodscategorycode='.$goodscategorycode_en.'&categorycode='.$categorycode_en.'">Sugar Memo Sale Detail</a>';	
						$categorycode_en = fnEncrypt('1');
						echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?goodscategorycode='.$goodscategorycode_en.'&categorycode='.$categorycode_en.'">Sugar Invoice Sale Summary</a>';	
						$categorycode_en = fnEncrypt('2');
						echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?goodscategorycode='.$goodscategorycode_en.'&categorycode='.$categorycode_en.'">Sugar Invoice Sale Detail</a>';
						$categorycode_en = fnEncrypt('5');
						echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?goodscategorycode='.$goodscategorycode_en.'&categorycode='.$categorycode_en.'">Sugar Godown Stock Summary</a>';
						$categorycode_en = fnEncrypt('6');
						echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?goodscategorycode='.$goodscategorycode_en.'&categorycode='.$categorycode_en.'">Brokerwise tender balance</a>';
						$categorycode_en = fnEncrypt('7');
						echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?goodscategorycode='.$goodscategorycode_en.'&categorycode='.$categorycode_en.'">Brokerwise tender balance detail</a>';
						$categorycode_en = fnEncrypt('8');
						echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?goodscategorycode='.$goodscategorycode_en.'&categorycode='.$categorycode_en.'">Tender Allotment Report</a>';
						$categorycode_en = fnEncrypt('9');
						echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?goodscategorycode='.$goodscategorycode_en.'&categorycode='.$categorycode_en.'">Sugar B2B Json file</a>';
						$categorycode_en = fnEncrypt('10');
						echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?goodscategorycode='.$goodscategorycode_en.'&categorycode='.$categorycode_en.'">Release Orderwise Tender Balance </a>';
						$categorycode_en = fnEncrypt('11');
						echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?goodscategorycode='.$goodscategorycode_en.'&categorycode='.$categorycode_en.'">Sugar Invoicewise Despatch</a>';
						$categorycode_en = fnEncrypt('12');
						echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?goodscategorycode='.$goodscategorycode_en.'&categorycode='.$categorycode_en.'">Sugar Memowise Invoicewise Despatch</a>';
						$categorycode_en = fnEncrypt('13');
						echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?goodscategorycode='.$goodscategorycode_en.'&categorycode='.$categorycode_en.'">Godownwise Insurance Statement</a>';
						$categorycode_en = fnEncrypt('14');
						echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?goodscategorycode='.$goodscategorycode_en.'&categorycode='.$categorycode_en.'">Purchaserwise Sale Detail</a>';
						$categorycode_en = fnEncrypt('15');
						echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?goodscategorycode='.$goodscategorycode_en.'&categorycode='.$categorycode_en.'">Sugar EInvoice B2B Json file</a>';
						
					
						echo '</ul>';
						echo "</p>";
					}
					//Molasses Sale Master Addition / alteration
					if ($_SESSION["responsibilitycode"] == 123469201 or $_SESSION["responsibilitycode"] == 123469518)
					{
						$goodscategorycode_en = fnEncrypt('1');
						echo "<p>";
						echo '<ul class="servicebar">';
							echo '<li><a class="servicebar" href="../ip_view/finishedgoods.php?goodscategorycode='.$goodscategorycode_en.'">Goods</a>';
							echo '<li><a class="servicebar" href="../ip_view/goodstaxrate.php?goodscategorycode='.$goodscategorycode_en.'">Tax Rate</a>';
							echo '<li><a class="servicebar" href="../ip_view/goodspurchaser.php?goodscategorycode='.$goodscategorycode_en.'">Goods Purchaser</a>';
						echo '</ul>';
						echo "</p>";
					}
					else if ($_SESSION["responsibilitycode"] == 123469835 or $_SESSION["responsibilitycode"] == 123470152)
					{
						$goodscategorycode_en = fnEncrypt('1');
						echo "<p>";
						echo '<ul class="servicebar">';
							echo '<li><a class="servicebar" href="../ip_view/goodssalepermission.php?goodscategorycode='.$goodscategorycode_en.'">Release Order</a>';
							echo '<li><a class="servicebar" href="../ip_view/saletenderheader.php?goodscategorycode='.$goodscategorycode_en.'">Molasses Sale Tender</a>';
							echo '<li><a class="servicebar" href="../ip_view/salequotationheader.php?goodscategorycode='.$goodscategorycode_en.'">Molasses Sale Quotation</a>';
							echo '<li><a class="servicebar" href="../ip_view/saleorderheader.php?goodscategorycode='.$goodscategorycode_en.'">Molasses Sale Order</a>';
							echo '<li><a class="servicebar" href="../ip_view/salememoheader.php?goodscategorycode='.$goodscategorycode_en.'">Molasses Sale Memo</a>';
							echo '<li><a class="servicebar" href="../ip_view/saleinvoiceheader.php?goodscategorycode='.$goodscategorycode_en.'">Molasses Sale Invoice</a>';
						echo '</ul>';
						echo "</p>";
					}
					//Molasses officer alteration 123470786

					// Reports
					else if ($_SESSION["responsibilitycode"] == 123470469)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						$goodscategorycode_en = fnEncrypt('1');
							$reportid_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../op_view/report_selection.php?salecategorycode='.$salecategorycode_en.'&reportid='.$reportid_en.'">Finished Goods Checklist</a>';
							$reportid_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../op_view/report_selection.php?salecategorycode='.$salecategorycode_en.'&reportid='.$reportid_en.'">Tax Rate Checklist</a>';
							$reportid_en = fnEncrypt('3');
							echo '<li><a class="servicebar" href="../op_view/report_selection.php?salecategorycode='.$salecategorycode_en.'&reportid='.$reportid_en.'">Molasses Purchaser Checklist</a>';	
							$reportid_en = fnEncrypt('4');
							echo '<li><a class="servicebar" href="../op_view/report_selection.php?salecategorycode='.$salecategorycode_en.'&reportid='.$reportid_en.'">Release Order</a>';
							$reportid_en = fnEncrypt('5');
							echo '<li><a class="servicebar" href="../op_view/report_selection.php?salecategorycode='.$salecategorycode_en.'&reportid='.$reportid_en.'">Sale Tender</a>';
							$reportid_en = fnEncrypt('6');
							echo '<li><a class="servicebar" href="../op_view/report_selection.php?salecategorycode='.$salecategorycode_en.'&reportid='.$reportid_en.'">Molasses Tender</a>';	
							$reportid_en = fnEncrypt('7');
							echo '<li><a class="servicebar" href="../op_view/report_selection.php?salecategorycode='.$salecategorycode_en.'&reportid='.$reportid_en.'">Molasses Sale Order</a>';	
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