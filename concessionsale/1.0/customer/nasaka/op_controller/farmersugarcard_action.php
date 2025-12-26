<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/farmersugarcard_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $farmersugarcard1 = new farmersugarcard($connection,290,1,7,'','FSUGCRD_000');
    $farmersugarcard1->farmercode = $_POST['farmercode'];
    $farmersugarcard1->villagecode = $_POST['villagecode'];
    $farmersugarcard1->circlecode = $_POST['circlecode'];
    $farmersugarcard1->centrecode = $_POST['centrecode'];
    $farmersugarcard1->upddate = $_POST['Date'];
    $farmersugarcard1->startreport();
    $farmersugarcard1->endreport();
?>