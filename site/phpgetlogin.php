<?php
    function checktimeout()
    {
        date_default_timezone_set("UTC");
        require('../info/phpsqlajax_dbinfo.php');
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname, $username, $password, $database);
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Communication Error";
            exit;
        }
        $connection ->autocommit(FALSE);
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'])> 10800) 
        {
            $query = "update fbuserlogininformation set sessionenddatetime=current_timestamp,loggedoutflag=2 where sessionenddatetime is null and fb_id=".$_SESSION['fb_id']." and sessionid='".$_SESSION['cursession']."'";
            //echo $query;
            if (mysqli_query($connection, $query)) 
            {
                $connection -> commit();
                return True;
            }
            else
            {
                return True;
            }
            $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
        }
        else
        {
            return False;
        }
    }
?>