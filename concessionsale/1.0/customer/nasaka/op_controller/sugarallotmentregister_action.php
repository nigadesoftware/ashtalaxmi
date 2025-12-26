<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/sugarallotmentregister_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $sugarallotmentregister1 = new sugarallotmentregister($connection,195,1,7,'','SUGALTREG_000');
    $sugarallotmentregister1->seasoncode = $_SESSION['yearperiodcode'];
    $sugarallotmentregister1->circlecode = $_POST['circlecode'];
    $sugarallotmentregister1->upddate = $_POST['Date'];
    $sugarallotmentregister1->startreport();
    $sugarallotmentregister1->endreport();
?>