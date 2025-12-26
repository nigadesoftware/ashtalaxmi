<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/harvestingprogram_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $harvestingprogram1 = new harvestingprogram($connection,285);
    $harvestingprogram1->programnumber = $_POST['Harvesting_Program_Number'];
    $harvestingprogram1->centrecode = $_POST['centrecode'];
    $harvestingprogram1->villagecode = $_POST['villagecode'];
    $harvestingprogram1->plantationhangamcode = $_POST['plantationhangamcode'];
    $harvestingprogram1->startreport();
    $harvestingprogram1->endreport();
?>