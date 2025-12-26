<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/goodsreceiptitemdetaillist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $goodsreceiptitemdetaillist1 = new goodsreceiptitemdetaillist($connection,290);
//$goodsreceiptitemdetaillist1->bankcode = $_POST['bankcode'];
    $goodsreceiptitemdetaillist1->newpage(true);
    $goodsreceiptitemdetaillist1->startreport();
    $goodsreceiptitemdetaillist1->endreport();
?>