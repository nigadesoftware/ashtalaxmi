<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/farmersocietydeduction_report.php');
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
    $farmersocietydeduction1 = new farmersocietydeduction($connection,285,1,7,'Farmer Society Deduction Report','Fsocded_000','A4','P');
    $farmersocietydeduction1->bankbranchcode = $_POST['bankbranchcode'];
    $farmersocietydeduction1->societycode = $_POST['societycode'];
    $farmersocietydeduction1->billperiodtransnumber = $_POST['billperiodtransnumber'];
    $farmersocietydeduction1->startreport();
    $farmersocietydeduction1->endreport();
?>