<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/transporterledger_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $ledger1 = new ledger($connection,290);
     $ledger1->contractorcode =$_POST['subcontractorcode'];
    $ledger1->billcategorycode = $_POST['billtypecode'];
    
 
    
    $ledger1->startreport();
    $ledger1->endreport();
?>