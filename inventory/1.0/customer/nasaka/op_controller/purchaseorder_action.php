<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/purchaseorder_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $purchaseorder1 = new purchaseorder($connection,290);
    $purchaseorder1->fromdate = $_POST['From_Date'];
    $purchaseorder1->todate = $_POST['To_Date'];
    $purchaseorder1->transactionnumber = $_POST['transactionnumber'];
    //$purchaseorder1->newpage(true);
    $purchaseorder1->startreport();
    $purchaseorder1->endreport();
?>