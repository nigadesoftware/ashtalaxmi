<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/concessionsaleinvoice_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $concessionsaleinvoice1 = new concessionsaleinvoice($connection,290,1,7,'','CONCSALEINVOICE_000');
    $concessionsaleinvoice1->seasonyear = $_POST['seasonyear'];
    $concessionsaleinvoice1->invoicenumber = $_POST['invoicenumber'];
    $concessionsaleinvoice1->transactionnumber = $_POST['transactionnumber'];
    $concessionsaleinvoice1->startreport();
    $concessionsaleinvoice1->endreport();
?>