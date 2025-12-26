<?php
	require("../info/phpsqlajax_dbinfo.php");
   	include("../info/phpgetloginview.php");
	require("../info/swapproutine.php");
    //System Admin,Admin
    if (isaccessible(621478512368915)==0 and isaccessible(785236954125917)==0)
    {
        echo 'Communication Error';
        exit;
    }
	$userid = $_POST['userid'];
	$usersname = $_POST["misusername"];
	$usersaddress = $_POST["misuseraddress"];
	$aadharnumber = $_POST["aadharnumber"];
	$mobile = $_POST["misusermobile"];
	$email = $_POST["misemailaddress"];
	$suspended = $_POST["suspended"];
	// Opens a connection to a MySQL server
	$connection=mysqli_connect($hostname, $username, $password, $database);
	// Check connection
	if (mysqli_connect_errno())
	{
	  echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Communication Error</span>';
	  exit;
	}
	if (isset($usersname)==false or isset($aadharnumber)==false or isset($mobile)==false or isset($email)==false)
	{
		echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Invalid data</span>';
		exit;
	}
	if (strlen($aadharnumber)!=12)
	{
		echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Invalid aadhar number</span>';
		exit;
	}
	if (strlen($mobile)==10)
	{
		echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Please include ISD (+91) with mobile number</span>';
		exit;
	}
	if (strlen($mobile)!=13)
	{
		echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Invalid mobile number</span>';
		exit;
	}
	if (isset($usersaddress)==false or strlen($usersaddress)<=5)
	{
		echo("Invalid address");
  		exit;
	}
	if (empty($email)==False)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) 
		{
	  		echo("Not a valid email address");
	  		exit;
		}
	}
	$connection ->autocommit(FALSE);
	$query = "update misuser set misuseractive=0,dldatetime='".currentdatetime()."',cruserid=".$_SESSION["usersid"]." where misuseractive=1 and misuserid=$userid";
	if (mysqli_query($connection, $query))
	{
		$query1 = "insert into misuser(misuserid,misusername,aadharnumber,misusermobile,misuseraddress,misemailaddress,miscustomerid,suspended,misuseractive) values ($userid,'$usersname',$aadharnumber,$mobile,'$usersaddress','$email',$customerid,$suspended,1)";
		if (mysqli_query($connection, $query1))
		{
	    	//require("../info/datalog.php");
	    	//$datalogconnection = datalog_connection();
	        //$grouptransactionid = getgrouptransactionid($datalogconnection);
	    	//logdata($datalogconnection,$grouptransactionid,$database_finance,$query);
	    	//logdata($datalogconnection,$grouptransactionid,$database_finance,$query1);
	    	$connection -> commit();
	    	//$datalogconnection->commit();
			echo '<li><a style="color:#f48" class="navbar" href="../data/user_list.php">User List</a><br/>';
			echo '<li><a style="color:#f48" class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
			echo '<span style="background-color:#0a0;color:#ff8;text-align:left;">User '.$usersname.' is updated successfully</span>';
			exit;
		}
		else
		{
			echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication Error</span>';
    		$connection -> rollback();
		}
	}
	else
	{
    	echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication Error</span>';
    	$connection -> rollback();
	}
?>