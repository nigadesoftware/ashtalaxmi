<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/vehiclepresentee_report.php');
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
    $vehiclepresentee1 = new vehiclepresentee($connection,285,1,7,'Vehicle Presentee','VEHPRES_000','A3','L');
    $vehiclepresentee1->contractorcategorycode = $_POST['contractorcategorycode'];
    $vehiclepresentee1->servicetrhrcategorycode = $_POST['servicetrhrcategorycode'];
    $vehiclepresentee1->contractcategorycode = $_POST['contractcategorycode'];
    $vehiclepresentee1->fromdate = $_POST['From_Date'];
    $vehiclepresentee1->todate = $_POST['To_Date'];
    $vehiclepresentee1->code = $_POST['code'];
    $vehiclepresentee1->startreport();
    $vehiclepresentee1->endreport();
?>