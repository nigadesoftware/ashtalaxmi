<?php
	require("../info/phpgetlogin.php");
	require("../info/ncryptdcrypt.php");
	require("../info/swapproutine.php");
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
	                <li><a class="navbar" href="../mis/usermenu.php">User Menu</a>
	            </ul>
        	</nav>
			<article class="w3-container">
				<section>
				<?php
					$finalreportperiodid_en = fnEncrypt($_SESSION['finalreportperiodid']);
					//Petrol Pump Master Addition
					if ($_SESSION["responsibilitycode"] == 123457506)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
							echo '<li><a class="servicebar" href="../../../../../index.php">Home</a><br/>';
							echo '<li><a class="servicebar" href="../mis/usermenu.php">User Menu</a><br/>';
							$namecategoryid_en = fnEncrypt(475156235);
							echo '<li><a class="servicebar" href="../ip_view/masterbase.php?namecategoryid='.$namecategoryid_en.'">Shift</a>';
							$namecategoryid_en = fnEncrypt(623542547);
							echo '<li><a class="servicebar" href="../ip_view/masterbase.php?namecategoryid='.$namecategoryid_en.'">Customer Type</a>';
							$namecategoryid_en = fnEncrypt(621451254);
							echo '<li><a class="servicebar" href="../ip_view/masterbase.php?namecategoryid='.$namecategoryid_en.'">Petrol Pump</a>';
							echo '<li><a class="servicebar" href="../ip_view/item.php">Item</a>';
							echo '<li><a class="servicebar" href="../ip_view/customer.php">Customer</a>';
							echo '<li><a class="servicebar" href="../ip_view/supplier.php">Supplier</a>';
							echo '<li><a class="servicebar" href="../ip_view/rate.php">Rate</a>';
							echo '<li><a class="servicebar" href="../ip_view/pump.php">Pump</a>';
							echo '<li><a class="servicebar" href="../mis/selectresponsibility.php">Switch Responsibility</a><br/>';
							echo '<li><a class="servicebar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
						echo '</ul>';
						echo "</p>";
					}
					//Petrol Pump Master Alteration
					elseif ($_SESSION["responsibilitycode"] == 123457206)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
							echo '<li><a class="servicebar" href="../../../../../index.php">Home</a><br/>';
							echo '<li><a class="servicebar" href="../mis/usermenu.php">User Menu</a><br/>';
							$namecategoryid_en = fnEncrypt(654123874);
							echo '<li><a class="servicebar" href="../ip_view/masterbase.php?namecategoryid='.$namecategoryid_en.'">Sugar Factory</a>';
							echo '<li><a class="servicebar" href="../ip_view/masterbase.php?namecategoryid='.$namecategoryid_en.'">Customer Type</a>';
							$namecategoryid_en = fnEncrypt(621451254);
							echo '<li><a class="servicebar" href="../ip_view/item.php">Item</a>';
							echo '<li><a class="servicebar" href="../ip_view/customer.php">Customer</a>';
							echo '<li><a class="servicebar" href="../ip_view/supplier.php">Supplier</a>';
							echo '<li><a class="servicebar" href="../ip_view/rate.php">Rate</a>';
							echo '<li><a class="servicebar" href="../ip_view/pump.php">Pump</a>';
							echo '<li><a class="servicebar" href="../mis/selectresponsibility.php">Switch Responsibility</a><br/>';
							echo '<li><a class="servicebar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
						echo '</ul>';
						echo "</p>";
					}
					//Petrol Pump Transaction Addition
					elseif ($_SESSION["responsibilitycode"] == 123457306 or $_SESSION["responsibilitycode"] == 123457406)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
							echo '<li><a class="servicebar" href="../../../../../index.php">Home</a><br/>';
							echo '<li><a class="servicebar" href="../mis/usermenu.php">User Menu</a><br/>';
							$shiftcode_en = fnEncrypt('248804088');
							echo '<li><a class="servicebar" href="../ip_view/petrolpumpmenu.php?shiftcode='.$shiftcode_en.'">First Shift</a><br/>';
							$shiftcode_en = fnEncrypt('248804235');
							echo '<li><a class="servicebar" href="../ip_view/petrolpumpmenu.php?shiftcode='.$shiftcode_en.'">Second Shift</a><br/>';
							$shiftcode_en = fnEncrypt('248804382');
							echo '<li><a class="servicebar" href="../ip_view/petrolpumpmenu.php?shiftcode='.$shiftcode_en.'">Third Shift</a><br/>';
							echo '<li><a class="servicebar" href="../mis/selectresponsibility.php">Switch Responsibility</a><br/>';
							echo '<li><a class="servicebar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
						echo '</ul>';
						echo "</p>";
					}
					//Reports
					elseif ($_SESSION["responsibilitycode"] == 451278369852145)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
							echo '<li><a class="servicebar" href="../../../../../index.php">Home</a><br/>';
							echo '<li><a class="servicebar" href="../mis/usermenu.php">User Menu</a><br/>';
							echo '<li><a class="servicebar" href="../op_view/daywiseshiftwisesale_selection.php">Daywise Shiftwise Sale</a><br/>';
							echo '<li><a class="servicebar" href="../op_view/periodicalvehiclesale_selection.php">Periodical Vehiclewise Sale Detail</a><br/>';
							echo '<li><a class="servicebar" href="../op_view/periodicalcreditsalesum_selection.php">Periodical Transporter Credit Sale Summary</a><br/>';
							echo '<li><a class="servicebar" href="../mis/selectresponsibility.php">Switch Responsibility</a><br/>';
							echo '<li><a class="servicebar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
						echo '</ul>';
						echo "</p>";
					}
					//MIS Reports
					elseif ($_SESSION["responsibilitycode"] == 357451254865478)
					{
						echo "<p>";
						echo '<ul class="servicebar">';
							echo '<li><a class="servicebar" href="../../../../../index.php">Home</a><br/>';
							echo '<li><a class="servicebar" href="../mis/usermenu.php">User Menu</a><br/>';
							echo '<li><a class="servicebar" href="../op_view/daywiseshiftwisesale_selection.php">Daywise Shiftwise Sale</a><br/>';
							echo '<li><a class="servicebar" href="../op_view/periodicalvehiclesale_selection.php">Periodical Vehiclewise Sale Detail</a><br/>';
							echo '<li><a class="servicebar" href="../op_view/periodicalcreditsalesum_selection.php">Periodical Transporter Credit Sale Summary</a><br/>';
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