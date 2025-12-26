<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/sectionlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $sectionlist1 = new sectionlist($connection,290);
//$sectionlist1->bankcode = $_POST['bankcode'];
    $sectionlist1->newpage(true);
    $sectionlist1->startreport();
    $sectionlist1->endreport();
?>