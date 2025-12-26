<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/mainsubvaluationlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $mainsubvaluationlist1 = new mainsubvaluationlist($connection,290);
    $mainsubvaluationlist1->mainstorecode = $_POST['mainstorecode'];
    $mainsubvaluationlist1->fromdate = $_POST['From_Date'];
    $mainsubvaluationlist1->todate = $_POST['To_Date'];
    if ($_POST['exportcsvfile']==1)
    {
        $mainsubvaluationlist1->export();
    }
    else 
    {
        $mainsubvaluationlist1->newpage(true);
        $mainsubvaluationlist1->startreport();
        $mainsubvaluationlist1->endreport();

    }
?>