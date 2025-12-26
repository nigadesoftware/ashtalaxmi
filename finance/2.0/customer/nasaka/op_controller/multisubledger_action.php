<?php
    require_once("../info/phpgetloginview.php");
    require_once("../info/ncryptdcrypt.php");
    require_once("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/multisubledger_report.php');
    set_time_limit(0);
    $fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-Y');
    $referencecode = $_POST["referencecode"];
    $connection = swapp_connection();
	$subledger = new multisubledger($connection,275);
    $subledger->fromdate = $fromdate;
    $subledger->todate = $todate;
    $subledger->referencecode = $referencecode;
    $subledger->yearcode = $_SESSION['yearperiodcode'];
    $subledger->design=0;
    //$subledger->newpage(true);
    $subledger->group();
    $subledger->endreport();
?>