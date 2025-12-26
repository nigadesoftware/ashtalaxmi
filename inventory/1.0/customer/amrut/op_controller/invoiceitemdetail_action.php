<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/invoiceitemdetaillist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $invoiceitemdetaillist1 = new invoiceitemdetaillist($connection,290);
//$invoiceitemdetaillist1->bankcode = $_POST['bankcode'];
    $invoiceitemdetaillist1->newpage(true);
    $invoiceitemdetaillist1->startreport();
    $invoiceitemdetaillist1->endreport();
?>