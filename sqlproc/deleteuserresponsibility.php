<?php
	require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
	// Grab User submitted information
	$users_mobile = $_POST["users_mobile"];
	$users_responsibility = $_POST["users_responsibility"];
	// Opens a connection to a MySQL server
	$connection=mysqli_connect($hostname, $username, $password, $database);
	// Check connection
	if (mysqli_connect_errno())
	{
	  echo "Communication Error";
	  exit;
	}
	if (isset($users_mobile)==false or isset($users_responsibility)==false)
	{
		echo "Invalid Data";
		exit;
	}
	$result=mysqli_query($connection, "select m.misuserid from misuser m where m.misuseractive=1 
		and misusermobile=$users_mobile");
	$row = mysqli_fetch_assoc($result);
	$misuserid=$row["misuserid"];
	if (isset($misuserid) == false)
	{
		echo 'Invalid Data';
		exit;
	}
	$result=mysqli_query($connection, "select p.misresponsibilityid from misresponsibility p 
		where p.misactive=1 and mod(misresponsibilityid,100)=$users_responsibility");
	$row = mysqli_fetch_assoc($result);
	$misresponsibilityid=$row["misresponsibilityid"];
	if (isset($misresponsibilityid) == false) 
	{
		echo 'Invalid Data';
		exit;
	}
	if (($_SESSION["responsibilitycode"] != 621478512368915) and ($misresponsibilityid=621478512368915 
		or $misresponsibilityid=785236954125917))
	{
		echo 'Invalid Operation';
		exit;
	}
	$result=mysqli_query($connection, "select count(*) as cnt from misuserresponsibility p 
		where p.misactive=1 and mod(misresponsibilityid,100)=$users_responsibility and misuserid=$misuserid and misfactoryid=".$_SESSION["factorycode"]);
	$row = mysqli_fetch_assoc($result);
	if ($row['cnt'] = 0) 
	{
		echo 'User doesnt has responsibility';
		exit;
	}
	$connection -> autocommit(FALSE);
	$sql = "update swappcoi_db.misuserresponsibility set misactive=0,dldatetime=now(),cruserid=".$_SESSION["usersid"]." where misactive=1 and mod(misresponsibilityid,100)=$users_responsibility and misuserid=$misuserid and misfactoryid=".$_SESSION["factorycode"];
	if (mysqli_query($connection, $sql)) 
	{
	    	$connection -> commit();	
			echo 'User Responsibility is revoked <br/>';
	} 
	else 
	{
    	echo "Error: ".mysqli_error($connection);
    	$connection -> rollback();
	}
?>