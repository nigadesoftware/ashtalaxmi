<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/vehiclepumpdiesel_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $vehiclepumpdiesel1 = new vehiclepumpdiesel($connection,290);
    $vehiclepumpdiesel1->fromdate = $_POST['From_Date'];
    $vehiclepumpdiesel1->todate = $_POST['To_Date'];
    $vehiclepumpdiesel1->newpage(true);
    if ($_POST['exportcsvfile']==1)
    {
        $vehiclepumpdiesel1->vehiclepumpdieselexport();
    }
    else
    {
        $vehiclepumpdiesel1->startreport();
        $vehiclepumpdiesel1->endreport();
    }
?>