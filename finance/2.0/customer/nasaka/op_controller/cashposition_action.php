<?php
    require_once("../info/phpgetloginview.php");
    require_once("../info/ncryptdcrypt.php");
    require_once("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/cashposition_report.php');
    set_time_limit(0);
    $fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-Y');
    $connection = swapp_connection();
	$cashposition1 = new cashposition($connection,295);
    $cashposition1->fromdate = $fromdate;
    $cashposition1->todate = $todate;
    $cashposition1->yearcode = $_SESSION['yearperiodcode'];
    $cashposition1->newpage(true);
    $cashposition1->group();
    //$cashposition1->grandfooter();
    $cashposition1->endreport();
?>