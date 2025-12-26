<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/mainstorewisesupplierwisesmry_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $mainstorelist1 = new mainstorewisesmry($connection,290);
    $mainstorelist1->fromdate = $_POST['From_Date'];
    $mainstorelist1->todate = $_POST['To_Date'];
    $mainstorelist1->mainstorecode=$_POST['mainstorecode'];
    $mainstorelist1->startreport();
    $mainstorelist1->endreport();
?>