<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/loadingslip_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $transactionnumber= $_POST["transactionnumber"];
    $connection = swapp_connection();
    $slip1 = new loadingslip($connection,290);
    $slip1->transactionnumber = $transactionnumber;
    
    $slip1->startreport();
    $slip1->endreport();
?>