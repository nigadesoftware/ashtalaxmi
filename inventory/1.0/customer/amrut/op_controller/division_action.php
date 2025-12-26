<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/divisionlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $divisionlist1 = new divisionlist($connection,290);
//$divisionlist1->bankcode = $_POST['bankcode'];
    $divisionlist1->newpage(true);
    $divisionlist1->startreport();
    $divisionlist1->endreport();
?>