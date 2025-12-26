<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/varietyplantationsummary_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /* if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    } */
    $connection = swapp_connection();
    $varietyplantationsummary1 = new varietyplantationsummary($connection,285,1,7,'Cane Varietywise Plantation','CNVARPLNT_000','A3','L');
    if ($_POST['From_Date']!='')
    $varietyplantationsummary1->fromdate = DateTime::createFromFormat('d/m/Y',$_POST['From_Date'])->format('d-M-Y');
    if ($_POST['To_Date']!='')
    $varietyplantationsummary1->todate = DateTime::createFromFormat('d/m/Y',$_POST['To_Date'])->format('d-M-Y');
    $varietyplantationsummary1->startreport();
    $varietyplantationsummary1->endreport();
?>