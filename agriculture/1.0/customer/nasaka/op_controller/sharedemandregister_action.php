<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/sharedemandregister_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $sharedemandregister1 = new sharedemandregister($connection,290);
    $sharedemandregister1->circlecode = $_POST['circlecode'];
    $sharedemandregister1->farmercode = $_POST['farmercode'];
    $sharedemandregister1->letterdate = $_POST['Date'];
    $sharedemandregister1->reference = $_POST['Narration'];
    
    //$sharedemandregister1->newpage(true);
    $sharedemandregister1->detail();
    $sharedemandregister1->endreport();
?>