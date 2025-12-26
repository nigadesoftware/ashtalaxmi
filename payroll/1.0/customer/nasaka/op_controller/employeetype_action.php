<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/employeetypelist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $employeetypelist1 = new employeetypelist($connection,290);
//$employeetypelist1->bankcode = $_POST['bankcode'];
    $employeetypelist1->newpage(true);
    $employeetypelist1->startreport();
    $employeetypelist1->endreport();
?>