<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/htnetpayment_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['htcode']=202;
    $_POST['villagecode']=0; */
    //$_POST['billperiodtransnumber']=1;
    //$_POST['bankcode']=0;
    //$_POST['bankbranchcode']=0;
    $connection = swapp_connection();
    $htnetpayment1 = new htnetpayment($connection,265,1,7,'HT Net Payment','HTnetpay_000','A4','P');
    $htnetpayment1->bankcode = $_POST['bankcode'];
    $htnetpayment1->bankbranchcode = $_POST['bankbranchcode'];
    $htnetpayment1->seasoncode = $_SESSION['yearperiodcode'];
    $htnetpayment1->billcategorycode = $_POST['billtypecode'];
    $htnetpayment1->billperiodnumber = $_POST['HT_Bill_Period_Number'];
    $htnetpayment1->flagcode = $_POST['flagcode'];
    $htnetpayment1->startreport();
    $htnetpayment1->endreport();
?>