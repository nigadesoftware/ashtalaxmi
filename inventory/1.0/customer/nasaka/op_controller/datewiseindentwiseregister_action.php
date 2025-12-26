<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/datewiseindentwiseregister_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $consumption1 = new contractor($connection,290);   
    $consumption1->season = $_POST['Season']; 
    if($consumption1->season=="")
    {
        $consumption1->season=$_SESSION["yearperiodcode"];
    }  
    $consumption1->startreport();
    $consumption1->endreport();
?>