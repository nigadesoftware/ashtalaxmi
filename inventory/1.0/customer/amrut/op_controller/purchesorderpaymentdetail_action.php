<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/purchesorderpaymentdetaillist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $purchesorderpaymentdetaillist1 = new purchesorderpaymentdetaillist($connection,290);
//$purchesorderpaymentdetaillist1->bankcode = $_POST['bankcode'];
    $purchesorderpaymentdetaillist1->newpage(true);
    $purchesorderpaymentdetaillist1->startreport();
    $purchesorderpaymentdetaillist1->endreport();
?>