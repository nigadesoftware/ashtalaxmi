<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/purchaseorderregister_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $purchaseorderregister1 = new purchaseorderregister($connection,290);
    $purchaseorderregister1->fromdate = $_POST['From_Date'];
    $purchaseorderregister1->todate = $_POST['To_Date'];
    if ($_POST['exportcsvfile']==1)
    {
        $purchaseorderregister1->export();
    }
    else
    {
     $purchaseorderregister1->newpage(true);
    $purchaseorderregister1->startreport();
    $purchaseorderregister1->endreport();
    }
   
?>