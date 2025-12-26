<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/castecategorylist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $castecategorylist1 = new castecategorylist($connection,290);
//$castecategorylist1->bankcode = $_POST['bankcode'];
    $castecategorylist1->newpage(true);
    $castecategorylist1->startreport();
    $castecategorylist1->endreport();
?>