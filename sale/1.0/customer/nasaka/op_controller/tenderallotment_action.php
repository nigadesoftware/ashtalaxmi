<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/tenderallotment_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $fromdate= $_POST["fromdate"];
    $todate= $_POST["todate"];
    $goodscategorycode= $_POST["goodscategorycode"];
    $tendertransactionnumber= $_POST["tendertransactionnumber"];
    $connection = swapp_connection();
    $tenderallotment1 = new tenderallotment($connection,270);
    $fromdate = DateTime::createFromFormat('d/m/Y',$fromdate)->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$todate)->format('d-M-Y');
    $tenderallotment1->fromdate = $fromdate;
    $tenderallotment1->todate = $todate;
    $tenderallotment1->goodscategorycode = $goodscategorycode;
    $tenderallotment1->tendertransactionnumber = $tendertransactionnumber;
    $tenderallotment1->newpage(true);
    $tenderallotment1->detail();
    $tenderallotment1->endreport();
?>