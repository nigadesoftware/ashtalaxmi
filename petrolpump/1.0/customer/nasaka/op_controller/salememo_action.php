<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/salememo_report.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $transactionid = $_POST["transactionid"];
    
    $connection = petrolpump_connection();
	$salememo1 = new salememo($connection,120);
    $salememo1->transactionid = $transactionid;
    
    $salememo1->newpage(true);
    $salememo1->detail();
    $salememo1->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 5, 'color' => array(0,0,0)));
    $salememo1->pdf->line(0,$salememo1->liney+8,200,$salememo1->liney+8);
    $salememo1->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
    $salememo1->pageheader($salememo1->liney+10);
    $salememo1->detail();
    $salememo1->endreport();

?>