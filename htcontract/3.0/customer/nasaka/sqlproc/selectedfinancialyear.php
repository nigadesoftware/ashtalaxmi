<?php
	require('../info/phpsqlajax_dbinfo.php');
    require('../info/ncryptdcrypt.php');
	require("../info/phpgetloginview.php");
    require("../info/swapproutine.php");
	
	$yearperiodcode_de = fnDecrypt($_GET["yearperiodcode"]);
    
    if (isset($yearperiodcode_de) == false )
	{
		echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Financial Year not selected</span>';
	  	exit;
	}
        $_SESSION["yearperiodcode"] =  $yearperiodcode_de;
        $_SESSION["finalreportperiodid"] = $yearperiodcode_de;
        $_SESSION["financialyear"] = $yearperiodcode_de;
        $dt = getcurrentworkingday();
        if ($dt!='')
        {
            $_SESSION["currentworkingday"] = $dt;    
        }
        else
        {
            unset($_SESSION["currentworkingday"]);
        }
        setdefaultdate($yearperiodcode_de);
        if (!isset($_SESSION['financialyear']))
        {
            header("location: ../mis/usermenu.php");
        }
        else
        {
            // Opens a connection to a MySQL server
            $connection=mysqli_connect($hostname, $username, $password, $database);
            // Check connection
            if (mysqli_connect_errno())
            {
                echo "Communication Error";
            }
            if ($_SESSION['changedefaultusersettings'] == 'on')
            {
                $query1 = "update userdefault set yearperiodcode=".$_SESSION["yearperiodcode"]." where misuserid=".$_SESSION["usersid"]." and active=1";
                if (mysqli_query($connection, $query1))
                {
                    $connection -> commit();
                }
                else
                {
                    echo "Communication Error3";
                    exit;
                }
            }
            header("location: ../mis/entitymenu.php?yearperiodcode=".fnEncrypt($_SESSION['yearperiodcode']));
        }

    function setdefaultdate($yearperiodcode)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection = mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
        {
            //echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication error</span>';
            exit;
        }
        mysqli_query($connection,'SET NAMES UTF8');
        $entityglobalgroupid = $_SESSION['entityglobalgroupid'];
        $yearperiodcode = $_SESSION['yearperiodcode'];

        $query = "select perioddatetimefrom,perioddatetimeto from finalreportperiod f where f.active=1 and f.yearperiodcode=".$yearperiodcode;
        //echo $query;
        $result = mysqli_query($connection,$query);
        if ($row = mysqli_fetch_assoc($result))
        {
            $_SESSION['fromdate'] = $row['perioddatetimefrom'];
            $_SESSION['todate'] = $row['perioddatetimeto'];
        }
    }
	
?>