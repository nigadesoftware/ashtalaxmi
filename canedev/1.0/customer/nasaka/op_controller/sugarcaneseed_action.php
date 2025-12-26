<?php
    ///require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/sugarcaneseed_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['farmercode']=202;*/
    //$_POST['code']=1; 
    $connection = swapp_connection();
    $sugarcaneseed1 = new sugarcaneseed($connection,265,1,7,'Sugar Cane Seed Report','Seed_000','A4','P');
    $sugarcaneseed1->from_date = $_POST['From_Date'];
    $sugarcaneseed1->to_date = $_POST['To_Date'];
    $sugarcaneseed1->startreport();
    $sugarcaneseed1->endreport();
?>