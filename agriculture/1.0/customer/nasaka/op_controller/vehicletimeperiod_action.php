<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/vehicletimeperiod_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $vehicletimeperiod1 = new vehicletimeperiod($connection,275);
    $vehicletimeperiod1->fromdate = $_POST['From_Date'];
    if ($_POST['To_Date']!='')
    $vehicletimeperiod1->todate = $_POST['To_Date'];
    else
    $vehicletimeperiod1->todate = $_POST['From_Date'];
    $vehicletimeperiod1->circlecode = $_POST['circlecode'];
    $vehicletimeperiod1->vehiclecategorycode = $_POST['vehiclecategorycode'];
    $vehicletimeperiod1->startreport();
    $vehicletimeperiod1->endreport();
?>