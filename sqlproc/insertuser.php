<?php
	require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
	// Grab User submitted information
	$usersname = $_POST["users_name"];
	$mobile = $_POST["users_mobile"];
	// Opens a connection to a MySQL server
	$connection=mysqli_connect($hostname, $username, $password, $database);
	// Check connection
	if (mysqli_connect_errno())
	{
	  echo "Communication Error";
	  exit;
	}
	if (isset($usersname)==false or isset($mobile)==false)
	{
		echo "Invalid Data";
		exit;
	}
	if (strlen($mobile)!=13)
	{
		echo "Invalid Data";
		exit;
	}
	$passgen = (int)($mobile / 23548);
	$passgen = (int)($passgen % 100000);
	$connection ->autocommit(FALSE);
	$result=mysqli_query($connection, "select ifnull(max(misuserid),0)+1325486 as misuserid from misuser");
	$row = mysqli_fetch_assoc($result);
	$userid=$row["misuserid"];
	$sql = "insert into misuser(misuserid,misusername,misusermobile,misuseractive,cruserid) values ($userid,'$usersname',$mobile,1,".$_SESSION["usersid"].")";
	if (mysqli_query($connection, $sql)) 
	{
    	echo $usersname.' User is Created.<br/>';
    	$sqlpass = "insert into misuserpassword(misuserid,mispassword,misactive,cruserid) values ($userid,$passgen,1,".$_SESSION["usersid"].")";
    	if (mysqli_query($connection, $sqlpass)) 
		{
	    	$connection ->commit();	
			echo 'First Time Password is Generated :'.$passgen.'<br/>';
		}
		else
		{
			echo "Error: ".mysqli_error($connection);
    		$connection ->rollback();
		}
	} 
	else 
	{
    	echo "Error: ".mysqli_error($connection);
    	$connection ->rollback();
	}
?>