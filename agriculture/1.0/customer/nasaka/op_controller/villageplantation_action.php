<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/villageplantation_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $villageplantation1 = new villageplantation($connection,285,1,7,'Villagewise Plantation','VILPLNT_000','A4','P');
    $villageplantation1->centrecode = $_POST['centrecode'];
    if ($_POST['From_Date']!='')
    $villageplantation1->fromdate = DateTime::createFromFormat('d/m/Y',$_POST['From_Date'])->format('d-M-Y');
    if ($_POST['To_Date']!='')
    $villageplantation1->todate = DateTime::createFromFormat('d/m/Y',$_POST['To_Date'])->format('d-M-Y');
    $villageplantation1->farmercategorycode = $_POST['farmercategorycode'];
    $villageplantation1->startreport();
    $villageplantation1->endreport();
?>