<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/approvalmemo_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $approvalmemo1 = new approvalmemo($connection,290);
    $approvalmemo1->transactionnumber = $_POST['transactionnumber'];
    $approvalmemo1->newpage(true);
    $approvalmemo1->startreport();
    $approvalmemo1->endreport();
?>