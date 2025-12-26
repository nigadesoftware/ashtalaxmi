<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once("../tcpdf/examples/tcpdf_include.php");
    require_once("../op_model/harvestedarea_report.php");
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $harvestedarea1 = new harvestedarea($connection,290,1,7,'Harvested Area','HARAR_000','A4','P');
    $harvestedarea1->fromdate = $_POST['From_Date'];
    $harvestedarea1->todate = $_POST['To_Date'];
    $harvestedarea1->startreport();
    $harvestedarea1->endreport();
?>