<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/farmerdeduction_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['farmercode']=202;
    $_POST['villagecode']=0; */
    //$_POST['billperiodtransnumber']=1;
    //$_POST['dedcode']=1;
    //$_POST['bankbranchcode']=0;
    $connection = swapp_connection();
    $farmerdeduction1 = new farmerdeduction($connection,285,1,7,'HT Deduction Report','HTded_000','A4','P');
    $farmerdeduction1->dedcode = $_POST['deductioncode'];
    //$farmerdeduction1->branchcode = $_POST['bankbranchcode'];
    $farmerdeduction1->billperiodtransnumber = $_POST['billperiodtransnumber'];
    $farmerdeduction1->startreport();
    $farmerdeduction1->endreport();
?>