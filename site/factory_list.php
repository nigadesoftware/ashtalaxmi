<?php
    require("../info/ncryptdcrypt.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"></meta>
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<link rel="stylesheet" href="../css/w3.css">
		<title>Factory List</title>
		<style type="text/css">
			body
			{
				background-color: #fff;
			}
			header
			{
				background-color: #333;
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
				color: #070;
				line-height: 30px;
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
			a.navbar
			{
				color: #070;
			}
			a.servicebar
			{
				color: #070;
			}
			footer
			{
				float: bottom;
				color: #666;
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
		<script async src="../js/1.11.0/jquery.min.js">
 		 </script>
 		 <script async>
 			$(document).ready(function(){
			 setInterval(function(){cache_clear()},360000);
			 });
			 function cache_clear()
			{
			 window.location.reload(true);
			}
		</script>
	<script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58db35533dec30001259e677&product=sticky-share-buttons"></script>
</head>
	<body>
		<header class="w3-container">
			<div><img src="../img/swapp_namelogo.png" width="150px" height="50px">
				</div>
		</header>
		<nav "w3-container">
			<ul class="navbar">
                <ul class="navbar">
					<li><a class="navbar" href="/index.php">Home</a>
				</ul>
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
						$query = "SELECT m.id,m.name,m.namelocal,m.type,s.subdistrictid,s.subdistrictname,s.subdistrictname_eng,d.districtid,d.districtname,d.districtname_eng,t.stateid,t.statename,t.statename_eng 
        				FROM subdistrict s,district d, state t,markers m
        				where s.districtid=d.districtid and d.stateid=t.stateid and s.subdistrictid=m.subdistrictid order by d.districtid,statename_eng,districtname_eng,subdistrictname_eng,m.name";  
					  	//$query = "SELECT * FROM markers where type='Co-Operative Sugar Factory' order by name";						  	
						//echo $query;
						mysqli_query($connection,'SET NAMES UTF8');
						$result = mysqli_query($connection,$query);
						if (!$result)
						{
						  die('Communication Error');
						}
						// Iterate through the rows, adding XML nodes for each
						echo '<table style="border=1px solid black; border-radius:10px; padding:0px; padding-top:6px;margin: 0px; font-family:verdana;" align="left" width=300px>';
						$laststateid=0;
						$lastdistrictid=0;
						$lastsubdistrictid=0;

						echo '<tr>';
	                		echo '<td><a for="Statelist" style="color:#000">State wise District wise Taluka wise Sugar Factory List </a></td>';
                       	echo '</tr>';

						while ($row = @mysqli_fetch_assoc($result))
						{
							echo '<tr>';
                        	if ($laststateid == $row['stateid'])
                        	{
	                        	
                        	}
                        	else
                        	{
                        		$stateid_en = fnEncrypt('00000'.$row['stateid']);
                        		echo '<td><a for="Statelist" style="background-color:#fc0;color:#000" href="../site/indianstatedistrictlist.php?stateid='.$stateid_en.'">'.$row['statename_eng'].' state )'.' </a></td>';
                        		$laststateid = $row['stateid'];
                        	}
                        	echo '</tr>';
							echo '<tr>';
                        	if ($lastdistrictid == $row['districtid'])
                        	{
	                        	
                        	}
                        	else
                        	{
                        		$districtid_en = fnEncrypt('00000'.$row['districtid']);
                        		echo '<td><a for="Districtlist" style="background-color:#fed;color:#a00" href="../site/indianstatedistrictsubdistrictlist.php?stateid='.$stateid_en.'&districtid='.$districtid_en.'">'.$row['districtname_eng'].' district '.' </a></td>';
                        		$lastdistrictid = $row['districtid'];
                        	}
                        	echo '</tr>';
							echo '<tr>';
                        	if ($lastsubdistrictid == $row['subdistrictid'])
                        	{
	                        	
                        	}
                        	else
                        	{
                        		$subdistrictid_en = fnEncrypt('00000'.$row['subdistrictid']);
                        		echo '<td><a for="talukalist" style="background-color:#efe;color:#080" href="../site/indianstatedistrictsubdistrictarealist.php?stateid='.$stateid_en.'&districtid='.$districtid_en.'&subdistrictid='.$subdistrictid_en.'">'.$row['subdistrictname'].' तालुका ( '.$row['subdistrictname_eng'].' taluka )'.' </a></td>';
                        		$lastsubdistrictid = $row['subdistrictid'];
                        	}
                        	echo '</tr>';
							echo '<tr style="font-size:13px">';
							$factoryid_en = fnEncrypt('00000'.$row['id']);
							if ($row['type'] == 'Co-Operative Sugar Factory')
							{
								echo '<td><a style="color:#020" class="servicebar" href="../site/factorymap.php?factoryid='.$factoryid_en.'"">'.$row['name'].' Co-Operative Sugar Factory (ssk)'.'</a>';
							}
							else
							{
								echo '<td><a style="color:#020" class="servicebar" href="../site/factorymap.php?factoryid='.$factoryid_en.'"">'.$row['name'].' Sugar Factory'.'</a>';								
							}
                    		
                			echo '</tr>';
						}

						/*$query = "SELECT * FROM markers where type='Private Sugar Factory' order by name";						  	
						mysqli_query($connection,'SET NAMES UTF8');
						$result = mysqli_query($connection,$query);
						if (!$result)
						{
						  die('Communication Error');
						}
						// Iterate through the rows, adding XML nodes for each
						echo '<table style="border=1px solid black; border-radius:10px; padding:0px; padding-top:6px;margin: 0px; font-family:verdana;" align="left" width=500px>';
						echo '<tr>';
                        echo '<td><label for="coopfactorylist">खाजगी साखर कारखाना यादी (Private Sugar Factory List)</label></td>';
                        echo '</tr>';
						while ($row = @mysqli_fetch_assoc($result))
						{
							echo '<tr style="font-size:13px">';
							$factoryid_en = fnEncrypt('00000'.$row['id']);
                    		echo '<td><a style="color:#e04" class="servicebar" href="../site/factorymap.php?factoryid='.$factoryid_en.'"">'.$row['namelocal'].' '.'साखर कारखाना'.'</br>'.$row['name'].' sakhar karkhana'.'</a>';
                			echo '</tr>';
						}*/
						echo '<tr style="font-size:13px">';
						echo '<td for="Factorylist" style="color:#008">( If Sugar Factory is not listed in the above list please mail us : admin@swapp.co.in ) </td>';
						echo '</tr>';
						echo "</table>";
					?>
			</section>
		</article>
		<footer>
		</footer>
	</body>
</html>