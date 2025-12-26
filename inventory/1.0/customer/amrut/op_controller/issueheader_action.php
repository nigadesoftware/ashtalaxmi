<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/issueheaderlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $issueheaderlist1 = new issueheaderlist($connection,290);
//$issueheaderlist1->bankcode = $_POST['bankcode'];
    $issueheaderlist1->newpage(true);
    $issueheaderlist1->startreport();
    $issueheaderlist1->endreport();
?>