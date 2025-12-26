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
					<li><a class="navbar" href="../mis/entitymenu.php">Entity Menu</a><br/>
					<li><a class="navbar" href="../mis/usermenu.php">User Menu</a>
					<li><a class="navbar" href="../mis/selectresponsibility.php">Switch Responsibility</a><br/>
					<li><a class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>
							
	            </ul>
        	</nav>
			<article class="w3-container">
				<section>
				<?php
					$activitycode_de = fnDecrypt($_GET['activitycode']);
					$weightcategorycode_de = fnDecrypt($_GET['weightcategorycode']);
					if (isset($_GET['vehiclecategorycode']))
					{
						$vehiclecategorycodedefault_de = fnDecrypt($_GET['vehiclecategorycode']);
					}
					else
					{
						$vehiclecategorycodedefault_de = 0;
					}
					$vehiclecategorycodedefault_en = fnEncrypt($vehiclecategorycodedefault_de);
					$weightcategorycode_en = fnEncrypt($weightcategorycode_de);
					if (isset($_GET['flag']))
					{
						$flag_de = fnDecrypt($_GET['flag']);
					}
					else
					{
						$flag_de = '';
					}
					if ($activitycode_de==1)
					{
						if ($_SESSION['lng']=="English")
						{
							if ($weightcategorycode_de==1 and $flag_de=='Query')
							{
								$weight = 'Load Weight';
								$flag='Query';
							}
							elseif ($weightcategorycode_de==1 and $flag_de!='Query')
							{
								$weight = 'Load Weight';
								$flag='';
							}
							elseif ($weightcategorycode_de==2)
							{
								$weight = 'Empty Weight';
								$flag='Query';
							}
							elseif ($weightcategorycode_de==3 and $flag_de=='Query')
							{
								$weight = 'Manual';
								$flag='Query';
							}
							elseif ($weightcategorycode_de==3 and $flag_de!='Query')
							{
								$weight = 'Manual';
							}
							elseif ($weightcategorycode_de==4 and $flag_de=='Query')
							{
								$weight = 'Weight Slip';
								$flag='Query';
							}
							elseif ($weightcategorycode_de==4 and $flag_de!='Query')
							{
								$weight = 'Weight Slip';
							}
							$flag_en = fnEncrypt($flag);
							$vehiclecategorycode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../ip_view/weightslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&weightcategorycode='.$weightcategorycode_en.'&flag='.$flag_en.'">Truck '.$weight.'</a>';
							$vehiclecategorycode_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../ip_view/weightslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&weightcategorycode='.$weightcategorycode_en.'&flag='.$flag_en.'">Tractor '.$weight.'</a>';
							$vehiclecategorycode_en = fnEncrypt('3');
							echo '<li><a class="servicebar" href="../ip_view/weightslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&weightcategorycode='.$weightcategorycode_en.'&flag='.$flag_en.'">Bulluckcart '.$weight.'</a>';
							$vehiclecategorycode_en = fnEncrypt('4');
							echo '<li><a class="servicebar" href="../ip_view/weightslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&weightcategorycode='.$weightcategorycode_en.'&flag='.$flag_en.'">Jugad '.$weight.'</a>';
							if ($vehiclecategorycodedefault_de>0)
							{
								header('location:../ip_view/weightslip.php?vehiclecategorycode='.$vehiclecategorycodedefault_en.'&weightcategorycode='.$weightcategorycode_en.'&flag='.$flag_en);
							}
						}
						else
						{
							if ($weightcategorycode_de==1 and $flag_de=='Query')
							{
								$weight = 'भरगाडी';
								$flag='Query';
							}
							elseif ($weightcategorycode_de==1 and $flag_de!='Query')
							{
								$weight = 'भरगाडी';
								$flag='';
							}
							elseif ($weightcategorycode_de==2)
							{
								$weight = 'रिकामीगाडी';
								$flag='Query';
							}
							elseif ($weightcategorycode_de==3 and $flag_de=='Query')
							{
								$weight = 'मॅन्यूयल';
								$flag='Query';
							}
							elseif ($weightcategorycode_de==3 and $flag_de!='Query')
							{
								$weight = 'मॅन्यूयल';
							}
							elseif ($weightcategorycode_de==4)
							{
								$weight = 'वजन स्लीप';
								$flag='Query';
							}
							

							$flag_en = fnEncrypt($flag);
							$vehiclecategorycode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../ip_view/weightslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&weightcategorycode='.$weightcategorycode_en.'&flag='.$flag_en.'">ट्रक '.$weight.'</a>';
							$vehiclecategorycode_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../ip_view/weightslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&weightcategorycode='.$weightcategorycode_en.'&flag='.$flag_en.'">ट्रॅक्टर '.$weight.'</a>';
							$vehiclecategorycode_en = fnEncrypt('3');
							echo '<li><a class="servicebar" href="../ip_view/weightslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&weightcategorycode='.$weightcategorycode_en.'&flag='.$flag_en.'">टायरगाडी '.$weight.'</a>';
							$vehiclecategorycode_en = fnEncrypt('4');
							echo '<li><a class="servicebar" href="../ip_view/weightslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&weightcategorycode='.$weightcategorycode_en.'&flag='.$flag_en.'">जुगाड '.$weight.'</a>';
							if ($vehiclecategorycodedefault_de>0)
							{
								header('location:../ip_view/weightslip.php?vehiclecategorycode='.$vehiclecategorycodedefault_en.'&weightcategorycode='.$weightcategorycode_en.'&flag='.$flag_en);
							}
						}
					}
					elseif ($activitycode_de==2)
					{
						if ($_SESSION['lng']=="English")
						{
							$weight = 'Fieldslip';
							$flag='';
							$flag_en = fnEncrypt($flag);
							$vehiclecategorycode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../ip_view/fieldslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&weightcategorycode='.$weightcategorycode_en.'&flag='.$flag_en.'">Truck '.$weight.'</a>';
							$vehiclecategorycode_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../ip_view/fieldslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&weightcategorycode='.$weightcategorycode_en.'&flag='.$flag_en.'">Tractor '.$weight.'</a>';
							$vehiclecategorycode_en = fnEncrypt('3');
							echo '<li><a class="servicebar" href="../ip_view/fieldslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&weightcategorycode='.$weightcategorycode_en.'&flag='.$flag_en.'">Bulluckcart '.$weight.'</a>';
							$vehiclecategorycode_en = fnEncrypt('4');
							echo '<li><a class="servicebar" href="../ip_view/fieldslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&weightcategorycode='.$weightcategorycode_en.'&flag='.$flag_en.'">Jugad '.$weight.'</a>';
							if ($vehiclecategorycodedefault_de>0)
							{
								header('location:../ip_view/fieldslip.php?vehiclecategorycode='.$vehiclecategorycodedefault_en.'&weightcategorycode='.$weightcategorycode_en.'&flag='.$flag_en);
							}
						}
						else
						{
							$weight = 'फिल्डस्लीप';
							$flag='';
							$flag_en = fnEncrypt($flag);
							$vehiclecategorycode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../ip_view/fieldslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&weightcategorycode='.$weightcategorycode_en.'&flag='.$flag_en.'">ट्रक '.$weight.'</a>';
							$vehiclecategorycode_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../ip_view/fieldslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&weightcategorycode='.$weightcategorycode_en.'&flag='.$flag_en.'">ट्रॅक्टर '.$weight.'</a>';
							$vehiclecategorycode_en = fnEncrypt('3');
							echo '<li><a class="servicebar" href="../ip_view/fieldslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&weightcategorycode='.$weightcategorycode_en.'&flag='.$flag_en.'">टायरगाडी '.$weight.'</a>';
							$vehiclecategorycode_en = fnEncrypt('4');
							echo '<li><a class="servicebar" href="../ip_view/fieldslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&weightcategorycode='.$weightcategorycode_en.'&flag='.$flag_en.'">जुगाड '.$weight.'</a>';
							if ($vehiclecategorycodedefault_de>0)
							{
								header('location:../ip_view/fieldslip.php?vehiclecategorycode='.$vehiclecategorycodedefault_en.'&weightcategorycode='.$weightcategorycode_en.'&flag='.$flag_en);
							}
						}
					}
					elseif ($activitycode_de==3)
					{
						if ($_SESSION['lng']=="English")
						{
							$weight = 'Todslip';
							$flag='';
							$flag_en = fnEncrypt($flag);
							$vehiclecategorycode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../ip_view/todslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&flag='.$flag_en.'">Truck '.$weight.'</a>';
							$vehiclecategorycode_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../ip_view/todslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&flag='.$flag_en.'">Tractor '.$weight.'</a>';
							$vehiclecategorycode_en = fnEncrypt('3');
							echo '<li><a class="servicebar" href="../ip_view/todslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&flag='.$flag_en.'">Bulluckcart '.$weight.'</a>';
							$vehiclecategorycode_en = fnEncrypt('4');
							echo '<li><a class="servicebar" href="../ip_view/todslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&flag='.$flag_en.'">Jugad '.$weight.'</a>';
							if ($vehiclecategorycodedefault_de>0)
							{
								header('location:../ip_view/todslip.php?vehiclecategorycode='.$vehiclecategorycodedefault_en.'&flag='.$flag_en);
							}
						}
						else
						{
							$weight = 'तोडस्लीप';
							$flag='';
							$flag_en = fnEncrypt($flag);
							$vehiclecategorycode_en = fnEncrypt('1');
							echo '<li><a class="servicebar" href="../ip_view/todslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&flag='.$flag_en.'">ट्रक '.$weight.'</a>';
							$vehiclecategorycode_en = fnEncrypt('2');
							echo '<li><a class="servicebar" href="../ip_view/todslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&flag='.$flag_en.'">ट्रॅक्टर '.$weight.'</a>';
							$vehiclecategorycode_en = fnEncrypt('3');
							echo '<li><a class="servicebar" href="../ip_view/todslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&flag='.$flag_en.'">टायरगाडी '.$weight.'</a>';
							$vehiclecategorycode_en = fnEncrypt('4');
							echo '<li><a class="servicebar" href="../ip_view/todslip.php?vehiclecategorycode='.$vehiclecategorycode_en.'&flag='.$flag_en.'">जुगाड '.$weight.'</a>';
							if ($vehiclecategorycodedefault_de>0)
							{
								header('location:../ip_view/todslip.php?vehiclecategorycode='.$vehiclecategorycodedefault_en.'&flag='.$flag_en);
							}
						}
					}
				?>					
				</section>
			</article>
			<footer>
				<div class="copyright">Copyright &copy;2020 Nigade Software Technologies (opc) Private Limited</div>
			</footer>
	</body>
</html>