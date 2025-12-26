<?php
    require_once("../info/phpgetloginview.php");
    require_once("../info/ncryptdcrypt.php");
    require_once("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/trialbalancegroup_report.php');
    set_time_limit(0);
    $fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-Y');
    $connection = swapp_connection();
	$trialbalancegroup1 = new trialbalancegroup($connection,200);
    $trialbalancegroup1->fromdate = $fromdate;
    $trialbalancegroup1->todate = $todate;
    $trialbalancegroup1->yearcode = $_SESSION['yearperiodcode'];
    if ($_POST["exportcsvfile"]==1)
    {
        $trialbalancegroup1->export();
    }
    else
    {
        $trialbalancegroup1->newpage(true);
        $trialbalancegroup1->detail();
        $trialbalancegroup1->endreport();
    }
?>