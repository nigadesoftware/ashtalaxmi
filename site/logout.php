<?php
	session_start();
	require("../info/phpsqlajax_dbinfo.php");
	
	function currentdatetime()
    {
        date_default_timezone_set("UTC");
        $dt = time();
        $dt = date('Y-m-d H:i:sP',$dt);
        return $dt;
    }
	if (isset($_SESSION['fb_access_token']))
    {
			// Opens a connection to a MySQL server
			$connection=mysqli_connect($hostname, $username, $password, $database);
			  // Check connection
			  if (mysqli_connect_errno())
			  {
			    echo "Communication Error";
			      exit;
			  }
			  $connection ->autocommit(FALSE);
			  $sessid=session_id();
			        $query = "update fbuserlogininformation
			        		set sessionenddatetime='".currentdatetime()."'
			        		where sessionid='".$sessid."' and fb_id='".$_SESSION['fb_id']."' and sessionenddatetime is null";
			        if (mysqli_query($connection, $query)) 
			        {
			            $connection -> commit();
			            unset($_SESSION['fb_access_token']);
						unset($_SESSION['fb_id']);
						unset($_SESSION['fb_name']);
						session_unset();
						session_destroy();
						header("location:../index.php");
						exit(); 
			        }
			        else
			        {
			          //echo $query;
			          echo 'Communication Error';
			          exit;
			        }
	}
	else
	{
		echo "Communication Error";
		exit;
	}
?>