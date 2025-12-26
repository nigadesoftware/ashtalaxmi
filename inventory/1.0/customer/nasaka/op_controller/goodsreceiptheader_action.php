<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/goodsreceiptheaderlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $goodsreceiptheaderlist1 = new goodsreceiptheaderlist($connection,290);
//$goodsreceiptheaderlist1->bankcode = $_POST['bankcode'];
    $goodsreceiptheaderlist1->newpage(true);
    $goodsreceiptheaderlist1->startreport();
    $goodsreceiptheaderlist1->endreport();
?>