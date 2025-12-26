<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/invoiceheaderlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $invoiceheaderlist1 = new invoiceheaderlist($connection,290);
//$invoiceheaderlist1->bankcode = $_POST['bankcode'];
    $invoiceheaderlist1->newpage(true);
    $invoiceheaderlist1->startreport();
    $invoiceheaderlist1->endreport();
?>