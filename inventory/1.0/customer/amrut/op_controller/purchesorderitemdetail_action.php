<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/purchesorderitemdetaillist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $purchesorderitemdetaillist1 = new purchesorderitemdetaillist($connection,290);
//$purchesorderitemdetaillist1->bankcode = $_POST['bankcode'];
    $purchesorderitemdetaillist1->newpage(true);
    $purchesorderitemdetaillist1->startreport();
    $purchesorderitemdetaillist1->endreport();
?>