<?php
	require("../info/phpgetlogin.php");
?>
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
			<nav class="w3-container">
			</nav>
			<article class="w3-container">
				<section>
				<?php
				if ($_SESSION["responsibilitycode"] == 621478512368915)
				{
					echo "<p>";
						echo '<ul class="servicebar">';
							echo '<li><a class="servicebar" href="../index.php">Home</a><br/>';
							//echo '<li><a class="servicebar" href="../mis/changepassword.php">Change Password</a>';
							echo '<li><a class="servicebar" href="../data/user_list.php">User List</a>';
							echo '<li><a class="servicebar" href="../mis/selectresponsibility.php">Switch Responsibility</a><br/>';
							echo '<li><a class="servicebar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
						echo '</ul>';
					echo '</p>';
				}
				elseif ($_SESSION["responsibilitycode"] == 785236954125917)
				{
					echo "<p>";
						echo '<ul class="servicebar">';
							echo '<li><a class="servicebar" href="../index.php">Home</a><br/>';
							//echo '<li><a class="servicebar" href="../mis/changepassword.php">Change Password</a>';
							echo '<li><a class="servicebar" href="../data/user_list.php">User List</a>';
							echo '<li><a class="servicebar" href="../mis/selectresponsibility.php">Switch Responsibility</a><br/>';						
							echo '<li><a class="servicebar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
						echo '</ul>';
					echo '</p>';	
				}
				else
				{
					echo "<p>";
						echo '<ul class="servicebar">';
							echo '<li><a class="servicebar" href="../index.php">Home</a><br/>';
							echo '<li><a class="servicebar" href="../mis/selectentity_list.php">Select Entity</a>';
							//echo '<li><a class="servicebar" href="../mis/changepassword.php">Change Password</a>';
							echo '<li><a class="servicebar" href="../mis/selectresponsibility.php">Switch Responsibility</a><br/>';
							echo '<li><a class="servicebar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
						echo '</ul>';
					echo "</p>";
				}
				?>
				</section>
			</article>
			<footer>
			<div class="copyright">This is developed and maintained by Nigade Software Technologies (opc) Private Limited. Copyright &copy;2020 Nigade Software Technologies (opc) Private Limited</div>
			</footer>
	</body>
</html>