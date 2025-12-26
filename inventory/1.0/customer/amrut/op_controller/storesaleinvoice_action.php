<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/storesaleinvoice_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $transactionnumber= $_POST["transactionnumber"];
    
    $connection = swapp_connection();
	$storesaleinvoice1 = new storesaleinvoice($connection,285);
    $storesaleinvoice1->transactionnumber = $transactionnumber;
    $storesaleinvoice1->farmercode = $_POST['farmercode'];
   
    
    //$storesaleinvoice1->newpage(true);
    $storesaleinvoice1->startreport();
    $storesaleinvoice1->endreport();
?>