<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/farmerbill_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['farmercode']=202;
    $_POST['billperiodtransnumber']=1;
    $_POST['circlecode']=0;
    $_POST['villagecode']=0; */
    $connection = swapp_connection();
    $farmerbill1 = new farmerbill($connection,285,1,7,'Farmer Bill','FBILL_000','A5','L');
    $farmerbill1->circlecode = $_POST['circlecode'];
    $farmerbill1->villagecode = $_POST['villagecode'];
    $farmerbill1->farmercode = $_POST['farmercode'];
    $farmerbill1->billperiodtransnumber = $_POST['billperiodtransnumber'];
    $farmerbill1->startreport();
    $farmerbill1->endreport();
?>