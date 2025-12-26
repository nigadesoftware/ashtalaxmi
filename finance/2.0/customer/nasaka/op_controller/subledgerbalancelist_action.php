<?php
    require_once("../info/phpgetloginview.php");
    require_once("../info/ncryptdcrypt.php");
    require_once("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/subledgerbalancelist_report.php');
    set_time_limit(0);
    //$fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-Y');
    $accountcode = $_POST["accountcode"];
    $connection = swapp_connection();
	$subledgerbalancelist1 = new subledgerbalancelist($connection,200);
    $subledgerbalancelist1->todate = $todate;
    $subledgerbalancelist1->accountcode = $accountcode;
    $subledgerbalancelist1->yearcode = $_SESSION['yearperiodcode'];
    $subledgerbalancelist1->design=0;
    if ($_POST["exportcsvfile"]==1)
    {
        $subledgerbalancelist1->export();
    }
    else
    {
        $subledgerbalancelist1->newpage(true);
        $subledgerbalancelist1->detail();
        $subledgerbalancelist1->endreport();
    }
?>