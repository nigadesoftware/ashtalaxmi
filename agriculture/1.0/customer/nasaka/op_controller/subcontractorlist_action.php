<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/subcontractorlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    require("../ip_model/report_db_oracle.php");

    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $reportcode = $_POST['reportcode'];
    $servicetrhrcategorycode = $_POST['servicetrhrcategorycode'];
    $contractorcode = $_POST['contractorcode'];
    $connection = swapp_connection();
    $report1 = new report($connection);
    $report1->reportcode = $reportcode;
    $report1->fetch();
    $subcontractorlist1 = new subcontractorlist($connection,290,1,7, $report1->papersize,$report1->orientation,
    $report1->reportname_uni,$report1->reportfile);
    $subcontractorlist1->reportcode=$reportcode;
    $subcontractorlist1->servicetrhrcategorycode=$servicetrhrcategorycode;
    $subcontractorlist1->contractorcode=$contractorcode;
    $subcontractorlist1->startreport();
    $subcontractorlist1->endreport();
?>