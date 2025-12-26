<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once("../tcpdf/examples/tcpdf_include.php");
    require_once("../op_model/circlemembercrushing_report.php");
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $circlemembercrushing1 = new circlemembercrushing($connection,260);
    $circlemembercrushing1->fromdate = $_POST['From_Date'];
    $circlemembercrushing1->todate = $_POST['To_Date'];
    //$circlemembercrushing1->newpage(true);
    $circlemembercrushing1->startreport();
    $circlemembercrushing1->endreport();
?>