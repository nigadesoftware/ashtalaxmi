<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/concessionsaleregister_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $concessionsaleregister1 = new concessionsaleregister($connection,260,1,7,'','CONSALEREG_000');
    $concessionsaleregister1->seasoncode = $_SESSION['yearperiodcode'];
    $concessionsaleregister1->fromdate = $_POST['From_Date'];
    if ($_POST['To_Date']=='')
        $concessionsaleregister1->todate = $_POST['From_Date'];
    else
        $concessionsaleregister1->todate = $_POST['To_Date'];
    $concessionsaleregister1->startreport();
    $concessionsaleregister1->endreport();
?>