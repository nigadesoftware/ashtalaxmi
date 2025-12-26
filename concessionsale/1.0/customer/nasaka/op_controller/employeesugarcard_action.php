<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/employeesugarcard_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $employeesugarcard1 = new employeesugarcard($connection,290,1,7,'','MSUGCRD_000');
    $employeesugarcard1->startreport();
    $employeesugarcard1->endreport();
?>