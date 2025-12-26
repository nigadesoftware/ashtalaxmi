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
					<li><a class="navbar" href="../mis/entitymenu.php">Entity Menu</a>
					<li><a class="navbar" href="../mis/selectresponsibility.php">Switch Responsibility</a><br/>
					<li><a class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>
							
	            </ul>
        	</nav>
			<article class="w3-container">
				<section>
				<?php
					$vouchertypecode_de = fnDecrypt($_GET['vouchertypecode']);
					if ($_SESSION["responsibilitycode"] == 123459106 or $_SESSION["responsibilitycode"] == 854723695125479 or $_SESSION["responsibilitycode"] ==123457106 or $_SESSION["responsibilitycode"] ==683297845124527)
					{
						$connection=swapp_connection();
                        $query = "select 
                        vouchersubtypecode
                        ,vouchersubtypenameeng
                        ,vouchersubtypenameuni
                        from vouchersubtype where 
                        vouchertypecode = ".$vouchertypecode_de;
                        $result = oci_parse($connection, $query);
                        $r = oci_execute($result);
                        echo "<p>";
						echo '<ul class="servicebar">';

                        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            if ($_SESSION['lng']=="English")
                            {
                                echo '<li><a class="servicebar" href="../ip_view/voucherheader.php?vouchersubtypecode='.fnEncrypt($row['VOUCHERSUBTYPECODE']).'">'.$row['VOUCHERSUBTYPENAMEENG'].'</a>';
                            }
                            else
                            {
                                echo '<li><a class="servicebar" href="../ip_view/voucherheader.php?vouchersubtypecode='.fnEncrypt($row['VOUCHERSUBTYPECODE']).'">'.$row['VOUCHERSUBTYPENAMEUNI'].'</a>';
                            }
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