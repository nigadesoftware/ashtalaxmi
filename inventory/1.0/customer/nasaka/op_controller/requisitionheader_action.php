<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/requisitionheaderlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $requisitionheaderlist1 = new requisitionheaderlist($connection,290);
//$requisitionheaderlist1->bankcode = $_POST['bankcode'];
    $requisitionheaderlist1->newpage(true);
    $requisitionheaderlist1->startreport();
    $requisitionheaderlist1->endreport();
?>