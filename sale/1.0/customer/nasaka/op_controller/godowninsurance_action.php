<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/godowninsurance_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $fromdate= $_POST["fromdate"];
    $todate= $_POST["todate"];
    $connection = swapp_connection();
    $godowninsurancereport1 = new godowninsurancereport($connection,270);
    $fromdate = DateTime::createFromFormat('d/m/Y',$fromdate)->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$todate)->format('d-M-Y');
    $godowninsurancereport1->fromdate = $fromdate;
    $godowninsurancereport1->todate = $todate;
    $godowninsurancereport1->goodscategorycode = $_POST["goodscategorycode"];
    //$godowninsurancereport1->newpage(true);
    $godowninsurancereport1->detail();
    $godowninsurancereport1->endreport();
?>