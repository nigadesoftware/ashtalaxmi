<?php
	header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
?>
<!DOCTYPE html>
<html>
<head>
		<link rel="shortcut icon" href="favicon.ico">
		<meta charset="utf-8"></meta>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="../css/w3.css">
		<title>Home</title>
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
				font-family: Arial;
				font-size: 19px;
			}
			nav
			{
				width: 300px;
				float: left;
				list-style-type: none;
				font-family: verdana;
				font-size: 15px;
				color: #f48;
				line-height: 30px;
			}
			article
			{
				background-color: #f66;
				display: table;
				margin-left: 0px;
				padding-left: 3px;
				font-family: Arial Unicode Ms;
				font-size: 15px;
			}
			section
			{
				margin-left: 0px;
				margin-right: 3px;
				float: left;
				text-align: left;
				color: #000;
				line-height: 23px;
			}
			a.navbar
			{
				color: #f48;
			}
			a.servicebar
			{
				color: #b00;
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
			 setInterval(function(){cache_clear()},36000000);
			 });
			 function cache_clear()
			{
			 window.location.reload(true);
			}
		</script>
</head>
<body>
		<header class="w3-container">
			<div><img src="./img/swapp_namelogo.png" width="93px" height="31px"></div></br>
			<div><img src="./img/rawmaterial.png" width="91px" height="25px"></div>
		</header>
		<nav "w3-container">
			<ul class="navbar">
				<?php
					//session_start();
	    			if (isset($_SESSION['usersname']))
					{
						//header("location: ../mis/mismenu.php");
						echo '<li><a class="navbar" href="./mis/mismenu.php">Menu</a>';
					}
					else
					{
						echo '<li><a class="navbar" href="./menu.php">Menu</a>';
					}
				?>
			</ul>
		</nav>
		<article class="w3-container">
			<section>
				<ul class="servicebar">
					<li>We are professionally managed company to begin with
					an approach to serve with the credibility. 
					<li>Motivated and focused on specific domains.
					<li>Use of advanced technology to achieve the benefits.
					<li>Implementation of custom techniques.
					<li>Assurance of returns in terms of satisfaction. 
					<li>Consistency with the years of experience.
				</ul>
			</section>
		</article>
		<footer>
		</footer>
</body>
</html>