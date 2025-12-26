<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/farmertypenetpaymentsummary_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['farmercode']=202;
    $_POST['villagecode']=0; */
    //$_POST['billperiodtransnumber']=1;
    //$_POST['bankcode']=0;
    //$_POST['bankbranchcode']=0;
    $connection = swapp_connection();
    $farmertypenetpaymentsummary1 = new farmertypenetpaymentsummary($connection,195,1,7,'Farmer Net Payment','FRRTGS_000','A4','P');
    $farmertypenetpaymentsummary1->billperiodtransnumber = $_POST['billperiodtransnumber'];
    $farmertypenetpaymentsummary1->startreport();
    $farmertypenetpaymentsummary1->endreport();
    
?>