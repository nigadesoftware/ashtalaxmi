<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/designationlist_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $designationlist1 = new designationlist($connection,290);
//$designationlist1->bankcode = $_POST['bankcode'];
    $designationlist1->newpage(true);
    $designationlist1->startreport();
    $designationlist1->endreport();
?>