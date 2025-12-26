<?php
	require("../info/phpgetlogin.php");
	require("../info/ncryptdcrypt.php");
	require("../info/routine.php");
	require_once("../../../../../info/crypto.php");
	if ($_SESSION['changedefaultusersettings'] == 'on')
	{
		$_SESSION['changedefaultusersettings'] = 'off';
	}
	$connection=swapp_connection();
	function tendercategry(&$connection,$goodscategorycode)
	{
		$query = "select tendercategorycode from goodscategory g where g.goodscategorycode=".$goodscategorycode;
		$result = oci_parse($connection, $query);
		$r = oci_execute($result,OCI_NO_AUTO_COMMIT);
		if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			return $row['TENDERCATEGORYCODE'];
		}
		else
		{
			return 0;
		}
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
					if (in_array($_SESSION["responsibilitycode"],array(123479345,123479662,123479979,123480296,123480613)))
					{
						$goodscategorycode = '1';
						$goodscategorycode_en = fnEncrypt($goodscategorycode);
						$productname='Retail';
					}
					elseif (in_array($_SESSION["responsibilitycode"],array(123469201,123469518,123469835,123470152,123470469)))
					{
						$goodscategorycode = '2';
						$goodscategorycode_en = fnEncrypt($goodscategorycode);
						$productname='Molasses';
					}
					elseif (in_array($_SESSION["responsibilitycode"],array(123471737,123472054,123472371,123472688,123473005)))
					{
						$goodscategorycode = '4';
						$goodscategorycode_en = fnEncrypt($goodscategorycode);
						$productname='Bagasse';
					}
					elseif (in_array($_SESSION["responsibilitycode"],array(123479355,123479672,123479989,123480296,123480623)))
					{
						$goodscategorycode = '5';
						$goodscategorycode_en = fnEncrypt($goodscategorycode);
						$productname='Pressmud';
					}
					elseif (in_array($_SESSION["responsibilitycode"],array(123479365,123479682,123479999,123480306,123480633)))
					{
						$goodscategorycode = '3';
						$goodscategorycode_en = fnEncrypt($goodscategorycode);
						$productname='Ash';
					}
					elseif (in_array($_SESSION["responsibilitycode"],array(123479375,123479692,123479109,123480316,123480643)))
					{
						$goodscategorycode = '6';
						$goodscategorycode_en = fnEncrypt($goodscategorycode);
						$productname='Compost';
					}
					elseif (in_array($_SESSION["responsibilitycode"],array(123509345,123509662,123509979,123500296,123980613)))
					{
						$goodscategorycode = '7';
						$goodscategorycode_en = fnEncrypt($goodscategorycode);
						$productname='Store Material';
					}
					elseif (in_array($_SESSION["responsibilitycode"],array(123499345,123499662,123499979,123490296,123490613)))
					{
						$goodscategorycode = '8';
						$goodscategorycode_en = fnEncrypt($goodscategorycode);
						$productname='Scrap';
					}
					$tendercategorycode = tendercategry($connection,$goodscategorycode);
					//Master Addition / alteration
					if (in_array($_SESSION["responsibilitycode"],array(123479345,123479662,123469201,123469518,123479365,123479682,123471737,123472054,123479355,123479672,123479375,123479692,123509345,123509662,123499345,123499662)))
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						echo '<li><a class="servicebar" href="../ip_view/finishedgoods.php?goodscategorycode='.$goodscategorycode_en.'">'.$productname.' Goods</a>';
						echo '<li><a class="servicebar" href="../ip_view/goodstaxrate.php?goodscategorycode='.$goodscategorycode_en.'">'.$productname.' Goods Tax Rate</a>';
						echo '<li><a class="servicebar" href="../ip_view/finishedgoodsratemaster.php?goodscategorycode='.$goodscategorycode_en.'">'.$productname.' Goods Rate</a>';
						//echo '<li><a class="servicebar" href="../ip_view/goodstaxrate.php?goodscategorycode='.$goodscategorycode_en.'">'.$productname.' Tax Rate</a>';
						echo '<li><a class="servicebar" href="../ip_view/goodspurchaser.php?goodscategorycode='.$goodscategorycode_en.'">'.$productname.' Goods Purchaser</a>';
						echo '</ul>';
						echo "</p>";
					}
					//Transaction Addition / alteration
					if (in_array($_SESSION["responsibilitycode"],array(123479979,123480296,123469835,123470152,123472371,123472688,123480296,123479979,123479989,123480296,123479999,123480306,123479109,123480316,123499979,123490296,123509979,123500296,123499979)))
					{
						echo "<p>";
						echo '<ul class="servicebar">';
							//echo '<li><a class="servicebar" href="../ip_view/goodssalepermission.php?goodscategorycode='.$goodscategorycode_en.'">Release Order</a>';
							if ($tendercategorycode==1)
							{
								echo '<li><a class="servicebar" href="../ip_view/saletenderheader.php?goodscategorycode='.$goodscategorycode_en.'">'.$productname.' Sale Tender</a>';
								echo '<li><a class="servicebar" href="../ip_view/salequotationheader.php?goodscategorycode='.$goodscategorycode_en.'">'.$productname.' Sale Quotation</a>';
								echo '<li><a class="servicebar" href="../ip_view/saleorderheader.php?goodscategorycode='.$goodscategorycode_en.'">'.$productname.' Sale Order</a>';
								echo '<li><a class="servicebar" href="../ip_view/saleinvoiceheader.php?goodscategorycode='.$goodscategorycode_en.'">'.$productname.' Sale Invoice</a>';
							}
							elseif ($tendercategorycode==2)
							{
								//echo '<li><a class="servicebar" href="../ip_view/finishedgoodsratemaster.php?goodscategorycode='.$goodscategorycode_en.'">'.$productname.' Finished Goods Rate</a>';
								//echo '<li><a class="servicebar" href="../ip_view/goodspurchaser.php?goodscategorycode='.$goodscategorycode_en.'">'.$productname.' Goods Purchaser</a>';
								echo '<li><a class="servicebar" href="../ip_view/saleinvoiceheader.php?goodscategorycode='.$goodscategorycode_en.'">'.$productname.' Sale Invoice</a>';
							}
							
						echo '</ul>';
						echo "</p>";
					}
				
					// Reports
					else if (in_array($_SESSION["responsibilitycode"],array(123470469,123473005,123480613,123480623,123480633,123480643,123490613,123980613)))
					{
						echo "<p>";
						echo '<ul class="servicebar">';
						$categorycode_en = fnEncrypt('2');
						echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?goodscategorycode='.$goodscategorycode_en.'&categorycode='.$categorycode_en.'">'.$productname.' Sale Register</a>';	
						$categorycode_en = fnEncrypt('1');
						echo '<li><a class="servicebar" href="../op_view/periodical_selection.php?goodscategorycode='.$goodscategorycode_en.'&categorycode='.$categorycode_en.'">'.$productname.' Sale Summary</a>';
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