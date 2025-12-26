<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/employeelist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $employeelist1 = new employeelist($connection,290);
//$employeelist1->bankcode = $_POST['bankcode'];
    $employeelist1->newpage(true);
    $employeelist1->startreport();
    $employeelist1->endreport();
?>