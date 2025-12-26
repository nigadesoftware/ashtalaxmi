<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/goodreceiptregister_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $goodreceiptregister = new goodreceiptregister($connection,290);
    $goodreceiptregister->fromdate = $_POST['From_Date'];
    $goodreceiptregister->todate = $_POST['To_Date'];
    //  $goodreceiptregister->sectioncode = $_POST['From_Date'];
    $goodreceiptregister->startreport();
    $goodreceiptregister->endreport();
?>