<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/periodicalhttonnage_report.php');
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
    $periodicalhttonnage1 = new periodicalhttonnage($connection,210,1,7,'HT Contractor Tonnage','HTCONTR_000','A4','L');
    $periodicalhttonnage1->contractorcategorycode = $_POST['contractorcategorycode'];
    $periodicalhttonnage1->servicetrhrcategorycode = $_POST['servicetrhrcategorycode'];
    $periodicalhttonnage1->contractcategorycode = $_POST['contractcategorycode'];
    $periodicalhttonnage1->fromdate = $_POST['From_Date'];
    $periodicalhttonnage1->todate = $_POST['To_Date'];
    $periodicalhttonnage1->tslhtcontractortonnageexport = $_POST['tslhtcontractortonnageexport'];

    if ($_POST['exportcsvfile']=='1')
    {
        $periodicalhttonnage1->tslhtcontractortonnageexport();
    }
    else
    {
    if ($periodicalhttonnage1->tslhtcontractortonnageexport==1)
        $periodicalhttonnage1->tslhtcontractortonnageexport();
    else
    {
        $periodicalhttonnage1->startreport();
        $periodicalhttonnage1->endreport();
    }
    }
?>