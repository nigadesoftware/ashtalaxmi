<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/farmerledger_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['farmercode']=202;
    $_POST['ledgerperiodtransnumber']=1;
    $_POST['circlecode']=0;
    $_POST['villagecode']=0; */
    $connection = swapp_connection();
    $farmerledger1 = new farmerledger($connection,285,1,7,'Farmer ledger','Fledger_000','A4','P');
    $farmerledger1->seasonyear = $_POST['Season'];
    $farmerledger1->farmercode = $_POST['farmercode'];
    $farmerledger1->startreport();
    $farmerledger1->endreport();
?>