<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once("../tcpdf/examples/tcpdf_include.php");
    require_once("../op_model/fieldslip_report.php");
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $fieldslip1 = new fieldslip($connection,200);
    $fieldslip1->transactionnumber = $_POST['transactionnumber'];
    $fieldslip1->newpage(true);
    $fieldslip1->detail();
    $fieldslip1->endreport();
?>