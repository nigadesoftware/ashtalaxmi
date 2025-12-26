<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/productionsaleinfo_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $production1 = new production($connection,290);   
    $production1->fromdate = $_POST['fromdate'];
    $production1->todate = $_POST['todate'];
    $production1->startreport();
    $production1->endreport();
?>