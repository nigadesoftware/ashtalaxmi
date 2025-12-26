<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/negotiationitemdetaillist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $negotiationitemdetaillist1 = new negotiationitemdetaillist($connection,290);
//$negotiationitemdetaillist1->bankcode = $_POST['bankcode'];
    $negotiationitemdetaillist1->newpage(true);
    $negotiationitemdetaillist1->startreport();
    $negotiationitemdetaillist1->endreport();
?>