<?php
	require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
    require("../info/ncryptdcrypt.php");
    require("../info/swapproutine.php");
	// Grab User submitted information
	$existing_password = $_POST["existing_password"];
	$new_password = $_POST["new_password"];
	$retype_new_password = $_POST["retype_new_password"];
	// Opens a connection to a MySQL server
	$connection = mysqli_connect($hostname, $username, $password, $database);
	// Check connection
	if (mysqli_connect_errno())
	{
	  echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication Error1</span>';
	  exit;
	}
	if (isset($existing_password) == false or isset($new_password) == false or isset($retype_new_password) == false)
	{
		echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Invalid Data</span>';
		exit;
	}
	if ($new_password != $retype_new_password)
	{
		echo '<span style="background-color:#f44;color:#ff8;text-align:left;">New Password and/or Retype Password doesnt match</span>';
		exit;
	}
	if (strlen($new_password)<8)
	{
		echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Minimum 8 letters password needed</span>';
		exit;
	}
	if (strlen($new_password)>20)
	{
		echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Minimum 8 letters password needed</span>';
		exit;
	}
	if (preg_match("/[A-Za-z]/", $new_password) == 0)
	{
		echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Password should have atleast one Uppercase Letter/Lowercase Letter/Digit</span>';
		exit;
	}
	if (preg_match("/[0-9]/", $new_password) == 0)
	{
		echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Password should have atleast one Uppercase Letter/Lowercase Letter/Digit</span>';
		exit;
	}
	$result = mysqli_query($connection, "select p.mispassword from misuser m,misuserpassword p where m.misuserid=p.misuserid and m.misuseractive=1 and p.misactive=1 and m.misuserid=".$_SESSION["usersid"]);
	$row = mysqli_fetch_assoc($result);
	$mispassword = fnDecryptpass($row["mispassword"]);
	$existing_password = ''.$existing_password;
	if ($mispassword != $existing_password)
	{
		echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Invalid Password</span>';
		exit;
	}
	$connection -> autocommit(FALSE);
	$query = "update misuserpassword set misactive=0,dluserid=".$_SESSION["usersid"].",dldatetime='".currentdatetime()."' where misuserid=".$_SESSION["usersid"];
	if (mysqli_query($connection, $query)) 
	{
    	$new_password = ''.$new_password;
    	$new_password = fnEncryptpass($new_password);
    	$querypass = "insert into misuserpassword(misuserid,mispassword,misactive,cruserid,crdatetime) values (".$_SESSION["usersid"].",'$new_password',1,".$_SESSION["usersid"].",'".currentdatetime()."')";
    	if (mysqli_query($connection, $querypass)) 
		{
	    	$connection -> commit();
	    	//include("../info/responsemenu.php");
	    	//session_start();
	    	echo '<span style="background-color:#0a0;color:#ff8;text-align:left;">Password is changed successfully</span>';
	    	echo '<a class="navbar" href="../mis/selectresponsibility.php">Select Responsibility</a>';
			exit;
		}
		else
		{
			echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication Error2</span>';
    		$connection ->rollback();
		}
	}
	else
	{
    	echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication Error3</span>';
    	$connection ->rollback();
	}
?>