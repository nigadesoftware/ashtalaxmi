<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/caneyardtoken_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $caneyardtoken1 = new caneyardtoken($connection,200);
    $caneyardtoken1->transactionnumber = $_POST['transactionnumber'];
    $caneyardtoken1->tokenbasecode = $_POST['tokenbasecode'];
    $caneyardtoken1->newpage(true);
    $caneyardtoken1->detail();
    $caneyardtoken1->endreport();
?>