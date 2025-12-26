<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/circleplantationsummary_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /* if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    } */
    $connection = swapp_connection();
    $circleplantationsummary1 = new circleplantationsummary($connection,285,1,7,'Cane Varietywise Plantation','CNVARPLNT_000','A4','P');
    if ($_POST['From_Date']!='')
    $circleplantationsummary1->fromdate = DateTime::createFromFormat('d/m/Y',$_POST['From_Date'])->format('d-M-Y');
    if ($_POST['To_Date']!='')
    $circleplantationsummary1->todate = DateTime::createFromFormat('d/m/Y',$_POST['To_Date'])->format('d-M-Y');
    $circleplantationsummary1->startreport();
    $circleplantationsummary1->endreport();
?>