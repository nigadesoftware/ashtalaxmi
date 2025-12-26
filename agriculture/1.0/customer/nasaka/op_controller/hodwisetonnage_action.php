<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/hodwisetonnage_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    
    $connection = swapp_connection();
    $tonnage1 = new tonnagesmry($connection,290);
    $tonnage1->fromdate =$_POST['From_Date'];
    $tonnage1->todate = $_POST['To_Date'];
    $tonnage1->startreport();
    $tonnage1->endreport();
    
?>