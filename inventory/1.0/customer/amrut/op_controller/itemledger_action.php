<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/itemledger_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $itemledger1 = new itemledger($connection,275);
    $itemledger1->itemcode = $_POST['itemcode'];
    $itemledger1->fromdate = $_POST['From_Date'];
    $itemledger1->todate = $_POST['To_Date'];
    $itemledger1->startreport();
    $itemledger1->endreport();
?>