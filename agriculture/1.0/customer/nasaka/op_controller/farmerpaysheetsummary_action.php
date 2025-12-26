<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/farmerpaysheetsummary_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['farmercode']=202;
    
    $_POST['villagecode']=0; */
    //$_POST['billperiodtransnumber']=1;
    //$_POST['circlecode']=0;
    $connection = swapp_connection();
    $farmerpaysheetsummary1 = new farmerpaysheetsummary($connection,265,1,7,'Farmer paysheet summary','Fpaysmr_000','A3','L');
    $farmerpaysheetsummary1->circlecode = $_POST['circlecode'];
    /*  
    $farmerpaysheetsummary1->villagecode = $_POST['villagecode'];
    $farmerpaysheetsummary1->farmercode = $_POST['farmercode'];*/
    $farmerpaysheetsummary1->billperiodtransnumber = $_POST['billperiodtransnumber']; 
    $farmerpaysheetsummary1->startreport();
    $farmerpaysheetsummary1->endreport();
?>