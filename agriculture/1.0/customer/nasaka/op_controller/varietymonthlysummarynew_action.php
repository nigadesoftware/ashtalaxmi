<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/varietymonthlysummarynew_report.php');
    require_once('../ip_model/circle_db_oracle.php');
    require("../info/phpsqlajax_dbinfo.php");
    /* if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    } */
    $connection = swapp_connection();
    $varietymonthlysummarynew1 = new varietymonthlysummarynew($connection,285,1,7,'Cane Varietywise Plantation','CNVARPLNT_000','A3','L');
    if ($_POST['From_Date']!='')
    $varietymonthlysummarynew1->fromdate = DateTime::createFromFormat('d/m/Y',$_POST['From_Date'])->format('d-M-Y');
    if ($_POST['To_Date']!='')
    $varietymonthlysummarynew1->todate = DateTime::createFromFormat('d/m/Y',$_POST['To_Date'])->format('d-M-Y');
    $varietymonthlysummarynew1->divisioncode = $_POST['divisioncode'];
    $varietymonthlysummarynew1->plantationcategorycode = $_POST['plantationcategorycode'];
    
    if ($varietymonthlysummarynew1->divisioncode == 1)
        $varietymonthlysummarynew1->divisionname = 'कार्यक्षेत्र';
    elseif ($varietymonthlysummarynew1->divisioncode == 2)
        $varietymonthlysummarynew1->divisionname = 'गेटकेन';
    else
        $varietymonthlysummarynew1->divisionname = 'सर्व';

    if ($varietymonthlysummarynew1->plantationcategorycode == 1)
        $varietymonthlysummarynew1->plantationcategoryname = 'लागण';
    elseif ($varietymonthlysummarynew1->plantationcategorycode == 2)
        $varietymonthlysummarynew1->plantationcategoryname = 'खोडवा';
    else
        $varietymonthlysummarynew1->plantationcategoryname = 'सर्व';

    if ($_POST['exportcsvfile']=='1')
    {
        $varietymonthlysummarynew1->lowyr=($_SESSION['yearperiodcode']-20002)%100;
        $varietymonthlysummarynew1->curyr=($_SESSION['yearperiodcode']-10001)%100;
        $varietymonthlysummarynew1->export();
    }
    else
    {
        $varietymonthlysummarynew1->startreport();
        $varietymonthlysummarynew1->endreport();
    }    
?>