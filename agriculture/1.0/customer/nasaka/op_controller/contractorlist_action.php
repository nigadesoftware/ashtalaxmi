<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/contractorlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    require("../ip_model/report_db_oracle.php");

    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $reportcode = $_POST['reportcode'];
    $connection = swapp_connection();
    $report1 = new report($connection);
    $report1->reportcode = $reportcode;
    $report1->fetch();
    $contractorlist1 = new contractorlist($connection,200,1,7, $report1->papersize,$report1->orientation,
    $report1->reportname_uni,$report1->reportfile);
    $contractorlist1->reportcode=$reportcode;
    $contractorlist1->newpage(true);
    $contractorlist1->detail();
    $contractorlist1->endreport();
?>