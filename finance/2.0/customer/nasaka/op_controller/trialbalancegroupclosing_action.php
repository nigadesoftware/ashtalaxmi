<?php
    require_once("../info/phpgetloginview.php");
    require_once("../info/ncryptdcrypt.php");
    require_once("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/trialbalancegroupclosing_report.php');
    set_time_limit(0);
    $fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-Y');
    $connection = swapp_connection();
	$trialbalancegroupclosing1 = new trialbalancegroupclosing($connection,270);
    $trialbalancegroupclosing1->fromdate = $fromdate;
    $trialbalancegroupclosing1->todate = $todate;
    $trialbalancegroupclosing1->yearcode = $_SESSION['yearperiodcode'];
    $trialbalancegroupclosing1->newpage(true);
    $trialbalancegroupclosing1->detail();
    $trialbalancegroupclosing1->endreport();
?>