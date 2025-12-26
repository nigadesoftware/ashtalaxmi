<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/sugarcaneseedinvoice_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['farmercode']=202;*/
    //$_POST['code']=1; 
    //$_POST['from_date']='01/08/2020';
    //$_POST['to_date']='30/08/2020';

    $connection = swapp_connection();
    $sugarcaneseedinvoice1 = new sugarcaneseedinvoice($connection,195,1,7,'Sugar Cane Seed Invoice','SEEDINV_000','A4','L');
    $sugarcaneseedinvoice1->transactionnumber = $_POST['transactionnumber'];
    $sugarcaneseedinvoice1->startreport();
    $sugarcaneseedinvoice1->endreport();
?>