<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/gatepass_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $transactionnumber= $_POST["transactionnumber"];
    
    $connection = swapp_connection();
	$invoice1 = new gatepass($connection,290);
    $invoice1->transactionnumber = $transactionnumber;
    
    $invoice1->newpage(true);
    $invoice1->detail();
    $invoice1->endreport();
?>