<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/acletter_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $acletter1 = new acletter($connection,290);
    $acletter1->circlecode = $_POST['circlecode'];
    $acletter1->farmercode = $_POST['farmercode'];
    $acletter1->letterdate = $_POST['Date'];
    $acletter1->reference = $_POST['Narration'];
    if ($_POST['exportcsvfile']==1)
    {
        $acletter1->shares=0;
    }
    else
    {
        $acletter1->shares=1;
    }
    $acletter1->newpage(true);
    $acletter1->startreport();
    $acletter1->endreport();
?>