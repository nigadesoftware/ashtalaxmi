<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/farmertypepaymentsummary_report.php');
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
    $farmertypepaymentsummary1 = new farmertypepaymentsummary($connection,275,1,7,'Farmer Type Paysheet Summary','FTPAYSUM_000','A3','L');
    $farmertypepaymentsummary1->farmercategorycode = $_POST['farmercategorycode'];
    $farmertypepaymentsummary1->billperiodtransnumber = $_POST['billperiodtransnumber']; 
    $farmertypepaymentsummary1->startreport();
    $farmertypepaymentsummary1->endreport();
?>