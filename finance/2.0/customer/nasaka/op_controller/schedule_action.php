<?php
    require_once("../info/phpgetloginview.php");
    require_once("../info/ncryptdcrypt.php");
    require_once("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/schedule_report.php');
    set_time_limit(0);
    //$fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-Y');
    $grouptypecode = $_POST["grouptypecode"];
    $connection = swapp_connection();
	$schedule1 = new schedule($connection,260);
    $schedule1->design=0;
    $schedule1->todatecur = $todate;
    $schedule1->todatepre = date('d-M-Y', strtotime("-1 years", strtotime($todate)));
    $schedule1->grouptypecode = $grouptypecode;
    $schedule1->yearcodecur = $_SESSION['yearperiodcode'];
    $schedule1->yearcodepre = $_SESSION['yearperiodcode']-10001;
    //$schedule1->newpage(true);
    $schedule1->schedule();
    $schedule1->endreport();
?>