<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/invoicegrndetaillist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $invoicegrndetaillist1 = new invoicegrndetaillist($connection,290);
//$invoicegrndetaillist1->bankcode = $_POST['bankcode'];
    $invoicegrndetaillist1->newpage(true);
    $invoicegrndetaillist1->startreport();
    $invoicegrndetaillist1->endreport();
?>