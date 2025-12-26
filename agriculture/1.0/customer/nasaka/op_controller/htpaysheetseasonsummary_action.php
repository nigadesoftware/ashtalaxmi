<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/htpaysheetseasonsummary_report.php');
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
    $htpaysheetseasonsummary1 = new htpaysheetseasonsummary($connection,160,1,7,'HT Paysheet Season Summary','HTPSSUM_000','A4','L');
    $htpaysheetseasonsummary1->billcategorycode = $_POST['billtypecode'];
    $htpaysheetseasonsummary1->flagcode = $_POST['flagcode'];
    $htpaysheetseasonsummary1->startreport();
    $htpaysheetseasonsummary1->endreport();
?>