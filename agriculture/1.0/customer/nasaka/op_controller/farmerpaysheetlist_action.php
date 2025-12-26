<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/farmerpaysheetlist_report.php');
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
    $farmerpaysheetlist1 = new farmerpaysheetlist($connection,250,1,7,'Farmer paysheet list','FPSLIST_000','A4','P');
    $farmerpaysheetlist1->circlecode = $_POST['circlecode'];
    $farmerpaysheetlist1->villagecode = $_POST['villagecode'];
    $farmerpaysheetlist1->farmercode = $_POST['farmercode'];
    $farmerpaysheetlist1->billperiodtransnumber = $_POST['billperiodtransnumber'];
    $farmerpaysheetlist1->startreport();
    $farmerpaysheetlist1->endreport();
?>