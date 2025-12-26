<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/brokertenderbalance_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $todate= $_POST["todate"];
    $goodscategorycode= $_POST["goodscategorycode"];
    $brokercode = $_POST["brokercode"];
    $connection = swapp_connection();
    $brokertenderbalance1 = new brokertenderbalance($connection,270);
    $todate = DateTime::createFromFormat('d/m/Y',$todate)->format('d-M-Y');
    $brokertenderbalance1->todate = $todate;
    $brokertenderbalance1->goodscategorycode = $goodscategorycode;
    $brokertenderbalance1->brokercode = $brokercode;
    $brokertenderbalance1->newpage(true);
    $brokertenderbalance1->detail();
    $brokertenderbalance1->endreport();
?>