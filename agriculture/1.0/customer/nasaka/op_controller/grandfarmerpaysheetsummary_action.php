<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/grandfarmerpaysheetsummary_report.php');
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
    $grandfarmerpaysheetsummary1 = new grandfarmerpaysheetsummary($connection,265,1,7,'All Gat Farmer paysheet summary','Allgatfsmr_000','A4','P');
    $grandfarmerpaysheetsummary1->circlecode = $_POST['circlecode'];
    /*  
    $farmerpaysheetsummary1->villagecode = $_POST['villagecode'];
    $farmerpaysheetsummary1->farmercode = $_POST['farmercode'];*/
    $grandfarmerpaysheetsummary1->billperiodtransnumber = $_POST['billperiodtransnumber']; 
    $grandfarmerpaysheetsummary1->startreport();
    $grandfarmerpaysheetsummary1->endreport();
?>