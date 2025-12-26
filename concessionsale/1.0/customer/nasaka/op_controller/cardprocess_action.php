<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/card_process.php');
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
    $process1 = new process($connection);
    $ret1=$process1->sharecompletion();
    if ($ret1 == 1)
    {
        oci_commit($connection);
    }
    $ret2=$process1->sharetransfer();
    if ($ret2 == 1)
    {
        oci_commit($connection);
    }
    $ret3=$process1->sharetransferallotment();
    if ($ret3 == 1)
    {
        oci_commit($connection);
    }


    $ret4=$process1->dues();
    if ($ret4==true)
    {
        if ($ret4 == 1)
        {
            oci_commit($connection);
        }
        header("Location: ../mis/entitymenu.php");
    }
    else {
        echo 'Card Not Processed';
        exit;
    }

?>