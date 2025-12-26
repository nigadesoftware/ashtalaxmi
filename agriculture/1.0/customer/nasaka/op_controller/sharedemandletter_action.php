<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/sharedemandletter_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $sharedemandletter1 = new sharedemandletter($connection,290);
    $sharedemandletter1->circlecode = $_POST['circlecode'];
    $sharedemandletter1->farmercode = $_POST['farmercode'];
    $sharedemandletter1->letterdate = $_POST['Date'];
    $sharedemandletter1->reference = $_POST['Narration'];
    if ($_POST['exportcsvfile']==1)
    {
        $sharedemandletter1->shares=0;
    }
    else
    {
        $sharedemandletter1->shares=1;
    }
    $sharedemandletter1->newpage(true);
    $sharedemandletter1->startreport();
    $sharedemandletter1->endreport();
?>