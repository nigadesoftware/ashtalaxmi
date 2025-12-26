<?php
    require_once("../info/phpgetloginview.php");
    require_once("../info/ncryptdcrypt.php");
    require_once("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/subledger_report.php');
    set_time_limit(0);
    $fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-Y');
    $accountcode = $_POST["accountcode"];
    $subledgercode=$_POST["subledgercode"];
    $connection = swapp_connection();
	$subledger = new subledger($connection,275);
    $subledger->fromdate = $fromdate;
    $subledger->todate = $todate;
    $subledger->accountcode = $accountcode;
    $subledger->subledgercode = $subledgercode;
    $subledger->yearcode = $_SESSION['yearperiodcode'];
    $subledger->design=0;
    $subledger->newpage(true);
    $subledger->detail();
    $subledger->endreport();
?>