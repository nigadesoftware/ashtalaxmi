<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/departmentdetaillist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $departmentdetaillist1 = new departmentdetaillist($connection,290);
//$departmentdetaillist1->bankcode = $_POST['bankcode'];
    $departmentdetaillist1->newpage(true);
    $departmentdetaillist1->startreport();
    $departmentdetaillist1->endreport();
?>