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
				background-color:#efd469;
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
				color: #fc8;
				line-height: 23px;
			}
			a.navbar
			{
				color: #f48;
			}
			a.servicebar
			{
				color:#373d3f;
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
					//Finance Master Addition / alteration
					if ($_SESSION["responsibilitycode"] == 325434741256025 or $_SESSION["responsibilitycode"] == 398541254784126)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						if ($_SESSION['lng']=="English")
						{
							echo '<li><a class="servicebar" href="../ip_view/accountgroup.php">Account Group</a>';
							echo '<li><a class="servicebar" href="../ip_view/accountsubgroup.php">Account Sub Group</a>';
							echo '<li><a class="servicebar" href="../ip_view/accountsubsubgroup.php">Account Sub Sub Group</a>';
							echo '<li><a class="servicebar" href="../ip_view/accountschedule.php">Schedule</a>';
							echo '<li><a class="servicebar" href="../ip_view/accountsubschedule.php">Sub Schedule</a>';
							echo '<li><a class="servicebar" href="../ip_view/accountsubsubschedule.php">Sub Subschedule</a>';
							echo '<li><a class="servicebar" href="../ip_view/accountsubsubsubschedule.php">Sub Sub Subschedule</a>';
							echo '<li><a class="servicebar" href="../ip_view/subledgertype.php">Subledger Type</a>';
							echo '<li><a class="servicebar" href="../ip_view/accounthead.php">Account Head</a>';
							echo '<li><a class="servicebar" href="../ip_view/accountheaddetail.php">Account Head Detail</a>';
						    echo '<li><a class="servicebar" href="../ip_view/accountsubledger.php">Account Subledger</a>';
							echo '<li><a class="servicebar" href="../ip_view/accountopening.php">Account Opening</a>';
						}
						else
						{
							echo '<li><a class="servicebar" href="../ip_view/accountgroup.php">अकौंट ग्रुप</a>';
							echo '<li><a class="servicebar" href="../ip_view/accountsubgroup.php">अकौंट सब ग्रुप</a>';
							echo '<li><a class="servicebar" href="../ip_view/accountsubsubgroup.php">अकौंट सब सब ग्रुप</a>';
							echo '<li><a class="servicebar" href="../ip_view/accountschedule.php">परिशिष्ठ</a>';
							echo '<li><a class="servicebar" href="../ip_view/accountsubschedule.php">सब परिशिष्ठ</a>';
							echo '<li><a class="servicebar" href="../ip_view/accountsubsubschedule.php">सब सब परिशिष्ठ</a>';
							echo '<li><a class="servicebar" href="../ip_view/accountsubsubsubschedule.php">सब सब सबपरिशिष्ठ</a>';
							echo '<li><a class="servicebar" href="../ip_view/subledgertype.php">सबलेजर प्रकार </a>';
							echo '<li><a class="servicebar" href="../ip_view/accounthead.php">अकौंट हेड </a>';
							echo '<li><a class="servicebar" href="../ip_view/accountheaddetail.php">अकौंट हेड डिटेल</a>';
							echo '<li><a class="servicebar" href="../ip_view/accountsubledger.php">अकौंट सबलेजर</a>';
							echo '<li><a class="servicebar" href="../ip_view/accountopening.php">खाते आरंभीची शिल्लक</a>';
						}
						echo '</ul>';
						echo "</p>";
					}
					else if ($_SESSION["responsibilitycode"] == 123459106 or $_SESSION["responsibilitycode"] == 854723695125479 or $_SESSION["responsibilitycode"] == 123457106)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						if ($_SESSION['lng']=="English")
						{
							$vouchertypecode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../mis/vouchermenu.php?vouchertypecode='.$vouchertypecode_en.'">Receipt</a>';
                            $vouchertypecode_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../mis/vouchermenu.php?vouchertypecode='.$vouchertypecode_en.'">Payment</a>';
                            $vouchertypecode_en = fnEncrypt('3');
							echo '<li><a class="servicebar" href="../mis/vouchermenu.php?vouchertypecode='.$vouchertypecode_en.'">Contra</a>';
                            $vouchertypecode_en = fnEncrypt('4');
							echo '<li><a class="servicebar" href="../mis/vouchermenu.php?vouchertypecode='.$vouchertypecode_en.'">Purchase</a>';
                            $vouchertypecode_en = fnEncrypt('5');
							echo '<li><a class="servicebar" href="../mis/vouchermenu.php?vouchertypecode='.$vouchertypecode_en.'">Sale</a>';
                            $vouchertypecode_en = fnEncrypt('6');
							echo '<li><a class="servicebar" href="../mis/vouchermenu.php?vouchertypecode='.$vouchertypecode_en.'">Journal</a>';
							echo '<li><a class="servicebar" href="../ip_view/passbookheader.php">Pass Book</a>';
						}
						else
						{
							$vouchertypecode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../mis/vouchermenu.php?vouchertypecode='.$vouchertypecode_en.'">जमा वाउचर</a>';
                            $vouchertypecode_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../mis/vouchermenu.php?vouchertypecode='.$vouchertypecode_en.'">आदान वाउचर (पेमेंट)</a>';
                            $vouchertypecode_en = fnEncrypt('3');
							echo '<li><a class="servicebar" href="../mis/vouchermenu.php?vouchertypecode='.$vouchertypecode_en.'">प्रतिनोंद वाउचर (कॉन्ट्रा)</a>';
                            $vouchertypecode_en = fnEncrypt('4');
							echo '<li><a class="servicebar" href="../mis/vouchermenu.php?vouchertypecode='.$vouchertypecode_en.'">खरेदी वाउचर</a>';
                            $vouchertypecode_en = fnEncrypt('5');
							echo '<li><a class="servicebar" href="../mis/vouchermenu.php?vouchertypecode='.$vouchertypecode_en.'">विक्री वाउचर</a>';
                            $vouchertypecode_en = fnEncrypt('6');
							echo '<li><a class="servicebar" href="../mis/vouchermenu.php?vouchertypecode='.$vouchertypecode_en.'">जर्नल वाउचर</a>';
							echo '<li><a class="servicebar" href="../ip_view/passbookheader.php">पासबुक</a>';
						}
						echo '</ul>';
						echo "</p>";
					}
					// Cashier menu start
					else if ($_SESSION["responsibilitycode"] == 683297845124527)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						if ($_SESSION['lng']=="English")
						{
							$vouchertypecode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../mis/vouchermenu.php?vouchertypecode='.$vouchertypecode_en.'">Receipt</a>';
                            $vouchertypecode_en = fnEncrypt('3');
							echo '<li><a class="servicebar" href="../mis/vouchermenu.php?vouchertypecode='.$vouchertypecode_en.'">Contra</a>';
							echo '<li><a class="servicebar" href="../ip_view/voucherapproval_daily.php">Approval</a>';
						}
						else
						{
							$vouchertypecode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../mis/vouchermenu.php?vouchertypecode='.$vouchertypecode_en.'">जमा वाउचर</a>';

                            $vouchertypecode_en = fnEncrypt('3');
							echo '<li><a class="servicebar" href="../mis/vouchermenu.php?vouchertypecode='.$vouchertypecode_en.'">प्रतिनोंद वाउचर (कॉन्ट्रा)</a>';
                            echo '<li><a class="servicebar" href="../ip_view/voucherapproval_daily.php">मान्यता</a>';
						}
						echo '</ul>';
						echo "</p>";
					}

					// casher menu end

					// Checker menu start
					else if ($_SESSION["responsibilitycode"] == 123461861)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						if ($_SESSION['lng']=="English")
						{
							echo '<li><a class="servicebar" href="../ip_view/voucherapproval_daily.php">Approval</a>';
						}
						else
						{
                            echo '<li><a class="servicebar" href="../ip_view/voucherapproval_daily.php">मान्यता</a>';
						}
						echo '</ul>';
						echo "</p>";
					}

					// Checker menu end

					// Chief Accountant menu start
					else if ($_SESSION["responsibilitycode"] == 123462178)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						if ($_SESSION['lng']=="English")
						{
							echo '<li><a class="servicebar" href="../ip_view/voucherapproval_daily.php">Approval</a>';
						}
						else
						{
                            echo '<li><a class="servicebar" href="../ip_view/voucherapproval_daily.php">मान्यता</a>';
						}
						echo '</ul>';
						echo "</p>";
					}

					// Checker menu end

					//MIS Reports
					else if ($_SESSION["responsibilitycode"] == 357451254865478)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						if ($_SESSION['lng']=="English")
						{
							$typecode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../op_view/accountchecklist_selection.php?typecode='.$typecode_en.'">Groupwise Account Checklist</a>';
							$typecode_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../op_view/accountchecklist_selection.php?typecode='.$typecode_en.'">Schedulewise Account Checklist</a>';
							//echo '<li><a class="servicebar" href="../op_view/daybook_selection.php">Daybook</a>';
							$bookcategorycode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../op_view/monthlybook_selection.php?bookcategorycode='.$bookcategorycode_en.'">Daybook View</a>';
							$categoryid_de = fnEncrypt('51');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Ledger View</a>';
							$categoryid_de = fnEncrypt('56');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">SubLedger View</a>';
							$categoryid_de = fnEncrypt('52');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Trial Balance View</a>';
							$categoryid_de = fnEncrypt('58');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Trial Balance Groupwise View</a>';
							$categoryid_de = fnEncrypt('57');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">SubLedger Balance List View</a>';
							$categoryid_de = fnEncrypt('53');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Schedule View</a>';
							$categoryid_de = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Periodical Daybook</a>';
							$categoryid_de = fnEncrypt('11');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Periodical Cashbook</a>';
							$categoryid_de = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Trial Balance Detailed</a>';
							$categoryid_de = fnEncrypt('3');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Trial Balance Groupwise (Op-Tr-Cl)</a>';
							$categoryid_de = fnEncrypt('33');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Trial Balance Groupwise (Closing)</a>';
							$categoryid_de = fnEncrypt('34');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Journal Register</a>';
							$categoryid_de = fnEncrypt('35');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Sugarsale Journal Register</a>';
							$categoryid_de = fnEncrypt('4');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Main Ledger</a>';
							$categoryid_de = fnEncrypt('5');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Ledger</a>';
							$categoryid_de = fnEncrypt('55');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Bank Cheque Issue Register</a>';
							$categoryid_de = fnEncrypt('6');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Subledger</a>';
							$categoryid_de = fnEncrypt('60');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Multi Subledger</a>';
							$categoryid_de = fnEncrypt('10');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Subledger Balance List</a>';
							$categoryid_de = fnEncrypt('54');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Cash Position</a>';
							$categoryid_de = fnEncrypt('7');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Schedule</a>';
							$categoryid_de = fnEncrypt('8');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Profit and Loss Statement</a>';
							$categoryid_de = fnEncrypt('9');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Balancesheet</a>';
							$categoryid_de = fnEncrypt('59');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">Individual Ledger Balance List</a>';
						}
						else
						{
							echo '<p style="color:Brown">चेकलिस्ट</p>';
							$typecode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../op_view/accountchecklist_selection.php?typecode='.$typecode_en.'">चेकलिस्ट ग्रुपवार खाते</a>';
							$typecode_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../op_view/accountchecklist_selection.php?typecode='.$typecode_en.'">चेकलिस्ट परिशिष्ठवार खाते</a>';
							echo '<p style="color:Brown">किर्द</p>';
							//echo '<li><a class="servicebar" href="../op_view/daybook_selection.php">रोजकिर्द</a>';
							$bookcategorycode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../op_view/monthlydaybook_selection.php?bookcategorycode='.$bookcategorycode_en.'">रोजकिर्द पाहणी</a>';
							$categoryid_de = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">रोजकिर्द कालावधीतील</a>';
							$categoryid_de = fnEncrypt('11');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">रोखकिर्द कालावधीतील</a>';
							$categoryid_de = fnEncrypt('54');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">कॅश पोजिशन</a>';
							echo '<p style="color:Brown">खतावणी</p>';
							$categoryid_de = fnEncrypt('51');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">लेजर खतावणी पाहणी</a>';
							$categoryid_de = fnEncrypt('4');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">लेजर मुख्य खतावणी</a>';
							$categoryid_de = fnEncrypt('5');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">लेजर विस्तृत खतावणी</a>';
							$categoryid_de = fnEncrypt('56');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">सबलेजर खतावणी पाहणी</a>';
							$categoryid_de = fnEncrypt('6');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">सबलेजर खतावणी</a>';
							$categoryid_de = fnEncrypt('60');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">मल्टी सबलेजर खतावणी</a>';
							$categoryid_de = fnEncrypt('57');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">सबलेजर शिल्लक यादी पाहणी</a>';
							$categoryid_de = fnEncrypt('59');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">वैयक्तिक लेजर शिल्लक यादी पाहणी</a>';
							
							$categoryid_de = fnEncrypt('10');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">सबलेजर शिल्लक यादी</a>';
							$categoryid_de = fnEncrypt('61');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">सबलेजर हंगाम शिल्लक यादी</a>';
							echo '<p style="color:Brown">रजिस्टर</p>';
							$categoryid_de = fnEncrypt('34');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">रजिस्टर जर्नल</a>';
							$categoryid_de = fnEncrypt('35');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">रजिस्टर साखर विक्री जर्नल </a>';
							$categoryid_de = fnEncrypt('55');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">रजिस्टर बँक चेक जावक</a>';
							echo '<p style="color:Brown">तेरीज</p>';
							$categoryid_de = fnEncrypt('52');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">तेरीज पत्रक लेजरवार पाहणी</a>';
							$categoryid_de = fnEncrypt('58');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">तेरीज पत्रक ग्रुपवार पाहणी</a>';
							$categoryid_de = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">तेरीज पत्रक लेजरवार विस्तृत</a>';
							$categoryid_de = fnEncrypt('3');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">तेरीज पत्रक ग्रुपवार (आरंभिची-व्यवहार-अखेरची शिल्लक)</a>';
							$categoryid_de = fnEncrypt('33');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">तेरीज पत्रक ग्रुपवार (अखेरची शिल्लक)</a>';
							echo '<p style="color:Brown">आर्थिक पत्रक</p>';
							$categoryid_de = fnEncrypt('53');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">शेड्युल पाहणी</a>';
							$categoryid_de = fnEncrypt('7');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">शेड्युल</a>';
							$categoryid_de = fnEncrypt('8');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">नफातोटा पत्रक</a>';
							$categoryid_de = fnEncrypt('9');
							echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?categoryid='.$categoryid_de.'">ताळेबंद</a>';
						}
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