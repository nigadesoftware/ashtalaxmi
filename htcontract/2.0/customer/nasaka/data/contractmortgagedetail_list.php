<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
    include("../info/ncryptdcrypt.php");
    require("../info/rawmaterialroutine.php");
    //Raw Material Master Addition
    if (isaccessible(451230287895415)==0 and isaccessible(475124562358965)==0)
    {
        echo 'Communication Error';
        exit;
    }
    $contractid_de = fnDecrypt($_GET['contractid']);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"></meta>
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<link rel="stylesheet" href="../css/swapp123.css">
		<title>Contract Property Detail List</title>
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
		<script src="http://ajax.googleapi_mysqls.com/ajax/libs/jquery/1.11.0/jquery.min.js">
 		 </script>
 		 <script>
 			$(property).ready(function(){
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
			<?php
				$finalreportperiodid_en = fnEncrypt($_SESSION["finalreportperiodid"]);
				echo '<li><a class="navbar" href="../data/entitymenu.php?finalreportperiodid='.$finalreportperiodid_en.'">Entity Menu</a>';
				echo '<li><a class="navbar" href="../data/contract.php?contractid='.fnEncrypt($contractid_de).'&flag='.fnEncrypt('Display').'">Add/Display Contract</a></br>';
			?>
			</ul>
		</nav>
		<article class="w3-container">
			<section>
					<?php
						// Opens a connection to a MySQL server
                		$connection=rawmaterial_connection();
						  	//$query = "SELECT f.*,n.name_eng,n.name_unicode FROM contractmortgagedetail f,namedetail n where f.active=1 and n.active=1 and f.namedetailid=n.namedetailid and f.contractid=".$contractid_de;						  	
							$query = "select f.*,d.name_eng as propertyname_eng,d.name_unicode as propertyname_unicode from contractmortgagedetail f, namedetail d where f.active=1 and d.active=1 and f.propertycategoryid=d.namedetailid and f.contractid = ".$contractid_de;
							//echo $query;
							//mysqli_query($connection,'SET NAMES UTF8');
							$result = oci_parse($connection, $query); $r = oci_execute($result);
							if (!$result)
							{
							  die('Communication Error1');
							}
							// Iterate through the rows, adding XML nodes for each
							echo '<table style="border=1px solid black; border-radius:10px; padding:0px; padding-top:6px;margin: 0px; font-family:verdana;" align="left" width=500px>';
                        	if ($_SESSION["responsibilitycode"] == 475124562358965)
                    			{
		                        	echo '<col style="width:40%">';
		                        	echo '<col style="width:20%">';
		                        	echo '<col style="width:20%">';
		                        	echo '<col style="width:20%">';
		                        }
		                    else if ($_SESSION["responsibilitycode"] == 451230287895415)
		                        {
		                        	echo '<col style="width:40%">';
		                        	echo '<col style="width:60%">';
		                        }
                        	echo '<tr style="font-size:14px">';
                            echo '<td><label></br>करार गहाण मिळकत माहिती यादी</br>Contract Mortgage Property Detail List</label></td>';
                        	echo '</tr>';
                        	$contractid_en = fnEncrypt($contractid_de);
							$servicecontractorcategoryid_en = fnEncrypt('845689712');
                        	$contractmortgagedetailid_en = fnEncrypt('000000000');
                        	//if ($_SESSION["responsibilitycode"] == 325434741256025)
                			//{
                    			echo '<tr style="font-size:13px">';
                				echo '<td><a style="color:#4a4" class="servicebar" href="../data/contractmortgagedetail.php?contractid='.$contractid_en.'&servicecontractorcategoryid='.$servicecontractorcategoryid_en.'&contractmortgagedetailid='.$contractmortgagedetailid_en.'&flag=new">नवीन मिळकत</br>New property Detail</a>';
            					echo '</tr>';
            				//}
							while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
							{
								echo '<tr style="font-size:13px">';
								$contractmortgagedetailid_en = fnEncrypt($row['CONTRACTMORTGAGEDETAILID']);
								if ($_SESSION['lng'] == 'English')
								{
									echo '<td><a class="servicebar">'.$row['PROPERTYNAME_ENG'].'</A>';
								}
								else
								{
									echo '<td><a class="servicebar">'.$row['PROPERTYNAME_UNICODE'].'</A>';
								}
                    			if ($_SESSION["responsibilitycode"] == 475124562358965)
	                			{
		                        	echo '<td><a style="color:#333" class="servicebar" href="../data/contractmortgagedetail.php?contractid='.$contractid_en.'&contractmortgagedetailid='.$contractmortgagedetailid_en.'&flag=view'.'"><img border="0" alt="पहा (View)" src="../img/viewdata.png" width="18" height="10"></br></a>';
		                        	echo '<td><a style="color:#333" class="servicebar" href="../data/contractmortgagedetail.php?contractid='.$contractid_en.'&contractmortgagedetailid='.$contractmortgagedetailid_en.'&flag=change'.'"><img border="0" alt="बदल (Change)" src="../img/changedata.png" width="18" height="10"></br></a>';
		                        	echo '<td><a style="color:#333" class="servicebar" href="../data/contractmortgagedetail.php?contractid='.$contractid_en.'&contractmortgagedetailid='.$contractmortgagedetailid_en.'&flag=delete'.'"><img border="0" alt="खोडणे (Delete)" src="../img/deletedata.png" width="18" height="10"></br></a>';
	                			}
	      			   		    else if ($_SESSION["responsibilitycode"] == 451230287895415)
		                        {
		                        	echo '<td><a style="color:#333" class="servicebar" href="../data/contractmortgagedetail.php?contractid='.$contractid_en.'&contractmortgagedetailid='.$contractmortgagedetailid_en.'&flag=view'.'"><img border="0" alt="पहा (View)" src="../img/viewdata.png" width="18" height="10"></br></a>';
		                        }
		                        echo '</tr>';
							}
							echo "</table>";
							echo '</form>';
                        	echo '</section>';
					?>
			</section>
		</article>
		<footer>
		</footer>
	</body>
</html>