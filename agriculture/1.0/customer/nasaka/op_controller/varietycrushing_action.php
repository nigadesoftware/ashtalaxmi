<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once("../tcpdf/examples/tcpdf_include.php");
    require_once("../op_model/varietycrushing_report.php");
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $varietycrushing1 = new varietycrushing($connection,200);
    $varietycrushing1->slipdate = $_POST['Date'];
    $varietycrushing1->newpage(true);
    $varietycrushing1->detail();
    $varietycrushing1->endreport();
?>