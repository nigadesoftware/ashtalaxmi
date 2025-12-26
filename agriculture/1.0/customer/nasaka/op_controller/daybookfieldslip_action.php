<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once("../tcpdf/examples/tcpdf_include.php");
    require_once("../op_model/daybookfieldslip_report.php");
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $daybook1 = new daybookfieldslip($connection,265);
    $daybook1->fromdate = $_POST['From_Date'];
    $daybook1->todate = $_POST['To_Date'];
    $daybook1->newpage(true);
    $daybook1->detail();
    $daybook1->endreport();
?>