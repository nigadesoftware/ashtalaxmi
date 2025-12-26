<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/negotiationquotationdetaillist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $negotiationquotationdetaillist1 = new negotiationquotationdetaillist($connection,290);
//$negotiationquotationdetaillist1->bankcode = $_POST['bankcode'];
    $negotiationquotationdetaillist1->newpage(true);
    $negotiationquotationdetaillist1->startreport();
    $negotiationquotationdetaillist1->endreport();
?>