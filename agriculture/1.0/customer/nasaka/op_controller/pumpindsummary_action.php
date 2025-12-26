<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/pumpindsummary_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $pumpdatesummary1 = new pumpdatesummary($connection,290);
    $pumpdatesummary1->pumpcode = $_POST['pumpcode'];
    $pumpdatesummary1->fromdate = $_POST['From_Date'];
    $pumpdatesummary1->todate = $_POST['To_Date'];
    $pumpdatesummary1->typecode = $_POST['contractortypecode'];
    $pumpdatesummary1->exportcsvfile = $_POST['exportcsvfile'];
    $pumpdatesummary1->newpage(true);
    if ($_POST['pumpcode']!='' and $_POST['exportcsvfile']==1)
    {
        $pumpdatesummary1->pumpdatesummaryexport();
    }
    else
    {
        $pumpdatesummary1->startreport();
        $pumpdatesummary1->endreport();
    }
?>