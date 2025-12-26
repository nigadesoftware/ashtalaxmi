<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/sugarb2b_report.php');
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
    $sugarb2b1 = new sugarb2b($connection,270);
    $fromdate = DateTime::createFromFormat('d/m/Y',$fromdate)->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$todate)->format('d-M-Y');
    $sugarb2b1->fromdate = $fromdate;
    $sugarb2b1->todate = $todate;
    $sugarb2b1->goodscategorycode = $goodscategorycode;
    /*$sugarb2b1->newpage(true);
    $sugarb2b1->detail();
    $sugarb2b1->endreport();*/
    $sugarb2b1->export();
?>