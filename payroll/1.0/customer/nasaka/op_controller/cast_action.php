<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/castelist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $castelist1 = new castelist($connection,290);
//$castelist1->bankcode = $_POST['bankcode'];
    $castelist1->newpage(true);
    $castelist1->startreport();
    $castelist1->endreport();
?>