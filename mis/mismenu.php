<?php
	require("../info/phpgetlogin.php");
?>
<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8"></meta>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="./js/w3.css">
		<title>MIS Menu</title>
		<style type="text/css">
			body
			{
				background-color: #c44;
			}
			header
			{
				background-color: #fff;
				min-height: 50px;
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
				color: #fc8;
				line-height: 30px;
			}
			article
			{
				background-color: #fc8;
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
				color: #fc8;
			}
			a.servicebar
			{
				color: #060;
			}
			footer
			{
				float: bottom;
				color: #eee;
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
		
		<script src="./js/1.11.0/jquery.min.js">
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
				<ul class="navbar">
					<li><a class="navbar" href="../mis/mis.php">MIS Home</a><br/>
					<li><a class="navbar" href="../sqlproc/logout.php">Log Out</a><br/>
				</ul>
			</nav>
			<article class="w3-container">
				<section>
				<?php
				if ($_SESSION["responsibilitycode"] == 621478512368915)
				{
				echo "<p>";
					echo '<ul class="servicebar">';
						echo '<li><a class="servicebar" href="../factory/'.$_SESSION["factoryhomepage"].'/'.$_SESSION["factoryhomepage"].'.php">'.$_SESSION["factoryhomepage"].'</a>';
						echo '<li><a class="servicebar" href="../mis/createuser.php">Create User</a>';
						echo '<li><a class="servicebar" href="../mis/changepassword.php">Change Password</a>';
						echo '<li><a class="servicebar" href="../mis/grantuserresponsibility.php">Grant User Responsibility</a>';
						echo '<li><a class="servicebar" href="../mis/revokeuserresponsibility.php">Revoke User Responsibility</a>';
						echo '<li><a class="servicebar" href="../mis/resetuserpassword.php">Reset User Password</a>';
						echo '<li><a class="servicebar" href="../mis/removemisuser.php">Remove MIS User</a>';
						echo '<li><a class="servicebar" href="../mis/misuserlist.php">MIS User List</a>';
					echo '</ul>';
				echo '</p>';
				}
				elseif ($_SESSION["responsibilitycode"] == 785236954125917)
				{
				echo "<p>";
					echo '<ul class="servicebar">';
						echo '<li><a class="servicebar" href="../factory/'.$_SESSION["factoryhomepage"].'/'.$_SESSION["factoryhomepage"].'.php">'.$_SESSION["factoryhomepage"].'</a>';
						echo '<li><a class="servicebar" href="../mis/createuser.php">Create User</a>';
						echo '<li><a class="servicebar" href="../mis/changepassword.php">Change Password</a>';
						echo '<li><a class="servicebar" href="../mis/grantuserresponsibility.php">Grant User Responsibility</a>';
						echo '<li><a class="servicebar" href="../mis/revokeuserresponsibility.php">Revoke User Responsibility</a>';
					echo '</ul>';
				echo '</p>';	
				}
				else
				{
				echo "<p>";
					echo '<ul class="servicebar">';
						echo '<li><a class="servicebar" href="../factory/'.$_SESSION["factoryhomepage"].'/'.$_SESSION["factoryhomepage"].'.php">'.$_SESSION["factoryhomepage"].'</a>';
						echo '<li><a class="servicebar" href="../mis/changepassword.php">Change Password</a>';
					echo '</ul>';
				echo "</p>	";
				}
				?>					
				</section>
			</article>
			<footer>
				Copyright @2015 Swapp Software Application.<br/>All Rights Reserved.
			</footer>
	</body>
</html>