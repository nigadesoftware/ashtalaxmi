<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/contractorissue_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $issue1 = new issuelist($connection,290);
    $issue1->fromdate = $_POST['From_Date'];
    $issue1->todate = $_POST['To_Date'];
    $issue1->contractorcode = $_POST['contractorcode'];
    $issue1->startreport();
    $issue1->endreport();
?>