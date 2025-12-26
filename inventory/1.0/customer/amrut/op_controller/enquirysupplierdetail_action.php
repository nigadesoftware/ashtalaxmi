<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/enquirysupplierdetaillist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $enquirysupplierdetaillist1 = new enquirysupplierdetaillist($connection,290);
//$enquirysupplierdetaillist1->bankcode = $_POST['bankcode'];
    $enquirysupplierdetaillist1->newpage(true);
    $enquirysupplierdetaillist1->startreport();
    $enquirysupplierdetaillist1->endreport();
?>