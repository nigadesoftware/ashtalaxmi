<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/departmentlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $departmentlist1 = new departmentlist($connection,290);
//$departmentlist1->bankcode = $_POST['bankcode'];
    $departmentlist1->newpage(true);
    $departmentlist1->startreport();
    $departmentlist1->endreport();
?>