<?php
    require_once("../info/phpgetloginview.php");
    require_once("../info/ncryptdcrypt.php");
    require_once("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/journalregister_report.php');
    set_time_limit(0);
    $fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-Y');
    $connection = swapp_connection();
	$journalregister1 = new journalregister($connection,265);
    $journalregister1->fromdate = $fromdate;
    $journalregister1->todate = $todate;
    $journalregister1->vouchersubtypecode = 19;
    $journalregister1->yearcode = $_SESSION['yearperiodcode'];
    $journalregister1->newpage(true);
    $journalregister1->detail();
    $journalregister1->endreport();
?>