<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once("../tcpdf/examples/tcpdf_include.php");
    require_once("../op_model/itemconsumption_report.php");
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $itemconsumption1 = new itemconsumption($connection,290);
    $itemconsumption1->fromdate =$_POST['From_Date'];
    $itemconsumption1->todate = $_POST['To_Date'];
    if ($itemconsumption1->fromdate!='' and $itemconsumption1->todate!='')
    {
        $itemconsumption1->fromdate = DateTime::createFromFormat('Y-m-d',$itemconsumption1->fromdate)->format('d-M-Y');
        $itemconsumption1->todate = DateTime::createFromFormat('Y-m-d',$itemconsumption1->todate)->format('d-M-Y');

    }
    $itemconsumption1->newpage(true);
    $itemconsumption1->startreport();
    $itemconsumption1->endreport();
?>