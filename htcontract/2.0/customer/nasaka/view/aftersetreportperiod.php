<?php
	require("../info/phpsqlajax_dbinfo.php");
	require("../info/ncryptdcrypt.php");
	require("../info/phpgetlogin.php");

    $fromdate = DateTime::createFromFormat('d/m/Y',$_POST["fromdate"])->format('d-M-Y');
	$todate = DateTime::createFromFormat('d/m/Y',$_POST["todate"])->format('d-M-Y');
	
	if ($todate < $fromdate)
    {
    	echo 'Invalid date';
    	exit;
    }

    $_SESSION['fromdate'] = $fromdate;
    $_SESSION['todate'] = $todate;

    header("location: ../data/entitymenu.php");

?>