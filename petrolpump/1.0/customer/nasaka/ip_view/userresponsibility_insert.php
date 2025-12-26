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
	  echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Communication Error</span>';
	  exit;
	}
	if (isset($misuserid)==false or isset($misresponsibilityid)==false)
	{
		echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Communication Error</span>';
		exit;
	}
	if (($_SESSION["responsibilitycode"] != 621478512368915) and ($misresponsibilityid==621478512368915 or $misresponsibilityid==785236954125917))
	{
		echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Invalid Operation</span>';
		exit;
	}
	$result=mysqli_query($connection, "select count(*) as cnt from misuserresponsibility p 
		where p.misactive=1 and p.dldatetime is null and misresponsibilityid=$misresponsibilityid and misfactoryid=$customerid and misuserid=$misuserid");
	$row = oci_fetch_array($result,OCI_ASSOC);
	if ($row['CNT'] > 0) 
	{
		echo'<span style="background-color:#f44;color:#ff8;text-align:left;">User already have responsibility</span>';
		exit;
	}
	$connection -> autocommit(FALSE);
	$query = "insert into misuserresponsibility(misuserid,misresponsibilityid,misfactoryid,misactive,cruserid,crdatetime) values ($misuserid,$misresponsibilityid,$customerid,1,".$_SESSION["usersid"].",'".currentdatetime()."')";
	$r = oci_execute($result);
	if (oci_parse($connection, $query)) 
	{
    	//require("../info/datalog.php");
    	//$datalogconnection = datalog_connection();
	    //$grouptransactionid = getgrouptransactionid($datalogconnection);
    	//logdata($datalogconnection,$grouptransactionid,$database_finance,$query);
    	$connection -> commit();
    	//$datalogconnection->commit();	
		$userid_en = fnEncrypt($misuserid);
		echo '<span style="background-color:#0a0;color:#ff8;text-align:left;">User responsibility is granted successfully</span>';
		echo '<a style="color:#f48" class="navbar" href="../ip_view/userresponsibility_list.php?userid='.$userid_en.'"></br>User Responsibility List</a></br>';
		exit;		
	} 
	else 
	{
    	echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication Error</span>';
    	$connection -> rollback();
	}
?>