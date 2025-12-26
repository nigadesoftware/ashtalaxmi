<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/purchasersaledetail_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $fromdate= $_POST["fromdate"];
    $todate= $_POST["todate"];
    $purchasercode= $_POST["purchasercode"];
    $connection = swapp_connection();
    $purchasersaledetail1 = new purchasersaledetail($connection,270);
    $fromdate = DateTime::createFromFormat('d/m/Y',$fromdate)->format('d-M-Y');
    $todate = DateTime::createFromFormat('d/m/Y',$todate)->format('d-M-Y');
    $purchasersaledetail1->fromdate = $fromdate;
    $purchasersaledetail1->todate = $todate;
    if ($purchasercode !=0)
    $purchasersaledetail1->purchasercode = $purchasercode;
    $purchasersaledetail1->newpage(true);
    $purchasersaledetail1->detail();
    $purchasersaledetail1->endreport();
?>