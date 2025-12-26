<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/crushinglicense_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $crushinglicense1 = new crushinglicense($connection,285);
    $crushinglicense1->seasoncode = $_POST['Season'];
    $crushinglicense1->farmercategorycode = $_POST['farmercategorycode'];
    $crushinglicense1->export();
?>