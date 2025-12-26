<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/purchaseorderreg_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $purchaseorderregister1 = new purchaseorderregister($connection,290);
    $purchaseorderregister1->frompo = $_POST['From_PO'];
    $purchaseorderregister1->topo = $_POST['To_PO'];
    if ($_POST['exportcsvfile']==1)
    {
        $purchaseorderregister1->export();
    }
    /* else
    {
     $purchaseorderregister1->newpage(true);
    $purchaseorderregister1->startreport();
    $purchaseorderregister1->endreport();
    } */
   
?>