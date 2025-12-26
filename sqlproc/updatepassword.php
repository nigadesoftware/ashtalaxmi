<?php
	require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
	require("../info/crypto.php");
	// Grab User submitted information
	$existing_password = $_POST["existing_password"];
	$new_password = $_POST["new_password"];
	$retype_new_password = $_POST["retype_new_password"];
	// Opens a connection to a MySQL server
	$connection=mysqli_connect($hostname, $username, $password, $database);
	// Check connection
	if (mysqli_connect_errno())
	{
	  echo "Communication Error";
	  exit;
	}
	if (isset($existing_password)==false or isset($new_password)==false or isset($retype_new_password)==false)
	{
		echo "Invalid Data";
		exit;
	}
	if ($new_password !=$retype_new_password)
	{
		echo "New Password and/or Retype Password does not match";
		exit;
	}
	if (strlen($new_password)<4)
	{
		echo "Minimum 4 letters needed";
		exit;
	}
	$result=mysqli_query($connection, "select p.mispassword from misuser m,misuserpassword p where m.misuserid=p.misuserid and m.misuseractive=1 and p.misactive=1 and m.misuserid=".$_SESSION["usersid"]);
	$row = mysqli_fetch_assoc($result);
	$dcpass = new crypto;
	$dcrpass = $dcpass->Decrypt($row["mispassword"],1);
                
	$mispassword=$dcrpass;
	if ($mispassword != $existing_password)
	{
		echo 'Invalid Password';
		exit;
	}
	$dcrpass = $dcpass->Encrypt($new_password);
	$connection ->autocommit(FALSE);
	$sql = "update misuserpassword set misactive=0,dluserid=".$_SESSION["usersid"]." where misuserid=".$_SESSION["usersid"];
	if (mysqli_query($connection, $sql)) 
	{
    	$sqlpass = "insert into misuserpassword(misuserid,mispassword,misactive,cruserid) values (".$_SESSION["usersid"].",'$dcrpass',1,".$_SESSION["usersid"].")";
    	if (mysqli_query($connection, $sqlpass)) 
		{
	    	$connection ->commit();	
			echo '<tr>';
			echo '<td><a style="background-color:#0a0;color:#ff0;font-size:16px">Password is Changed Successfully</br></a></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td><a style="color:#0a0;font-size:16px" href="../mis/login.php">Login</a></td>';
			echo '</tr>';
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