<?php
    require("../info/phpgetlogin.php");
    require("../info/ncryptdcrypt.php");
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/swapproutine.php");
    //System Admin,Admin
    if (isaccessible(621478512368915)==0 and isaccessible(785236954125917)==0)
    {
        echo 'Communication Error';
        exit;
    }
    $userid_de = fnDecrypt($_GET['userid']);

    function getusername($usrid)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname, $username, $password, $database);
        // Check connection
        if (mysqli_connect_errno())
          {
            echo "Communication Error";
          }
        $query = "SELECT u.misusername FROM misuser u where u.misuseractive=1 and misuserid=".$usrid." order by misusername";                          
        //mysqli_query($connection,'SET NAMES UTF8');
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC))
        {
            return $row['MISUSERNAME'];
        }
        else
        {
            echo "Communication Error";
            return 0;
        }
    }

    function isadmin($usrid)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname, $username, $password, $database);
        // Check connection
        if (mysqli_connect_errno())
          {
            echo "Communication Error";
          }
        $query = "SELECT misactive FROM misuserresponsibility r where r.misactive=1 and misresponsibilityid=785236954125917 and misuserid=".$usrid." and misfactoryid=".$customerid;                          
        //mysqli_query($connection,'SET NAMES UTF8');
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"></meta>
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<link rel="stylesheet" href="../css/w3.css">
		<title>User Responsibility List</title>
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
				<li><a class="navbar" href="../mis/usermenu.php">User Menu</a>
				<li><a class="navbar" href="../mis/user_list.php">MIS User List</a>
                <?php
                    echo '<li><a style="color:#f48" class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
                ?>
			</ul>
		</nav>
		<article class="w3-container">
			<section>
					<?php
						echo '<form method="post" action="../sqlproc/regenerateuserpassword.php">';
						// Opens a connection to a MySQL server
						$connection=mysqli_connect($hostname, $username, $password, $database);
						// Check connection
						if (mysqli_connect_errno())
						  {
						  	echo "Communication Error";
						  }
							if (isadmin($userid_de) == 1 and isadmin($_SESSION['usersid']) == 1 )
					  		{
					  			$noadminrevoke=1;
					  		}
					  		else
					  		{
					  			$noadminrevoke=0;	
					  		}
					  		$query = "select m.misuserresponsibilityid,u.misuserid,u.misusername,r.misresponsibilityid,r.misresponsibilityname from misuserresponsibility m, misuser u, misresponsibility r where m.misuserid=u.misuserid and m.misresponsibilityid=r.misresponsibilityid and m.misactive=1 and u.misuseractive=1 and r.misactive=1 and u.misuserid=".$userid_de." and m.misfactoryid=".$customerid." order by misresponsibilityname";						  	

							//mysqli_query($connection,'SET NAMES UTF8');
							$result = oci_parse($connection, $query); $r = oci_execute($result);
							if (!$result)
							{
							  die('Communication Error');
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
                        			$misuserresponsibilityid_en = fnEncrypt('000000000');
                        			$userid_en = fnEncrypt($userid_de);
                    				echo '<td><img src="../img/R.png" width="16px" height="31px"><a style="color:#4a4" class="servicebar" href="../mis/userresponsibility.php?misuserresponsibilityid='.$misuserresponsibilityid_en.'&userid='.$userid_en.'&flag=new">Grant New Responsibility</a><br/>';
                					echo '</tr>';
                				}
                        	echo '<tr "font-size:15px">';
                        	echo '<td><img src="../img/U.png" width="16px" height="31px"><label for="user">'.getusername($userid_de).'</label></td>';
                        	echo '</tr>';

							while ($row = oci_fetch_array($result,OCI_ASSOC))
							{
								echo '<tr style="font-size:13px">';
                        		echo '<td><img src="../img/R.png" width="16px" height="31px"><a style="color:#000" class="servicebar">'.$row['MISRESPONSIBILITYNAME'].'</A>';
		                        if ($_SESSION["responsibilitycode"] == 621478512368915 or $_SESSION["responsibilitycode"] == 785236954125917)
                    			{
		                        	$misuserresponsibilityid_en = fnEncrypt($row['MISUSERRESPONSIBILITYID']);
		                        	echo '<td><a style="color:#333" class="servicebar" href="../mis/userresponsibility.php?misuserresponsibilityid='.$misuserresponsibilityid_en.'&userid='.$userid_en.'&flag=view'.'"><img border="0" alt="पहा (View)" src="../img/viewdata.png" width="18" height="10"></br></a>';
		                        	if ($noadminrevoke==0)
		                        	{
		                        		echo '<td><a style="color:#333" class="servicebar" href="../mis/userresponsibility.php?misuserresponsibilityid='.$misuserresponsibilityid_en.'&userid='.$userid_en.'&flag=delete'.'"><img border="0" alt="खोडणे (Delete)" src="../img/deletedata.png" width="18" height="10"></br></a>';
									}		                        		
                    			}
                    			echo '</tr>';
							}
							echo '<tr>';
                            echo '<td><input type="hidden" name="userid" value="'.$userid_de.'"/>';
                        	echo '</tr>';
                        	if ($_SESSION['usersid']!= $userid_de)
                        	{
                        		echo '<tr>';
                            	echo '<td><input type="submit" style="width:150px;font-size:13pt;" value="Reset Password"/>';
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