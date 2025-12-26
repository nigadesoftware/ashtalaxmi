<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once("../tcpdf/examples/tcpdf_include.php");
    require_once("../op_model/advancecrushing_report.php");
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $advancecrushing1 = new advancecrushing($connection,200);
    $advancecrushing1->slipdate = $_POST['Date'];
    $advancecrushing1->newpage(true);
    $advancecrushing1->detail();
    $advancecrushing1->endreport();
?>