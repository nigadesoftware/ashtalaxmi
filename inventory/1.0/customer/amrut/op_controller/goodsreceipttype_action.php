<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/goodsreceipttypelist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $goodsreceipttypelist1 = new goodsreceipttypelist($connection,290);
//$goodsreceipttypelist1->bankcode = $_POST['bankcode'];
    $goodsreceipttypelist1->newpage(true);
    $goodsreceipttypelist1->startreport();
    $goodsreceipttypelist1->endreport();
?>