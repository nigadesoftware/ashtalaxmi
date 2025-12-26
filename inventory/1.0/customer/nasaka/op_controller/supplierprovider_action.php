<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/supplierproviderlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $supplierproviderlist1 = new supplierproviderlist($connection,290);
//$supplierproviderlist1->bankcode = $_POST['bankcode'];
    $supplierproviderlist1->newpage(true);
    $supplierproviderlist1->startreport();
    $supplierproviderlist1->endreport();
?>