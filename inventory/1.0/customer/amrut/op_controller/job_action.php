<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/joblist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $joblist1 = new joblist($connection,290);
//$unitlist1->bankcode = $_POST['bankcode'];
    $joblist1->newpage(true);
    $joblist1->startreport();
    $joblist1->endreport();
?>