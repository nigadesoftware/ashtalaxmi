<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/farmerpaysheet_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['farmercode']=202;
    $_POST['paysheetperiodtransnumber']=1;
    $_POST['circlecode']=0;
    $_POST['villagecode']=0; */
    $connection = swapp_connection();
    $farmerpaysheet1 = new farmerpaysheet($connection,255,1,7,'Farmer paysheet','Fpaysheet_000','A3','L');
    $farmerpaysheet1->circlecode = $_POST['circlecode'];
    $farmerpaysheet1->villagecode = $_POST['villagecode'];
    $farmerpaysheet1->farmercode = $_POST['farmercode'];
    $farmerpaysheet1->farmercategorycode = $_POST['farmercategorycode'];
    $farmerpaysheet1->billperiodtransnumber = $_POST['billperiodtransnumber'];
    $farmerpaysheet1->startreport();
    $farmerpaysheet1->endreport();
?>