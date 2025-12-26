<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once("../tcpdf/examples/tcpdf_include.php");
    require_once("../op_model/caneyardbalance_report.php");
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $caneyardbalance1 = new caneyardbalance($connection,200);
    $caneyardbalance1->balancedate = $_POST['Date'];
    $caneyardbalance1->balancehour = $_POST['Hour'];
    $caneyardbalance1->newpage(true);
    $caneyardbalance1->detail();
    $caneyardbalance1->endreport();
?>