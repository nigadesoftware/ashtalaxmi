<?php
	require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
	require("../info/swapproutine.php");
    //System Admin,Admin
    if (isaccessible(621478512368915)==0 and isaccessible(785236954125917)==0)
    {
        echo 'Communication Error';
        exit;
    }
	$misuserid = $_POST["misuserid"];
	$misresponsibilityid = $_POST["misresponsibilityid"];
	// Opens a connection to a MySQL server
	$connection=mysqli_connect($hostname, $username, $password, $database);
	// Check connection
	if (mysqli_connect_errno())
	{
	  echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication error</span>';
	  exit;
	}
	if (isset($misuserid)==false or isset($misresponsibilityid) == false)
	{
		echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Invalid data</span>';
		exit;
	}
	if (isset($misresponsibilityid) == false) 
	{
		echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Invalid responsibility</span>';
		exit;
	}
	if (($_SESSION["responsibilitycode"] != 621478512368915) and ($misresponsibilityid == 621478512368915 
		or $misresponsibilityid==785236954125917))
	{
		echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Invalid operation</span>';
		exit;
	}
	$result=mysqli_query($connection, "select count(*) as cnt from misuserresponsibility p 
		where p.misactive=1 and misresponsibilityid=$misresponsibilityid and misfactoryid=$customerid and misuserid=$misuserid");
	$row = oci_fetch_array($result,OCI_ASSOC);
	if ($row["cnt"] = 0) 
	{
		echo'<span style="background-color:#f44;color:#ff8;text-align:left;">User dont have responsibility</span>';
		exit;
	}
	$connection -> autocommit(FALSE);
	$query = "update misuserresponsibility set misactive=0,dldatetime='".currentdatetime()."',cruserid=".$_SESSION["usersid"]." where misactive=1 and misresponsibilityid=$misresponsibilityid and misfactoryid=$customerid and misuserid=$misuserid";
	$r = oci_execute($result);
	if (oci_parse($connection, $query))
	{
	    //require("../info/datalog.php");
    	//$datalogconnection = datalog_connection();
	    //$grouptransactionid = getgrouptransactionid($datalogconnection);
    	//logdata($datalogconnection,$grouptransactionid,$database_finance,$query);
    	$connection -> commit();
    	//$datalogconnection -> commit();	
		$userid_en = fnEncrypt($misuserid);
		echo '<li><a  class="navbar" href="../mis/userresponsibility_list.php?userid='.$userid_en.'">User Responsibility List</a><br/>';
		echo '<li><a  class="navbar" href="../../../../../sqlproc/logout.php">Log Out</a><br/>';
		echo '<span style="background-color:#0a0;color:#ff8;text-align:left;">User responsibility is revoked successfully</span>';
		exit;
	}
	else
	{
    	echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Communication error</span>';
    	$connection -> rollback();
	}
?>