<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/itemlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $itemlist1 = new itemlist($connection,270);
//$itemlist1->bankcode = $_POST['bankcode'];
    //$itemlist1->newpage(true);
    $itemlist1->startreport();
    $itemlist1->endreport();
?>