<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once("../tcpdf/examples/tcpdf_include.php");
    require_once("../op_model/dailycrushing_report.php");
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $dailycrushing1 = new dailycrushing($connection,270);
    $dailycrushing1->slipdate = $_POST['Date'];
    $dailycrushing1->newpage(true);
    $dailycrushing1->detail();
    $dailycrushing1->endreport();
?>