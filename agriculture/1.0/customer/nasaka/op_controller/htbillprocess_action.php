<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/htbill_process.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['farmercode']=202;
    $_POST['paysheetperiodtransnumber']=1;
    $_POST['circlecode']=0;
    $_POST['villagecode']=0; */
    $connection = swapp_connection();
    $htbillprocess1 = new htbillprocess($connection);
    $htbillprocess1->billperiodtransnumber = $_POST['billperiodtransnumber'];
    if ($_POST['unpost']==1)
    {
        $ret=$htbillprocess1->htbillprocessunpost();
    }
    else
    {
        $ret=$htbillprocess1->htbillprocess();
    }
    if ($ret==true)
    {
        header("Location: ../mis/entitymenu.php");
    }
    else {
        echo 'HT Bill Not Processed';
        exit;
    }

?>