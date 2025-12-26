<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once("../tcpdf/examples/tcpdf_include.php");
    require_once("../op_model/contractorbalancelist_report.php");
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $list1 = new contractorbalancelist($connection,290);
    $list1->contractorcode = $_POST['contractorcode'];
    $list1->subcontractorcode = $_POST['subcontractorcode'];
    $list1->deductioncode = $_POST['deductioncode'];
    $list1->newpage(true);
    $list1->startreport();
    $list1->endreport();
?>