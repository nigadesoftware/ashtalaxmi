<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once("../tcpdf/examples/tcpdf_include.php");
    require_once("../op_model/smartcardprinting_report.php");
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $smartcardprinting1 = new smartcardprinting($connection,200);
    //$smartcardprinting1->newpage(true);
    $smartcardprinting1->startreport();
    $smartcardprinting1->endreport();
?>