<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/storesalesummary_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $register = new storesalesummary($connection,280);
    $register->fromdate = $_POST['From_Date'];
    $register->todate = $_POST['To_Date'];
    $register->circlecode = $_POST['circlecode'];
    $register->storesalecategorycode = $_POST['storesalecategorycode'];  
    $register->storesalestorecode = $_POST['storesalestorecode'];
    $register->recoverycrushingyearcode = $_POST['Season'];
    $register->startreport();
    $register->endreport();
?>