<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/htpaysheetsummary_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['htcode']=202;
    $_POST['paysheetperiodtransnumber']=1;
    $_POST['circlecode']=0;
    $_POST['villagecode']=0; */
    $connection = swapp_connection();
    $htpaysheetsummary1 = new htpaysheetsummary($connection,160,1,7,'HT Paysheet Summary','HTPSSUM_000','A4','L');
    $htpaysheetsummary1->billcategorycode = $_POST['billtypecode'];
    $htpaysheetsummary1->billperiodnumber = $_POST['HT_Bill_Period_Number'];
    $htpaysheetsummary1->flagcode = $_POST['flagcode'];
    $htpaysheetsummary1->startreport();
    $htpaysheetsummary1->endreport();
?>