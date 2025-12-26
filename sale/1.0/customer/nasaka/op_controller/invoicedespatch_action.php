<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/invoicedespatch_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $fromdate= $_POST["fromdate"];
    $todate= $_POST["todate"];
    $connection = swapp_connection();
    $invoicedespatch1 = new invoicedespatch($connection,190);
    $fromdate = DateTime::createFromFormat('d/m/Y',$fromdate)->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$todate)->format('d-M-Y');
    $invoicedespatch1->fromdate = $fromdate;
    $invoicedespatch1->todate = $todate;
    $invoicedespatch1->goodscategorycode = $_POST["goodscategorycode"];
    $invoicedespatch1->newpage(true);
    $invoicedespatch1->detail();
    $invoicedespatch1->endreport();
?>