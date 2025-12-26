<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/supplierlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $supplierlist1 = new supplierlist($connection,290);
//$supplierlist1->bankcode = $_POST['bankcode'];
    $supplierlist1->newpage(true);
    $supplierlist1->startreport();
    $supplierlist1->endreport();
?>