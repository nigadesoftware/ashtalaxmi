<?php
	require("../info/phpgetlogin.php");
	require("../info/ncryptdcrypt.php");
	require("../info/swapproutine.php");
    //vouchervalidation();
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
		<link rel="stylesheet" href="../css/swapp123.css">
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
				color: #f48;
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
		<script src="http://ajax.googleapi_mysqls.com/ajax/libs/jquery/1.11.0/jquery.min.js">
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
					//HT Contract Master Addition
					if ($_SESSION["responsibilitycode"] == 452365784154249)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
							echo '<li><a class="servicebar" href="../../../../../index.php">Home</a><br/>';
							echo '<li><a class="servicebar" href="../mis/usermenu.php">User Menu</a><br/>';
							//echo '<li><a class="servicebar" href="../data/servicecontractor.php">Service Contractor</a>';
							$namecategoryid_en = fnEncrypt(398541725);
							echo '<li><a class="servicebar" href="../data/masterbase.php?namecategoryid='.$namecategoryid_en.'">Transportation Vehicle</a>';
							$namecategoryid_en = fnEncrypt(845125632);
							echo '<li><a class="servicebar" href="../data/masterbase.php?namecategoryid='.$namecategoryid_en.'">HT Contract Related Service</a>';
							$namecategoryid_en = fnEncrypt(671529934);
							echo '<li><a class="servicebar" href="../data/masterbase.php?namecategoryid='.$namecategoryid_en.'">Harvesting with Transportation upto Vehicle</a>';
							$namecategoryid_en = fnEncrypt(913742561);
							echo '<li><a class="servicebar" href="../data/masterbase.php?namecategoryid='.$namecategoryid_en.'">Caste</a>';
							$namecategoryid_en = fnEncrypt(489732156);
							echo '<li><a class="servicebar" href="../data/masterbase.php?namecategoryid='.$namecategoryid_en.'">Profession</a>';
							$namecategoryid_en = fnEncrypt(365214765);
							echo '<li><a class="servicebar" href="../data/masterbase.php?namecategoryid='.$namecategoryid_en.'">Document</a>';
							$namecategoryid_en = fnEncrypt(632541254);
							echo '<li><a class="servicebar" href="../data/masterbase.php?namecategoryid='.$namecategoryid_en.'">Property</a>';
							$namecategoryid_en = fnEncrypt(547812365);
							echo '<li><a class="servicebar" href="../data/masterbase.php?namecategoryid='.$namecategoryid_en.'">HT Item</a>';
							$namecategoryid_en = fnEncrypt(843265874);
							echo '<li><a class="servicebar" href="../data/masterbase.php?namecategoryid='.$namecategoryid_en.'">Official Responsibility</a>';
							$namecategoryid_en = fnEncrypt(325968741);
							echo '<li><a class="servicebar" href="../data/masterbase.php?namecategoryid='.$namecategoryid_en.'">Vehicle Manufacturer</a>';
							$namecategoryid_en = fnEncrypt(854126547);
							echo '<li><a class="servicebar" href="../data/masterbase.php?namecategoryid='.$namecategoryid_en.'">Trailor Manufacturer</a>';
							//echo '<li><a class="servicebar" href="../view/test1.php">Test1</a>';
							echo '<li><a class="servicebar" href="../mis/selectresponsibility.php">Switch Responsibility</a><br/>';
							echo '<li><a class="servicebar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
						echo '</ul>';
						echo "</p>";
					}
					//HT Contract Master Alteration
					elseif ($_SESSION["responsibilitycode"] == 658741245893258)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
							echo '<li><a class="servicebar" href="../../../../../index.php">Home</a><br/>';
							echo '<li><a class="servicebar" href="../mis/usermenu.php">User Menu</a><br/>';
							//echo '<li><a class="servicebar" href="../data/servicecontractor.php">Service Contractor</a>';
							$namecategoryid_en = fnEncrypt(398541725);
							echo '<li><a class="servicebar" href="../data/masterbase.php?namecategoryid='.$namecategoryid_en.'">Transportation Vehicle</a>';
							$namecategoryid_en = fnEncrypt(845125632);
							echo '<li><a class="servicebar" href="../data/masterbase.php?namecategoryid='.$namecategoryid_en.'">HT Contract Related Service</a>';
							$namecategoryid_en = fnEncrypt(671529934);
							echo '<li><a class="servicebar" href="../data/masterbase.php?namecategoryid='.$namecategoryid_en.'">Harvesting with Transportation upto Vehicle</a>';
							$namecategoryid_en = fnEncrypt(913742561);
							echo '<li><a class="servicebar" href="../data/masterbase.php?namecategoryid='.$namecategoryid_en.'">Caste</a>';
							$namecategoryid_en = fnEncrypt(489732156);
							echo '<li><a class="servicebar" href="../data/masterbase.php?namecategoryid='.$namecategoryid_en.'">Profession</a>';
							$namecategoryid_en = fnEncrypt(365214765);
							echo '<li><a class="servicebar" href="../data/masterbase.php?namecategoryid='.$namecategoryid_en.'">Document</a>';
							$namecategoryid_en = fnEncrypt(632541254);
							echo '<li><a class="servicebar" href="../data/masterbase.php?namecategoryid='.$namecategoryid_en.'">Property</a>';
							$namecategoryid_en = fnEncrypt(547812365);
							echo '<li><a class="servicebar" href="../data/masterbase.php?namecategoryid='.$namecategoryid_en.'">HT Item</a>';
							$namecategoryid_en = fnEncrypt(843265874);
							echo '<li><a class="servicebar" href="../data/masterbase.php?namecategoryid='.$namecategoryid_en.'">Official Responsibility</a>';
							echo '<li><a class="servicebar" href="../mis/selectresponsibility.php">Switch Responsibility</a><br/>';
							echo '<li><a class="servicebar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
						echo '</ul>';
						echo "</p>";
					}
					//HT Contract Transaction Addition
					elseif ($_SESSION["responsibilitycode"] == 451230287895415)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
							echo '<li><a class="servicebar" href="../../../../../index.php">Home</a><br/>';
							echo '<li><a class="servicebar" href="../mis/usermenu.php">User Menu</a><br/>';
							//echo '<li><a class="servicebar" href="../data/servicecontractor.php">Service Contractor</a>';
							echo '<li><a class="servicebar" href="../data/contract.php">Contract</a>';
							echo '<li><a class="servicebar" href="../data/contractmapping.php">Contract Mapping</a>';
							echo '<li><a class="servicebar" href="../data/harvestinglabouradvancerate.php">Harvesting Labour Advance Rate</a>';
							$installment_en = fnEncrypt('0');
							echo '<li><a class="servicebar" href="../view/harcontadv_view.php?installment='.$installment_en.'">Harvesting Labour Advance Report(All Installement)</a>';
							$installment_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../view/harcontadv_view.php?installment='.$installment_en.'">Harvesting Labour Advance Report(First Installement)</a>';
							$installment_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../view/harcontadv_view.php?installment='.$installment_en.'">Harvesting Labour Advance Report(Second Installement)</a>';
							$installment_en = fnEncrypt('3');
							echo '<li><a class="servicebar" href="../view/harcontadv_view.php?installment='.$installment_en.'">Harvesting Labour Advance Report(Third Installement)</a>';
							echo '<li><a class="servicebar" href="../mis/selectresponsibility.php">Switch Responsibility</a><br/>';
							echo '<li><a class="servicebar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
						echo '</ul>';
						echo "</p>";
					}
					//HT Contract Transaction Alteration
					elseif ($_SESSION["responsibilitycode"] == 475124562358965)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						echo '<li><a class="servicebar" href="../../../../../index.php">Home</a><br/>';
						echo '<li><a class="servicebar" href="../mis/usermenu.php">User Menu</a><br/>';
						//echo '<li><a class="servicebar" href="../data/servicecontractor.php">Service Contractor</a>';
						echo '<li><a class="servicebar" href="../data/contract.php">Contract</a>';
						echo '<li><a class="servicebar" href="../data/contractmapping.php">Contract Mapping</a>';
						echo '<li><a class="servicebar" href="../data/harvestinglabouradvancerate.php">Harvesting Labour Advance Rate</a>';
						echo '<li><a class="servicebar" href="../view/harvestinglabouradvancerate_view.php">Harvesting Labour Advance Rate Report</a>';
						$installment_en = fnEncrypt('0');
						echo '<li><a class="servicebar" href="../view/harcontadv_view.php?installment='.$installment_en.'">Harvesting Labour Advance Report(All Installement)</a>';
						$installment_en = fnEncrypt('1');
						echo '<li><a class="servicebar" href="../view/harcontadv_view.php?installment='.$installment_en.'">Harvesting Labour Advance Report(First Installement)</a>';
						$installment_en = fnEncrypt('2');
						echo '<li><a class="servicebar" href="../view/harcontadv_view.php?installment='.$installment_en.'">Harvesting Labour Advance Report(Second Installement)</a>';
						$installment_en = fnEncrypt('3');
						echo '<li><a class="servicebar" href="../view/harcontadv_view.php?installment='.$installment_en.'">Harvesting Labour Advance Report(Third Installement)</a>';
						$reportcode_en = fnEncrypt('1');
						echo '<li><a class="servicebar" href="../view/setreportperiod.php?reportcode='.$reportcode_en.'">Harvesting Contractor Report</a>';
						echo '<li><a class="servicebar" href="../mis/selectresponsibility.php">Switch Responsibility</a><br/>';
						echo '<li><a class="servicebar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
						echo '</ul>';
						echo "</p>";
					}
				?>					
				</section>
			</article>
			<footer>
				<div class="copyright">This is developed and maintained by Swapp Software Application. Copyright &copy;2020 Nigade Software Technologies (opc) Private Limited</div>
			</footer>
	</body>
</html>