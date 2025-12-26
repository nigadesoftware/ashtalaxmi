<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/issuetypelist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $issuetypelist1 = new issuetypelist($connection,290);
//$issuetypelist1->bankcode = $_POST['bankcode'];
    $issuetypelist1->newpage(true);
    $issuetypelist1->startreport();
    $issuetypelist1->endreport();
?>