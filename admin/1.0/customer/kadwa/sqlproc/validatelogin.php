<?php
	require_once('../info/phpsqlajax_dbinfo.php');
    require_once("../info/ncryptdcrypt.php");
	require_once("../info/swapproutine.php");
	$aadharnumber = $_POST["aadharnumber"];
	$pass = $_POST["users_pass"];
	
	/*function isotpalreadyissued($connection,$misuserid)
	{
		$query = "select count(*) as cnt from misuserlogininformation where misuserid=".$misuserid." and date(sessionstartdatetime)=date(now())";
		$result = mysqli_query($connection,$query);
		if ($row = @mysqli_fetch_assoc($result))
		{

		}
	}*/
	
	if (isset($aadharnumber) == false or isset($pass) == false or $aadharnumber=='' or $pass=='')
	{
		//echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Incomplete Login Information</span>';
	  	header("location: ../mis/login.php?flag=4");
	  	exit;	
	}
	// Opens a connection to a MySQL server
	$connection=mysqli_connect($hostname, $username, $password, $database);
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Communication Error";
	  	exit;
	}
	$connection ->autocommit(FALSE);
    $query = "SELECT m.misuserid,m.misusername,p.mispassword,m.miscustomerid,c.miscustomername,p.isotppassword FROM misuser m,misuserpassword p,miscustomer c WHERE m.misuserid=p.misuserid and ((m.miscustomerid=c.miscustomerid and m.miscustomerid=$customerid) or (m.miscustomerid=0 and c.miscustomerid=$customerid)) and m.misuseractive=1 and p.misactive=1 and c.misactive=1 and m.aadharnumber = $aadharnumber";
    $result = mysqli_query($connection,$query);
	if ($row = @mysqli_fetch_assoc($result))
	{
		if(fnDecryptpass($row["mispassword"])==$pass)
		{
			session_start();
			$sessid=session_id();

			$query = "SELECT sessionid FROM misuserlogininformation m WHERE sessionenddatetime is null and sessionid='".$sessid."'";
		    //echo $query;
		    $result1 = mysqli_query($connection,$query);
			if ($row1 = @mysqli_fetch_assoc($result1))
			{
				if (isset($row1["sessionid"]))
				{
					header("location: ../sqlproc/logout.php");
					exit;
				}
			}
			else
			{
				session_regenerate_id(FALSE);
				session_unset();
				$query = "insert into misuserlogininformation(miscustomerid,misuserid,sessionid,ip_address,sessionstartdatetime) values (".$customerid.",".$row['misuserid'].",'".$sessid."'".",'".$_SERVER['REMOTE_ADDR']."','".currentdatetime()."')";
				if (mysqli_query($connection, $query)) 
				{
		    		$connection -> commit();
					/*session is started if you don't write this line can't use $_Session  global variable*/
					$_SESSION["cursession"]=$sessid;
					$_SESSION["usersid"]=$row["misuserid"];
					$_SESSION["usersname"]=$row["misusername"];
					$_SESSION["factorycode"]=$customerid;				
					$_SESSION["factoryname"]=$row["miscustomername"];
					$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
					$_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
					if ($row['isotppassword']==1)
					{
						header("location: ../mis/changepassword.php");
					}
					else
					{
						header("location: ../mis/selectresponsibility.php");
					}
				}
				else
				{
					//echo "Communication Error2";
				}
			}
		}
		else
		{
			header("location: ../mis/login.php?flag=5");
		  	exit;
		}
	}
	else
	{
		header("location: ../mis/login.php?flag=5");
		exit;
	}
?>