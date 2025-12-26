<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/localrequisitionheader_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $localrequisitionheader1 = new localrequisitionheader($connection,290);
//$localrequisitionheader1->bankcode = $_POST['bankcode'];
    $localrequisitionheader1->newpage(true);
    $localrequisitionheader1->startreport();
    $localrequisitionheader1->endreport();
?>