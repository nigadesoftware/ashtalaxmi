<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/enquiryitemdetaillist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $enquiryitemdetaillist1 = new enquiryitemdetaillist($connection,290);
//$enquiryitemdetaillist1->bankcode = $_POST['bankcode'];
    $enquiryitemdetaillist1->newpage(true);
    $enquiryitemdetaillist1->startreport();
    $enquiryitemdetaillist1->endreport();
?>