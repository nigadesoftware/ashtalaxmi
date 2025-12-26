<?php
    require_once("../info/phpgetloginview.php");
    require_once("../info/ncryptdcrypt.php");
    require_once("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/mainledger_report.php');
    set_time_limit(0);
    $fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-Y');
    $accountcode = $_POST["accountcode"];
    $connection = swapp_connection();
	$mainledger1 = new mainledger($connection,275);
    $mainledger1->fromdate = $fromdate;
    $mainledger1->todate = $todate;
    $mainledger1->accountcode = $accountcode;
    $mainledger1->yearcode = $_SESSION['yearperiodcode'];
    $mainledger1->newpage(true);
    $mainledger1->detail();
    $mainledger1->endreport();
?>