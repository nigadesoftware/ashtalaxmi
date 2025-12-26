<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/farmernetpayment_report.php');
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
    $farmernetpayment1 = new farmernetpayment($connection,265,1,7,'Farmer Net Payment','Fnetpay_000','A4','P');
    $farmernetpayment1->bankcode = $_POST['bankcode'];
    $farmernetpayment1->bankbranchcode = $_POST['bankbranchcode'];
    $farmernetpayment1->billperiodtransnumber = $_POST['billperiodtransnumber'];
    $farmernetpayment1->startreport();
    $farmernetpayment1->endreport();
?>