<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/plantationregister_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $plantationregister1 = new plantationregister($connection,285);
    $plantationregister1->centrecode = $_POST['centrecode'];
    $plantationregister1->villagecode = $_POST['villagecode'];
    $plantationregister1->fromdate = $_POST['From_Date'];
    $plantationregister1->todate = $_POST['To_Date'];
    $plantationregister1->plantationhangamcode = $_POST['plantationhangamcode'];
    //$plantationregister1->newpage(true);
    $plantationregister1->startreport();
    $plantationregister1->endreport();
?>