<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/paysheet_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $paysheet1 = new paysheet($connection,200);
    $paysheet1->financialyearcode = $_POST['Season'];
    $paysheet1->monthcode = $_POST['calendermonthcode'];
    $paysheet1->monthname = $_POST['calendermonth'];
    $paysheet1->paymentcategorycode=$_POST['paymentcategorycode'];
    $paysheet1->employeecategorycode=$_POST['employeecategorycode'];
    $paysheet1->startreport();
    $paysheet1->endreport();
?>