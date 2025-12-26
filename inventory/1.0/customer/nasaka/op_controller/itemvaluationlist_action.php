<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/itemvaluationlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $itemvaluationlist1 = new itemvaluationlist($connection,290);
    $itemvaluationlist1->mainstorecode = $_POST['mainstorecode'];
    $itemvaluationlist1->substorecode = $_POST['substorecode'];
    $itemvaluationlist1->fromdate = $_POST['From_Date'];
    $itemvaluationlist1->todate = $_POST['To_Date'];
    if ($_POST['exportcsvfile']==1)
    {
        $itemvaluationlist1->export();
    }
    else {
        $itemvaluationlist1->newpage(true);
        $itemvaluationlist1->startreport();
        $itemvaluationlist1->endreport();

    }
?>