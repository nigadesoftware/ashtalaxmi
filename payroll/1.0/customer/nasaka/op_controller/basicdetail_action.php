<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/basicdetaillist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $basicdetaillist1 = new basicdetaillist($connection,290);
//$basicdetaillist1->bankcode = $_POST['bankcode'];
    $basicdetaillist1->newpage(true);
    $basicdetaillist1->startreport();
    $basicdetaillist1->endreport();
?>