<?php
    require("../info/phpgetlogin.php");
    require("../info/ncryptdcrypt.php");
    require("../info/swapproutine.php");
    //System Admin,Admin
    if (isaccessible(621478512368915)==0 and isaccessible(785236954125917)==0)
    {
        echo 'Communication Error1';
        exit;
    }
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"></meta>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="../css/w3.css">
		<title>MIS User List</title>
		<style type="text/css">
			body
			{
				background-color: #fff;
			}
			header
			{
				background-color: #fff;
				min-height: 70px;
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
				color: #f48;
			}
			article
			{
				background-color: #fff;
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
			<?php
                echo '<li><a style="color:#f48" class="navbar" href="../sqlproc/logout.php">Log Out</a><br/>';
            ?>
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
							echo "Communication Error2";
						}
						if ($_SESSION["responsibilitycode"] == 621478512368915)
						{
							$query = "SELECT m.misuserid,m.misusername FROM misuser m where m.misuseractive=1 and (miscustomerid=0 or miscustomerid=".$_SESSION['factorycode'].") order by m.misusername";						  	
						}
						elseif ($_SESSION["responsibilitycode"] == 785236954125917)
						{
							$query = "SELECT m.misuserid,m.misusername FROM misuser m where m.misuseractive=1 and misuserid<>".$_SESSION['usersid']." and miscustomerid<>0 and miscustomerid=".$_SESSION['factorycode']." order by m.misusername";						  	
						}
						else
						{
							$query = "SELECT m.misuserid,m.misusername FROM misuser m where m.misuseractive=1 and miscustomerid=".$_SESSION['factorycode']." order by m.misusername";						  									
						}
						//echo $query;
						$result = mysqli_query($connection,$query);
						if (!$result)
						{
							die('Communication Error3');
						}
						// Iterate through the rows, adding XML nodes for each
						echo '<table style="border=1px solid black; border-radius:10px; padding:0px; padding-top:6px; margin: 0px; font-family:verdana;" align="left" width=500px>';
						if ($_SESSION["responsibilitycode"] == 621478512368915 or $_SESSION["responsibilitycode"] == 785236954125917)
							{
								echo '<col style="width:40%">';
								echo '<col style="width:20%">';
								echo '<col style="width:20%">';
								echo '<col style="width:20%">';
							}
						if ($_SESSION["responsibilitycode"] == 621478512368915 or $_SESSION["responsibilitycode"] == 785236954125917)
							{
								echo '<tr style="font-size:13px">';
								$customerid_en=fnEncrypt($customerid);
								$userid_en = fnEncrypt('000000000');
								echo '<td><img src="../img/U.png" width="16px" height="31px"><a style="color:#4a4" class="servicebar" href="../data/user.php?userid='.$userid_en.'&flag=new">New User</a><br/>';
								echo '</tr>';
							}
						while ($row = @mysqli_fetch_assoc($result))
						{
							echo '<tr style="font-size:13px">';
							$userid_en = fnEncrypt($row['misuserid']);
							echo '<td><img src="../img/U.png" width="16px" height="31px"><a style="color:#000" class="servicebar" href="../data/userresponsibility_list.php?userid='.$userid_en.'">'.$row['misusername'].'</a>';
							if ($_SESSION["responsibilitycode"] == 621478512368915 or $_SESSION["responsibilitycode"] == 785236954125917)
							{
								echo '<td><a style="color:#333" class="servicebar" href="../data/user.php?userid='.$userid_en.'&flag=view'.'"><img border="0" alt="पहा (View)" src="../img/viewdata.png" width="18" height="10"></br></a>';
								echo '<td><a style="color:#333" class="servicebar" href="../data/user.php?userid='.$userid_en.'&flag=change'.'"><img border="0" alt="बदल (Change)" src="../img/changedata.png" width="18" height="10"></br></a>';
							}
							echo '</tr>';
						}
						echo "</table>";
					?>
			</section>	
		</article>
		<footer>
		</footer>
	</body>
</html>