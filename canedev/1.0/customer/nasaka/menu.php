<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8"></meta>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="../css/w3.css">
		<title>Menu</title>
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
				color: #f48;
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
				color: #000;
				line-height: 23px;
			}
			a.navbar
			{
				color: #f48;
			}
			a.servicebar
			{
				color: #000;
			}
			footer
			{
				float: left;
				color: #8a4;
				font-family: verdana;
				font-size: 12px;
				background-color: #fff;
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
			 setInterval(function(){cache_clear()},360000);
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
				<li><a class="navbar" href="./index.php">Home</a><br/>
				<li><a class="navbar" href="./mis/login.php?flag=1">Login</a>
				<li><a class="navbar" href="./site/aboutus.php">About Us</a>
				<li><a class="navbar" href="./site/contactus.php">Contact Us</a>
			</ul>
		</nav>
		<article class="w3-container">
			<section>
			<p>
				<ul class="servicebar">
				</ul>
			</p>					
			</section>
		</article>
		<footer>
		</footer>
	</body>
</html>