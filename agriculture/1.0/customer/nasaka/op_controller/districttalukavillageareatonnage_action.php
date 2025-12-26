<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/districttalukavillageareatonnage_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $districttalukavillageareatonnage1 = new districttalukavillageareatonnage($connection,290);
    $districttalukavillageareatonnage1->seasoncode = $_POST['Season'];
    $districttalukavillageareatonnage1->divisioncode = $_POST['divisioncode'];
    $districttalukavillageareatonnage1->startreport();
    $districttalukavillageareatonnage1->endreport();
?>