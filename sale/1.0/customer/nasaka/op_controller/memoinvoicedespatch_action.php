<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/memoinvoicedespatch_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $fromdate= $_POST["fromdate"];
    $todate= $_POST["todate"];
    $connection = swapp_connection();
    $memoinvoicedespatch1 = new memoinvoicedespatch($connection,190);
    $fromdate = DateTime::createFromFormat('d/m/Y',$fromdate)->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$todate)->format('d-M-Y');
    $memoinvoicedespatch1->fromdate = $fromdate;
    $memoinvoicedespatch1->todate = $todate;
    $memoinvoicedespatch1->goodscategorycode = $_POST["goodscategorycode"];
    $memoinvoicedespatch1->newpage(true);
    $memoinvoicedespatch1->detail();
    $memoinvoicedespatch1->endreport();
?>