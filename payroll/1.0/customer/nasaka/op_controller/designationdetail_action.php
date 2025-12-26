<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/designationdetaillist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $designationdetaillist1 = new designationdetaillist($connection,290);
//$designationdetailist1->bankcode = $_POST['bankcode'];
    $designationdetaillist1->newpage(true);
    $designationdetaillist1->startreport();
    $designationdetaillist1->endreport();
?>