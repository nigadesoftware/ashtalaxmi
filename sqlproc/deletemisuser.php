<?php
	require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
	// Grab User submitted information
	$mobile = $_POST["users_mobile"];
	$remove_user = $_POST["remove_user"];
	// Opens a connection to a MySQL server
	$connection=mysqli_connect($hostname, $username, $password, $database);
	// Check connection
	if (mysqli_connect_errno())
	{
	  echo "Communication Error";
	  exit;
	}
	if (isset($mobile)==false or isset($remove_user)==false)
	{
		echo "Invalid Data";
		exit;
	}
	if (strlen($mobile)!=13)
	{
		echo "Invalid Data";
		exit;
	}
	if ($remove_user != 'Y' and $remove_user != 'y')
	{
		echo "Invalid Operation";
		exit;
	}	
	$passgen = (int)($mobile / 23548);
	$passgen = (int)($passgen % 100000);
	$result=mysqli_query($connection, "select m.misuserid,m.misusername from misuser m where m.misuseractive=1 and m.misusermobile=".$mobile);
	$row = mysqli_fetch_assoc($result);
	if (isset($row['misuserid']))
	{
		$connection ->autocommit(FALSE);
		$sql = "update misuser set misuseractive=0,dluserid=".$_SESSION["usersid"]." where misuserid=".$row["misuserid"];
		if (mysqli_query($connection, $sql)) 
			{
				$sql = "update misuserpassword set misactive=0,dluserid=".$_SESSION["usersid"]." where misuserid=".$row["misuserid"];
				if (mysqli_query($connection, $sql)) 
				{
					$sql = "update misuserresponsibility set misactive=0,dluserid=".$_SESSION["usersid"]." where misuserid=".$row["misuserid"];
					if (mysqli_query($connection, $sql)) 
					{
						$connection ->commit();	
						echo $row['misusername'].' User is Removed <br/>';
					}
					else
					{
		    			echo "Communication Error";
		    			$connection ->rollback();
					}
				} 
				else
				{
		    		echo "Communication Error";
		    		$connection ->rollback();
				}
			}
		else 
			{
		    	echo "Communication Error";
		    	$connection ->rollback();
			}
	}
	else
	{
		echo "Communication Error";
	}
?>