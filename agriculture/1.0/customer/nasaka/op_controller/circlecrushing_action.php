<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once("../tcpdf/examples/tcpdf_include.php");
    require_once("../op_model/circlecrushing_report.php");
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $circlecrushing1 = new circlecrushing($connection,200);
    $circlecrushing1->slipdate = $_POST['Date'];
    $circlecrushing1->shiftcode = $_POST['Shift'];
    $circlecrushing1->newpage(true);
    $circlecrushing1->detail();
    $circlecrushing1->endreport();
?>