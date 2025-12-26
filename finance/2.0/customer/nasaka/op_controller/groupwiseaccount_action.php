<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/groupwiseaccount_checklist.php');
    if (isaccessible(357451254865478)==0)
    {
        echo 'Communication Error';
        exit;
    }
    require("../info/phpsqlajax_dbinfo.php");
    set_time_limit(0);
    $connection = swapp_connection();
	$groupwiseaccount1 = new groupwiseaccount($connection,275);
    
    $groupwiseaccount1->newpage(true);
    $groupwiseaccount1->detail();
    $groupwiseaccount1->endreport();
?>