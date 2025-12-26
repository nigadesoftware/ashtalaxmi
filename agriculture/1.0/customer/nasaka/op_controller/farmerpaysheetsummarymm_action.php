<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/farmerpaysheetsummarymm_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $smry = new farmerpaysheetsummarymm($connection,290);
    $smry->fromdate = $_POST['From_Date'];
    $smry->todate = $_POST['To_Date'];
    if ($_POST['exportcsvfile']==1)
    {
        $smry->export();
    }
    else
    {
     $smry->newpage(true);
    $smry->startreport();
    $smry->endreport();
    }
   
?>