<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/itembalancelist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $itembalancelist1 = new itembalancelist($connection,290);
    $itembalancelist1->mainstorecode = $_POST['mainstorecode'];
    $itembalancelist1->substorecode = $_POST['substorecode'];
    $itembalancelist1->fromdate = $_POST['From_Date'];
    $itembalancelist1->todate = $_POST['To_Date'];
    $itembalancelist1->newpage(true);
    $itembalancelist1->startreport();
    $itembalancelist1->endreport();
?>