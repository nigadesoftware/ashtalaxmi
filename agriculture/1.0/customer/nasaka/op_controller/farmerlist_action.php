<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/farmerlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $farmer1 = new farmerlist($connection,290);
    $farmer1->divisioncode = $_POST['divisioncode'];
    $farmer1->circlecode = $_POST['circlecode'];
    $farmer1->villagecode = $_POST['villagecode'];
    $farmer1->farmercategorycode = $_POST['farmercategorycode'];
    $farmer1->bankbranchcode = $_POST['bankbranchcode'];
    $farmer1->startreport();
    $farmer1->endreport();
?>