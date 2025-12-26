<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/salaryslip_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $salaryslip1 = new salaryslip($connection,290);
    $salaryslip1->financialyearcode = $_POST['Season'];
    $salaryslip1->monthcode = $_POST['calendermonthcode'];
    $salaryslip1->monthname = $_POST['calendermonth'];
    $salaryslip1->paymentcategorycode=$_POST['paymentcategorycode'];
    $salaryslip1->employeecategorycode=$_POST['employeecategorycode'];
    $salaryslip1->employeecode=$_POST['employeecode'];
    $salaryslip1->startreport();
    $salaryslip1->endreport();
?>