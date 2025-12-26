<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/sectionmasterlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $sectionmasterlist1 = new sectionmasterlist($connection,290);
    //sectionmasterlist1->bankcode = $_POST['bankcode'];
    $sectionmasterlist1->newpage(true);
    $sectionmasterlist1->startreport();
    $sectionmasterlist1->endreport();
?>