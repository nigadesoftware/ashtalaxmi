<?php
    require_once("../info/phpgetloginview.php");
    require_once("../info/ncryptdcrypt.php");
    require_once("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/trialbalancedetailed_report.php');
    set_time_limit(0);
    $fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-Y');
    $connection = swapp_connection();
	$trialbalancedetailed1 = new trialbalancedetailed($connection,200);
    $trialbalancedetailed1->fromdate = $fromdate;
    $trialbalancedetailed1->todate = $todate;
    $trialbalancedetailed1->yearcode = $yearcode = $_SESSION['yearperiodcode'];
    if ($_POST["exportcsvfile"]==1)
    {
        $trialbalancedetailed1->export();
    }
    else
    {
        $trialbalancedetailed1->newpage(true);
        $trialbalancedetailed1->detail();
        $trialbalancedetailed1->endreport();
    }
?>