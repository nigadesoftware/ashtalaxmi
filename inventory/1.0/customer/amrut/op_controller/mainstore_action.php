<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/mainstorelist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $mainstorelist1 = new mainstorelist($connection,290);
    //$mainstorelist1->bankcode = $_POST['bankcode'];
    $mainstorelist1->newpage(true);
    $mainstorelist1->startreport();
    $mainstorelist1->endreport();
?>