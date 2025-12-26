<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/issueitemdetaillist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $issueitemdetaillist1 = new issueitemdetaillist($connection,290);
//$issueitemdetaillist1->bankcode = $_POST['bankcode'];
    $issueitemdetaillist1->newpage(true);
    $issueitemdetaillist1->startreport();
    $issueitemdetaillist1->endreport();
?>