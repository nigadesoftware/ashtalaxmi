<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/invoicenew_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $transactionnumber= $_POST["transactionnumber"];
    
    $connection = swapp_connection();
	$invoice1 = new invoice($connection,270);
    $invoice1->transactionnumber = $transactionnumber;
    
    $invoice1->startreport();
    $invoice1->endreport();
?>