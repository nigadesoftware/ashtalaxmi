<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/releaseorderbalance_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $goodscategorycode= $_POST["goodscategorycode"];
    $permissiontransactionnumber = $_POST["permissiontransactionnumber"];
    $connection = swapp_connection();
    $releaseorderbalance1 = new releaseorderbalance($connection,270);
    $releaseorderbalance1->goodscategorycode = $goodscategorycode;
    $releaseorderbalance1->permissiontransactionnumber = $permissiontransactionnumber;
    $releaseorderbalance1->newpage(true);
    $releaseorderbalance1->detail();
    $releaseorderbalance1->endreport();
?>