<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/varietymonthlysummary_report.php');
    require_once('../ip_model/circle_db_oracle.php');
    require("../info/phpsqlajax_dbinfo.php");
    /* if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    } */
    $connection = swapp_connection();
    $varietymonthlysummary1 = new varietymonthlysummary($connection,285,1,7,'Cane Varietywise Plantation','CNVARPLNT_000','A3','L');
    if ($_POST['From_Date']!='')
    $varietymonthlysummary1->fromdate = DateTime::createFromFormat('d/m/Y',$_POST['From_Date'])->format('d-M-Y');
    if ($_POST['To_Date']!='')
    $varietymonthlysummary1->todate = DateTime::createFromFormat('d/m/Y',$_POST['To_Date'])->format('d-M-Y');
    $varietymonthlysummary1->circlecode = $_POST['circlecode'];
    if ($_POST['circlecode']!=0)
    {
        $circle1=new circle($connection);
        $circle1->circlecode=$_POST['circlecode'];
        $circle1->fetch();
        $varietymonthlysummary1->circlename=$circle1->circlenameuni;
    }
    else
    {
        $varietymonthlysummary1->circlename='सर्व';
    }
    $varietymonthlysummary1->startreport();
    $varietymonthlysummary1->endreport();
?>