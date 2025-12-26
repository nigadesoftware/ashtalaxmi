<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/memo_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $transactionnumber= $_POST["transactionnumber"];
    
    $connection = swapp_connection();
	$memo1 = new memo($connection,270);
    $memo1->transactionnumber = $transactionnumber;
    
    $memo1->newpage(true);
    $memo1->detail();
    $memo1->endreport();
?>