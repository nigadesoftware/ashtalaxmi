<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once("../tcpdf/examples/tcpdf_include.php");
    require_once("../op_model/otherweightslip_report.php");
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $otherweightslip1 = new otherweightslip($connection,260,1,7,$_POST['isgatepass']);
    $otherweightslip1->transactionnumber = $_POST['transactionnumber'];
    $otherweightslip1->isgatepass = $_POST['isgatepass'];
    $otherweightslip1->startreport();
    $otherweightslip1->endreport();
?>