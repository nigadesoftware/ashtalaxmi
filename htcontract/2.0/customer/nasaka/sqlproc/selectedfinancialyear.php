<?php
	require('../info/phpsqlajax_dbinfo.php');
    require('../info/ncryptdcrypt.php');
	require("../info/phpgetloginview.php");
    require("../info/swapproutine.php");
	
	$finalreportperiodid_de = fnDecrypt($_GET["finalreportperiodid"]);
    
    if (isset($finalreportperiodid_de) == false )
	{
		echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Financial Year not selected</span>';
	  	exit;
	}
        $_SESSION["finalreportperiodid"] =  $finalreportperiodid_de;
        $_SESSION["financialyear"] = financial_year(0);
        $dt = getcurrentworkingday();
        if ($dt!='')
        {
            $_SESSION["currentworkingday"] = $dt;    
        }
        else
        {
            unset($_SESSION["currentworkingday"]);
        }
        setdefaultdate($finalreportperiodid_de);
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
                $query1 = "update userdefault set finalreportperiodid=".$_SESSION["finalreportperiodid"]." where misuserid=".$_SESSION["usersid"]." and active=1";
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
            header("location: ../data/entitymenu.php?finalreportperiodid=".fnEncrypt($_SESSION['finalreportperiodid']));
        }

    function setdefaultdate($finalreportperiodid)
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
        $finalreportperiodid = $_SESSION['finalreportperiodid'];

        $query = "select perioddatetimefrom,perioddatetimeto from finalreportperiod f where f.active=1 and f.finalreportperiodid=".$finalreportperiodid;
        //echo $query;
        $result = mysqli_query($connection,$query);
        if ($row = mysqli_fetch_assoc($result))
        {
            $_SESSION['fromdate'] = $row['perioddatetimefrom'];
            $_SESSION['todate'] = $row['perioddatetimeto'];
        }
    }
	
?>