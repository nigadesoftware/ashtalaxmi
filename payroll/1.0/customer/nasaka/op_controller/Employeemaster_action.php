<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/employeemasterlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $employeemasterlist1 = new employeemasterlist($connection,290);
//$employeemasterlist1->bankcode = $_POST['bankcode'];
    $employeemasterlist1->newpage(true);
    $employeemasterlist1->startreport();
    $employeemasterlist1->endreport();
?>