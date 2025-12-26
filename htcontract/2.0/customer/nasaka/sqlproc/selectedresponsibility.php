<?php
	require('../info/phpsqlajax_dbinfo.php');
    require('../info/ncryptdcrypt.php');
	require("../info/phpgetloginview.php");
	
	function getresponsibilityname($resid)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname, $username, $password, $database);
        // Check connection
        if (mysqli_connect_errno())
          {
            echo "Communication Error";
          }
        $query = "SELECT m.misresponsibilityname FROM misresponsibility  m where m.misactive=1 and misresponsibilityid=".$resid." order by misresponsibilityname";                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            return $row['misresponsibilityname'];
        }
        else
        {
            echo "Communication Error";
            return 0;
        }
    }
    if (isset($_POST["responsibility_code"]))
    {
        $responsibilitycode = $_POST["responsibility_code"];
    }
    else
    {
        $responsibilitycode = fnDecrypt($_GET["responsibility_code"]);
    }
	

    if ($_POST["lng"] == 'Yes')
    {
        $lng = 'English';
	}
    else
    {
        $lng = 'Other';   
    }

    if (isset($responsibilitycode) == false )
	{
		echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Responsibility not selected</span>';
	  	exit;
	}
    $_SESSION["responsibilitycode"] = $responsibilitycode;
    $_SESSION["responsibilityname"] = getresponsibilityname($responsibilitycode);
    $_SESSION["lng"] = $lng;
    // Opens a connection to a MySQL server
    $connection=mysqli_connect($hostname, $username, $password, $database);
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Communication Error1";
        exit;
    }
    if ($_SESSION['changedefaultusersettings'] == 'on')
    {
        $query1 = "update userdefault set mismoduleid=".$_SESSION['mismoduleid'].",misresponsibilityid=".$_SESSION["responsibilitycode"]." where misuserid=".$_SESSION["usersid"];
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
    else
    {
        $connection -> commit();
    }
    //unset($_SESSION["entityid"]);
    //unset($_SESSION["subentityid"]);
    //unset($_SESSION["finalreportperiodid"]);
    if (!isset($_SESSION['entityid']) and !isset($_SESSION['finalreportperiodid']))
    {
        header("location: ../mis/usermenu.php");
    }
    else
    {
        header("location: ../data/entitymenu.php?finalreportperiodid=".fnEncrypt($_SESSION['finalreportperiodid']));
    }
?>