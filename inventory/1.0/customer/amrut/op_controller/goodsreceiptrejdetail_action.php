<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/goodsreceiptrejdetaillist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $goodsreceiptrejdetaillist1 = new goodsreceiptrejdetaillist($connection,290);
//$goodsreceiptrejdetaillist1->bankcode = $_POST['bankcode'];
    $goodsreceiptrejdetaillist1->newpage(true);
    $goodsreceiptrejdetaillist1->startreport();
    $goodsreceiptrejdetaillist1->endreport();
?>