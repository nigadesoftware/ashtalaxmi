<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/invoicebill_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $invoicebill1 = new invoicebill($connection,290);
    $invoicebill1->transactionnumber = $_POST['transactionnumber'];
    $invoicebill1->startreport();
    $invoicebill1->endreport();
?>