<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/advancetermlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $advancetermlist1 = new advancetermlist($connection,290);
//$advancetermlist1->bankcode = $_POST['bankcode'];
    $advancetermlist1->newpage(true);
    $advancetermlist1->startreport();
    $advancetermlist1->endreport();
?>