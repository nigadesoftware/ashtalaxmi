<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once("../tcpdf/examples/tcpdf_include.php");
    require_once("../op_model/daybook_report.php");
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $daybook1 = new daybook($connection,280,1,7,'Day Book','DAYBOOK_000','A3','L');
    $daybook1->slipdate = $_POST['Date'];
    $daybook1->farmercategorycode = $_POST['farmercategorycode'];
    $daybook1->shiftcode = $_POST['Shift'];
    $daybook1->vehiclecategorycode = $_POST['vehiclecategorycode'];
    $daybook1->startreport();
    $daybook1->endreport();
?>