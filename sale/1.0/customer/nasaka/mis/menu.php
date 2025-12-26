<?php
	require("../info/phpgetlogin.php");
	require("../info/ncryptdcrypt.php");
	require("../info/swapproutine.php");
    vouchervalidation();
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
				//Finance Master Addition
				if ($_SESSION["responsibilitycode"] == 451230287895415)
				{
				echo "<p>";
					echo '<ul class="servicebar">';
						echo '<li><a class="servicebar" href="../index.php">Home</a><br/>';
						echo '<li><a class="servicebar" href="../mis/usermenu.php">User Menu</a><br/>';
						echo '<li><a class="servicebar" href="../mis/globalgroup_list.php">Global Group List</a>';
						echo '<li><a class="servicebar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
					echo '</ul>';
				echo "</p>";
				}
				?>					
				</section>
			</article>
			<footer>
			<div class="copyright">H&T Contract is marketed by Nirmala Enterprises to Someshwar SSK. This is developed and maintained by Swapp Software Application. Copyright &copy;2017 Swapp Software Application</div>
			</footer>
	</body>
</html>