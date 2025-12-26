<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/tenderwisesaledetail_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $fromdate= $_POST["fromdate"];
    $todate= $_POST["todate"];
    $goodscategorycode= $_POST["goodscategorycode"];
    $brokercode = $_POST["brokercode"];
    $connection = swapp_connection();
    $tenderwisesaledetail1 = new tenderwisesaledetail($connection,270);
    $fromdate = DateTime::createFromFormat('d/m/Y',$fromdate)->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$todate)->format('d-M-Y');
    $tenderwisesaledetail1->fromdate = $fromdate;
    $tenderwisesaledetail1->todate = $todate;
    $tenderwisesaledetail1->goodscategorycode = $goodscategorycode;
    $tenderwisesaledetail1->newpage(true);
    $tenderwisesaledetail1->detail();
    $tenderwisesaledetail1->endreport();
?>