<?php
    require_once("../info/phpgetloginview.php");
    require_once("../info/ncryptdcrypt.php");
    require_once("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/subledgeryearbalancelist_report.php');
    set_time_limit(0);
    //$fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-Y');
    $accountcode = $_POST["accountcode"];
    $connection = swapp_connection();
	$subledgeryearbalancelist1 = new subledgeryearbalancelist($connection,275);
    $subledgeryearbalancelist1->todate = $todate;
    $subledgeryearbalancelist1->accountcode = $accountcode;
    $subledgeryearbalancelist1->yearcode = $_SESSION['yearperiodcode'];
    $subledgeryearbalancelist1->seasoncode = $_POST['seasoncode'];
    $subledgeryearbalancelist1->design=0;
    if ($_POST["exportcsvfile"]==1)
    {
        $subledgeryearbalancelist1->export();
    }
    else
    {
        $subledgeryearbalancelist1->detail();
        $subledgeryearbalancelist1->endreport();
    }
?>