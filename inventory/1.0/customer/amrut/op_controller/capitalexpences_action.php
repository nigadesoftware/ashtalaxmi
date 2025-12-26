<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/capitalexpenceslist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $capitalexpenceslist1 = new capitalexpenceslist($connection,290);
//$capitalexpenceslist1->bankcode = $_POST['bankcode'];
    $capitalexpenceslist1->newpage(true);
    $capitalexpenceslist1->startreport();
    $capitalexpenceslist1->endreport();
?>