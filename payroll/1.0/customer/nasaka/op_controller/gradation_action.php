<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/gradationlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $gradationlist1 = new gradationlist($connection,290);
//$gradationlist1->bankcode = $_POST['bankcode'];
    $gradationlist1->newpage(true);
    $gradationlist1->startreport();
    $gradationlist1->endreport();
?>