<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/farmernetpaymentsummary_report.php');
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
    $farmernetpaymentsummary1 = new farmernetpaymentsummary($connection,265,1,7,'Farmer Net Payment Summary','Fnetpay_000','A4','P');
    $farmernetpaymentsummary1->bankcode = $_POST['bankcode'];
    $farmernetpaymentsummary1->branchcode = $_POST['bankbranchcode'];
    $farmernetpaymentsummary1->billperiodtransnumber = $_POST['billperiodtransnumber'];
    $farmernetpaymentsummary1->startreport();
    $farmernetpaymentsummary1->endreport();
?>