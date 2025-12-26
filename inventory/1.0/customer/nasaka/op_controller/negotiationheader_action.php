<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/negotiationheaderlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $negotiationheaderlist1 = new negotiationheaderlist($connection,290);
//$negotiationheaderlist1->bankcode = $_POST['bankcode'];
    $negotiationheaderlist1->newpage(true);
    $negotiationheaderlist1->startreport();
    $negotiationheaderlist1->endreport();
?>