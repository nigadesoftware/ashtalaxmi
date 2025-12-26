<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/employeetypedetaillist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $employeetypedetaillist1 = new employeetypedetaillist($connection,290);
//$employeetypelist1->bankcode = $_POST['bankcode'];
    $employeetypedetaillist1->newpage(true);
    $employeetypedetaillist1->startreport();
    $employeetypedetaillist1->endreport();
?>