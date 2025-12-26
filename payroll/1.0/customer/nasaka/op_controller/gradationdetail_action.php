<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/gradationdetaillist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $gradationdetaillist1 = new gradationdetaillist($connection,290);
//$gradationdetaillist1->bankcode = $_POST['bankcode'];
    $gradationdetaillist1->newpage(true);
    $gradationdetaillist1->startreport();
    $gradationdetaillist1->endreport();
?>