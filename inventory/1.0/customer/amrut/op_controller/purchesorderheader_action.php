<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/purchesorderheaderlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $purchesorderheaderlist1 = new purchesorderheaderlist($connection,290);
//$purchesorderheaderlist1->bankcode = $_POST['bankcode'];
    $purchesorderheaderlist1->newpage(true);
    $purchesorderheaderlist1->startreport();
    $purchesorderheaderlist1->endreport();
?>