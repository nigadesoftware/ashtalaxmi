<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/bankbranchlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $bankbranchlist1 = new bankbranchlist($connection,290);
    $bankbranchlist1->bankcode = $_POST['bankcode'];
    $bankbranchlist1->newpage(true);
    $bankbranchlist1->detail();
    $bankbranchlist1->endreport();
?>