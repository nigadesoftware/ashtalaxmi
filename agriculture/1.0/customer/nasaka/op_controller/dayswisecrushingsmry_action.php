<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once("../tcpdf/examples/tcpdf_include.php");
    require_once("../op_model/dayswisecrushingsmry_report.php");
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $circlecrushing1 = new circlevillagecrushing($connection,290);
    $circlecrushing1->fromdate = $_POST['From_Date'];
    $circlecrushing1->todate = $_POST['To_Date'];
   
    $circlecrushing1->newpage(true);
    $circlecrushing1->startreport();
    $circlecrushing1->endreport();
?>