<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/openingstockslist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $openingstockslist1 = new openingstockslist($connection,290);
//$openingstockslist1->bankcode = $_POST['bankcode'];
    $openingstockslist1->newpage(true);
    $openingstockslist1->startreport();
    $openingstockslist1->endreport();
?>