<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/htdeduction_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['htcode']=202;
    $_POST['villagecode']=0; */
    //$_POST['billperiodtransnumber']=1;
    //$_POST['dedcode']=1;
    //$_POST['bankbranchcode']=0;
    $connection = swapp_connection();
    $htdeduction1 = new htdeduction($connection,285,1,7,'Farmer Deduction Report','Fded_000','A4','P');
    $htdeduction1->dedcode = $_POST['dedcode'];
    //$htdeduction1->branchcode = $_POST['bankbranchcode'];
    $htdeduction1->billperiodtransnumber = $_POST['billperiodtransnumber'];
    $htdeduction1->startreport();
    $htdeduction1->endreport();
?>