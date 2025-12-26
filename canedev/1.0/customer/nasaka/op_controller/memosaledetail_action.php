<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/memosaledetail_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $fromdate= $_POST["fromdate"];
    $todate= $_POST["todate"];
    $goodscategorycode= $_POST["goodscategorycode"];
    $connection = swapp_connection();
    $memo1 = new memosaledetail($connection,195);
    $fromdate = DateTime::createFromFormat('d/m/Y',$fromdate)->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$todate)->format('d-M-Y');
    $memo1->fromdate = $fromdate;
    $memo1->todate = $todate;
    $memo1->goodscategorycode = $goodscategorycode;
    $memo1->newpage(true);
    $memo1->detail();
    $memo1->endreport();
?>