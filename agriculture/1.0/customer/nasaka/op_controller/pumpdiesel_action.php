<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/pumpdiesel_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $pumpdiesel1 = new pumpdiesel($connection,290);
    $pumpdiesel1->pumpcode = $_POST['pumpcode'];
    $pumpdiesel1->fromdate = $_POST['From_Date'];
    $pumpdiesel1->todate = $_POST['To_Date'];
    $pumpdiesel1->typecode = $_POST['contractortypecode'];
    $pumpdiesel1->newpage(true);
    if ($_POST['pumpcode']!='' and $_POST['exportcsvfile']==1)
    {
        $pumpdiesel1->pumpdieselexport();
    }
    else
    {
        $pumpdiesel1->startreport();
        $pumpdiesel1->endreport();
    }
?>