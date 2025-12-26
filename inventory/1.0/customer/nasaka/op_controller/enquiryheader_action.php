<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/enquiryheaderlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $enquiryheaderlist1 = new enquiryheaderlist($connection,290);
//$enquiryheaderlist1->bankcode = $_POST['bankcode'];
    $enquiryheaderlist1->newpage(true);
    $enquiryheaderlist1->startreport();
    $enquiryheaderlist1->endreport();
?>