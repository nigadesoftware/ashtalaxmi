<?php
	function datalog_connection()
	{
		require("../info/phpsqlajax_dbinfo.php");
		// Opens a connection to a MySQL server
		$datalogconnection=mysqli_connect($hostname_datalog, $username_datalog, $password_datalog, $database_datalog);
		// Check connection
		if (mysqli_connect_errno())
		{
		 	echo "Communication Error";
		}
		mysqli_query($datalogconnection,'SET NAMES UTF8');
		$datalogconnection ->autocommit(FALSE);
		return $datalogconnection;
	}

	function logdata(&$datalogconnection,$grouptransactionid,$db,$query)
    {
    	$query = "insert into datalog(customerid,sessionid,db,query,grouptransactionid,active,cruserid,crdatetime) values (".$_SESSION['factorycode'].",'".$_SESSION["cursession"]."',".'"$db","'.$query.'"'.",$grouptransactionid,1,".$_SESSION["usersid"].",'".datalogdatetime()."')";
    	mysqli_query($datalogconnection, $query);
    }
    function datalogdatetime()
    {
        date_default_timezone_set("UTC");
        $dt = time();
        $dt = date('d-M-Y H:i:sP',$dt);
        return $dt;
    }
    function getgrouptransactionid(&$datalogconnection)
    {
    	$query = "select coalesce(max(grouptransactionid),0)+1 as grouptransactionid from datalog";
    	$result = mysqli_query($datalogconnection,$query);
		if ($row = @mysqli_fetch_assoc($result))
		{
			return $row['grouptransactionid'];
		}
		else
		{
			return 10;
		}
    }
?>