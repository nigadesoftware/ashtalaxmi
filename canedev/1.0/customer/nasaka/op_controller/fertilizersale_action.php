<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/fertilizersale_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['farmercode']=202;*/
    //$_POST['salecatcode']=1; 
    //$_POST['fromdate']='01/08/2020';
    //$_POST['todate']='10/08/2020';
    $connection = swapp_connection();
    $fertilizersale1 = new fertilizersale($connection,200,1,7,'Fertilizer Sale Report','Ferti_000','A4','L');
    $fertilizersale1->fromdate = $_POST['From_Date'];
    $fertilizersale1->todate = $_POST['To_Date'];
    $fertilizersale1->salecategorycode = $_POST['salecategorycode'];
    $fertilizersale1->centrecode = $_POST['centrecode'];
    $fertilizersale1->subcategorycode = $_POST['subcategorycode'];
    $fertilizersale1->startreport();
    $fertilizersale1->endreport();
?>