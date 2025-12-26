<?php
	require('../info/phpsqlajax_dbinfo.php');
	require('../info/swapproutine.php');
	session_start();
	// Opens a connection to a MySQL server
	$connection=mysqli_connect($hostname, $username, $password, $database);
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Communication Error";
	  	exit;
	}
	$connection ->autocommit(FALSE);
	if (isset($_SESSION['usersname']))
    {
		$query = "update misuserlogininformation set sessionenddatetime='".currentdatetime()."',loggedoutflag=0 where sessionenddatetime is null and miscustomerid=".$_SESSION['factorycode']." and misuserid=".$_SESSION['usersid']." and sessionid='".$_SESSION['cursession']."'";
		//echo $query;
		if (mysqli_query($connection, $query)) 
		{
    		$connection -> commit();
			unset($_SESSION['cursession']);
			unset($_SESSION['usersid']);
			unset($_SESSION['usersname']);
			unset($_SESSION['entityid']);
			unset($_SESSION['finalreportperiodid']);
			session_unset();
			session_destroy();
			header("location:../mis/login.php?flag=0");
			exit();
		}
	}
	else
	{
		echo "Communication Error";
		exit;
	}
?>