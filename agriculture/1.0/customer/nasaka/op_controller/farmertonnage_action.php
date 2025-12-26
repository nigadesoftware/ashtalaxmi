<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/farmertonnage_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $farmertonnage1 = new farmertonnage($connection,290);
    $farmertonnage1->farmercode = $_POST['farmercode'];
    $farmertonnage1->fromdate = $_POST['From_Date'];
    $farmertonnage1->todate = $_POST['To_Date'];
    $farmertonnage1->startreport();
    $farmertonnage1->endreport();
?>