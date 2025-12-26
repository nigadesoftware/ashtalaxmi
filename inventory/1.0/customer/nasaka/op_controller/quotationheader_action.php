<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/quotationheaderlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $quotationheaderlist1 = new quotationheaderlist($connection,290);
//$quotationheaderlist1->bankcode = $_POST['bankcode'];
    $quotationheaderlist1->newpage(true);
    $quotationheaderlist1->startreport();
    $quotationheaderlist1->endreport();
?>