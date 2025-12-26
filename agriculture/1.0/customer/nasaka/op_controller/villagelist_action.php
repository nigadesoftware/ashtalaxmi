<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/villagelist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $village1 = new village($connection,275);
    $village1->circlecode = $_POST['circlecode'];
    $village1->distance = $_POST['Distance'];
    $village1->newpage(true);
    $village1->detail();
    $village1->endreport();
?>