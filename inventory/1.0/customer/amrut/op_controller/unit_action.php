<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/unitlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $unitlist1 = new unitlist($connection,290);
//$unitlist1->bankcode = $_POST['bankcode'];
    $unitlist1->newpage(true);
    $unitlist1->startreport();
    $unitlist1->endreport();
?>