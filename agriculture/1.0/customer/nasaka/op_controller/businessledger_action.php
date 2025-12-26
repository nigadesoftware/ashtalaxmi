<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/businessledger_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['htcode']=202;
    $_POST['paysheetperiodtransnumber']=1;
    $_POST['circlecode']=0;
    $_POST['villagecode']=0; */
    $connection = swapp_connection();
    $businessledger1 = new businessledger($connection,275,1,7,'HT paysheet','businessledger_000','A3','L');
    $businessledger1->contractorcode = $_POST['contractorcode'];
    $businessledger1->subcontractorcode = $_POST['subcontractorcode'];
    $businessledger1->vehiclecode = $_POST['vehiclecode'];
    $businessledger1->startreport();
    $businessledger1->endreport();
?>