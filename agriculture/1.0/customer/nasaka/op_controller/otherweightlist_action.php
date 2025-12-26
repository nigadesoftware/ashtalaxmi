<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/otherweightlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $otherweightlist1 = new otherweightlist($connection,205);
    $otherweightlist1->othmatcategorycode = $_POST['othmatcategorycode'];
    $otherweightlist1->fromdate = $_POST['From_Date'];
    $otherweightlist1->todate = $_POST['To_Date'];
    $otherweightlist1->purchasercode = $_POST['purchasercode'];
    $otherweightlist1->startreport();
    $otherweightlist1->endreport();
?>