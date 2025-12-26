<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once("../tcpdf/examples/tcpdf_include.php");
    require_once("../op_model/weightslip_report.php");
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $weightslip1 = new weightslip($connection,200);
    $weightslip1->transactionnumber = $_POST['transactionnumber'];
    $weightslip1->startreport();
    $weightslip1->endreport();
?>