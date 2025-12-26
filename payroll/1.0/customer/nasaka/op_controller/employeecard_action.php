<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/employeecard_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $employee1 = new employeecardlist($connection,290);
    $employee1->transactionnumber = 1;
  //  $enquiry1->suppliercode = 1;
    $employee1->startreport();
    $employee1->endreport();
?>