<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/quotationitemdetaillist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $quotationitemdetaillist1 = new quotationitemdetaillist($connection,290);
//$quotationitemdetaillist1->bankcode = $_POST['bankcode'];
    $quotationitemdetaillist1->newpage(true);
    $quotationitemdetaillist1->startreport();
    $quotationitemdetaillist1->endreport();
?>