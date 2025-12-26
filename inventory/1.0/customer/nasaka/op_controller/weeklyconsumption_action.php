<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/weeklyconsumption_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $consumption1 = new consumption($connection,290);
    $consumption1->fromdate = $_POST['From_Date'];
    $consumption1->todate = $_POST['To_Date'];   
    $consumption1->startreport();
    $consumption1->endreport();
?>