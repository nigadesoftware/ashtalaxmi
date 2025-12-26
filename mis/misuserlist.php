<?php
    require("../info/phpgetlogin.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"></meta>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="./js/w3.css">
		<title>MIS User List</title>
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
				color: #fc8;
				line-height: 30px;
			}
			a
			{
				color: #000;
			}
			article
			{
				background-color: #fc8;
				display: table;
				margin-left: 0px;
				padding-left: 10px;
				font-family: Verdana;
				font-size: 15px;
			}
			section
			{
				margin-left: 0px;
				margin-right: 15px;
				float: left;
				text-align: left;
				color: #111;
				line-height: 23px;
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
		<nav "w3-container">
			<ul class="navbar">
				<li><a class="navbar" href="/menu.php">Menu</a>
			</ul>
		</nav>
		<article class="w3-container">
			<section>
					<?php
						require("../info/phpsqlajax_dbinfo.php");

						// Opens a connection to a MySQL server

						$connection=mysqli_connect($hostname, $username, $password, $database);
						// Check connection
						if (mysqli_connect_errno())
						  {
						  echo "Communication Error";
						  }
						  	$query = "SELECT m.misusername,m.misusermobile,y.misresponsibilityname FROM misuser m, misuserresponsibility r,misresponsibility y where m.misuserid=r.misuserid and r.misresponsibilityid=y.misresponsibilityid and r.misfactoryid=".$_SESSION['factorycode']." and m.misuseractive=1 and r.misactive=1 and y.misactive=1 group by m.misusername,m.misusermobile,y.misresponsibilityname";						  	
							
							$result = mysqli_query($connection,$query);
							if (!$result) 
							{
							  //echo $query;
							  die('Communication Error');
							}
							// Iterate through the rows, adding XML nodes for each
							echo '<table style="border=1px solid black; border-radius:10px; padding:0px; padding-top:6px;margin: 0px; font-family:verdana;" align="left" width=300px>';
                        	echo '<tr style="font-size:12px">';
                            echo '<td><label>User Name</label></td>';
                            echo '<td><label>Mobile</label></td>';
                            echo '<td><label>Responsibility</label></td>';
                        	echo '</tr>';
							while ($row = @mysqli_fetch_assoc($result))
							{
								echo '<tr style="font-size:10px">';
                        		echo '<td><label>'.$row["misusername"].'</label></td>';
                        		echo '<td><label>+'.$row["misusermobile"].'</label></td>';
                        		echo '<td><label>'.$row["misresponsibilityname"].'</label></td>';
                    			echo '</tr>';
							}	
							echo "</table>";
					?>
			</section>	
		</article>
		<footer>
			Copyright @2015 Swapp Software Application.<br/>
			All Rights Reserved.
		</footer>
	</body>
</html>