<?php
    require("../info/phpgetlogin.php");
    require("../info/ncryptdcrypt.php");
    require("../info/phpsqlajax_dbinfo.php");
    require_once("../../../../../sqlproc/defaultusersettings.php");

    $entityglobalgroupid_de = fnDecrypt($_GET['entityglobalgroupid']);
    $globalgroupid_de = fnDecrypt($_GET['globalgroupid']);
	$entityid_de = fnDecrypt($_GET['entityid']);
	$_SESSION["entityid"] = $entityid_de;
	$_SESSION["globalgroupid"] = $globalgroupid_de;
	$_SESSION["entityglobalgroupid"] = $entityid_de;
	$_SESSION["entityname"] = getentityname($entityid_de,0);

	function getentityname($entityid,$lng)
	{
		require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
          {
            echo "Communication Error";
          }
        $query = "select entityname,entityname_eng from vw_entity where active=1 and entityid=".$entityid;
        //mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection, $query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            if ($lng == 0)
            {
            	return $row['entityname_eng'];
            }
            else
            {
            	return $row['entityname'];
            }
        }
        else
        {
            echo "Communication Error77";
            return '';
        }
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"></meta>
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<link rel="stylesheet" href="../css/w3.css">
		<title>Entity</title>
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
			 setInterval(function(){cache_clear()},360000);
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
				<li><a class="navbar" href="../mis/usermenu.php">Menu</a>
			</ul>
		</nav>
		<article class="w3-container">
			<section>
					<?php
						// Opens a connection to a MySQL server
						$connection=mysqli_connect($hostname, $username, $password, $database);
						// Check connection
						if (mysqli_connect_errno())
						{
							echo "Communication Error";
						}
						if ($_SESSION['changedefaultusersettings'] == 'on')
						{
							$query1 = "update userdefault set entityid=".$_SESSION["entityid"].", globalgroupid=".$_SESSION["globalgroupid"].", entityglobalgroupid=".$_SESSION["entityglobalgroupid"]."  where misuserid=".$_SESSION["usersid"];
							if (mysqli_query($connection, $query1))
							{
								$connection -> commit();
							}
							else
							{
								echo "Communication Error3";
								exit;
							}
						}
						else
						{
							$connection -> commit();
						}
						// Opens a connection to a MySQL server
						$connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
						// Check connection
						if (mysqli_connect_errno())
						{
							echo "Communication Error";
						}
						$query = "SELECT e.subentityid,e.subentityname,e.subentityname_eng FROM subentity e where active=1 and entityid=".$entityid_de." order by e.subentityname asc";						  	
						//mysqli_query($connection,'SET NAMES UTF8');
						$result = mysqli_query($connection, $query);
						if (!$result)
						{
							die('Communication Error');
						}
						// Iterate through the rows, adding XML nodes for each
						echo '<table style="border=1px solid black; border-radius:10px; padding:0px; padding-top:6px;margin: 0px; font-family:verdana;" align="left" width=500px>';
						echo '<tr style="font-size:14px">';
						echo '<td><label>बिझनेस सब एनटीटी</br>Business Sub Entity</label></td>';
						echo '</tr>';
						$isrecordpreseent = 0;
						$defaultsubentityid = getdefaultsubentityid($connection);
						while ($row = @mysqli_fetch_assoc($result))
						{
							echo '<tr style="font-size:13px">';
							$subentityid_en = fnEncrypt($row['subentityid']);
							echo '<td><a class="servicebar" href="../mis/selectfinancialyear.php?subentityid='.$subentityid_en.'">'.$row['subentityname'].'</BR>('.$row['subentityname_eng'].')'.'</A>';
							echo '</tr>';
							$isrecordpreseent = 1;
						}
						if ($isrecordpreseent == 0 )
						{
							$subentityid_en = fnEncrypt('000000000');
							echo '<tr style="font-size:13px">';
							echo '<td><a class="servicebar" href="../mis/selectfinancialyear.php?subentityid='.$subentityid_en.'">Select Season Year</a>';
							echo '</tr>';
							//header("location: ../mis/selectfinancialyear.php?subentityid=".$subentityid_en);
						}
						echo "</table>";
					?>
			</section>
		</article>
		<footer>
		</footer>
	</body>
</html>