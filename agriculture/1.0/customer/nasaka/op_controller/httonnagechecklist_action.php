<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/httonnagechecklist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['farmercode']=202;
    $_POST['paysheetperiodtransnumber']=1;
    $_POST['circlecode']=0;
    $_POST['villagecode']=0; */
    $connection = swapp_connection();
    $httonnagechecklist1 = new httonnagechecklist($connection,270,1,7,'Farmer paysheet list','HTTONCHLST_000','A4','P');
    $httonnagechecklist1->servicetrhrcategorycode = $_POST['servicetrhrcategorycode'];
    $httonnagechecklist1->fromdate = $_POST['From_Date'];
    $httonnagechecklist1->todate = $_POST['To_Date'];
    $httonnagechecklist1->contractorcode = $_POST['contractorcode'];
    $httonnagechecklist1->startreport();
    $httonnagechecklist1->endreport();
?>