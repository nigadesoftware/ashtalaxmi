<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/slipwiseregisternew_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $register1 = new slipregister($connection,290);
    $register1->fromdate = $_POST['From_Date'];
    $register1->todate = $_POST['To_Date'];
    $register1->contractorcode = $_POST['contractorcode'];
    $register1->startreport();
    $register1->endreport();
?>