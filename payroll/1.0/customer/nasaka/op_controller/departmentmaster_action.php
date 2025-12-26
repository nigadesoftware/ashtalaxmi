<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/departmentmasterlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $departmentmasterlist1 = new departmentmasterlist($connection,290);
//$departmentmasterlist1->bankcode = $_POST['bankcode'];
    $departmentmasterlist1->newpage(true);
    $departmentmasterlist1->startreport();
    $departmentmasterlist1->endreport();
?>