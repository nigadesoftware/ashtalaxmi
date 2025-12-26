<?php
	require('../info/phpsqlajax_dbinfo.php');
    require('../info/ncryptdcrypt.php');
	require("../info/phpgetloginview.php");
    require("../info/routine.php");
    
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
    /* if (!isset($_SESSION['legalentitycode']) and !isset($_SESSION['yearperiodcode']))
    { */
        /* if (defaultlegalentitycode()==false and defaultyear()==false)
        {
            header("location: ../mis/usermenu.php");
        }
        else
        { */
            defaultlegalentitycode();
            if (empty($_SESSION["yearperiodcode"]))
            {
                defaultyear();
            }
            //echo $_SESSION["legalentitycode"];
            //echo $_SESSION["yearperiodcode"];
            header("location: ../mis/entitymenu.php?yearperiodcode=".fnEncrypt($_SESSION['yearperiodcode']));    
        /* } */
    /* }
    else
    {
        header("location: ../mis/entitymenu.php?yearperiodcode=".fnEncrypt($_SESSION['yearperiodcode']));
    } */
    function defaultlegalentitycode()
    {
        $connection=swappdb_connection(); 
        $query = "SELECT * FROM nst_nasaka_finance.legalentity d";
        $result = oci_parse($connection, $query);
        $r = oci_execute($result);
        if (!$result)
        {
            die('Communication Error1');
        }
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $_SESSION["legalentitycode"] = $row['LEGALENTITYCODE'];
            return true;
        }
        else
        {
            return false;
        }
    }
    function defaultyear()
    {
        $connection=swappdb_connection();
        if (in_array($_SESSION['responsibilitycode'],array(123981564,123981247,123457423,123457740,123475858,123475224,123475541)))
        {
            $yearcategorycode=1;
        }
        elseif (in_array($_SESSION['responsibilitycode'],array(123477760,123478077,123478394,123478711,123479028
        ,123473322,123473639,123473956,123474273,123474590,123474907
        ,123476175,123476492,123476809,123477126,123477443
        ,451230287895415,475124562358965
        )))
        {
            $yearcategorycode=2;
        }
        else
        {
            $yearcategorycode=3;
        }
        //$query = "SELECT * FROM yearperiod d order by crushingfromdatetime desc";
        $query ="select y.yearperiodcode,y.periodname_eng from (select getcurrentyear(p_yearcategory => {$yearcategorycode}) as YEARPERIODCODE from dual)t
        ,yearperiod y where t.yearperiodcode=y.yearperiodcode";
        $result = oci_parse($connection, $query);
        $r = oci_execute($result);
        if (!$result)
        {
            die('Communication Error2');
        }
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $_SESSION["yearperiodcode"] =  $row['YEARPERIODCODE'];
            $_SESSION["financialyear"] = $row['PERIODNAME_ENG'];
            return true;
        }
        else
        {
            return false;
        }
    }
?>