<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/dieselregister_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $connection = swapp_connection();
    $dieselregister1 = new dieselregister($connection,290);
    //if ($_POST['From_Date']!='')
    $dieselregister1->fromdate = $_POST['From_Date'];
    if ($_POST['To_Date']=='')
    $dieselregister1->todate = $_POST['From_Date'];
    else
    $dieselregister1->todate = $_POST['To_Date'];
  
    $dieselregister1->startreport();
    $dieselregister1->endreport();
?>