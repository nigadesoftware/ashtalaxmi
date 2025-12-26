<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/htpaysheetcontractor_report.php');
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
    $htpaysheetcontractor1 = new htpaysheetcontractor($connection,275,1,7,'HT paysheet Contractor','HTCONPAYSH_000','A3','L');
    $htpaysheetcontractor1->billperiodtransnumber = $_POST['billperiodtransnumber'];
    $htpaysheetcontractor1->startreport();
    $htpaysheetcontractor1->endreport();
?>