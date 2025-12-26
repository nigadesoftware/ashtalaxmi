<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/substorelist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $substorelist1 = new substorelist($connection,290);
//$substorelist1->bankcode = $_POST['bankcode'];
    $substorelist1->newpage(true);
    $substorelist1->startreport();
    $substorelist1->endreport();
?>