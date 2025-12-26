<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/contractorbusinessnew_report.php');
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
    $contractorbusiness1 = new contractorbusiness($connection,195,1,7,'Contractor Business','CONTRBUS_000','A4','L');
    $contractorbusiness1->fromdate = $_POST['From_Date'];
    $contractorbusiness1->todate = $_POST['To_Date'];
    $contractorbusiness1->billtypecode = $_POST['billtypecode'];
    if ($_POST['exportcsvfile']=='1')
    {
        $contractorbusiness1->export($_POST['billperiodtransnumber'],1);
    }
    else
    {
        $contractorbusiness1->startreport();
        $contractorbusiness1->endreport();
    }
?>