<?php
    require_once("../info/phpgetloginview.php");
    require_once("../info/ncryptdcrypt.php");
    require_once("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/bankchequeissue_report.php');
    set_time_limit(0);
    $fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-Y');
    $accountcode = $_POST["accountcode"];
    $connection = swapp_connection();
	$bankchequeissue1 = new bankchequeissue($connection,275);
    $bankchequeissue1->fromdate = $fromdate;
    $bankchequeissue1->todate = $todate;
    $bankchequeissue1->bankcode = $accountcode;
    $bankchequeissue1->yearcode = $_SESSION['yearperiodcode'];
    $bankchequeissue1->design=0;
    if ($_POST["exportcsvfile"]==1)
    {
        $bankchequeissue1->export();
    }
    else
    {
        $bankchequeissue1->newpage(true);
        $bankchequeissue1->detail();
        $bankchequeissue1->endreport();
    }
?>