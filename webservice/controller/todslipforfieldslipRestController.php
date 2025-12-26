<?php
	require_once("../handler/todslipforfieldslipRestHandler.php");
	$view = "";
	$slipboyuserid = "";
	if (!isset($_SERVER['PHP_AUTH_USER']))
	{
		header('WWW-Authenticate: Basic realm="My Realm"');
		header('HTTP/1.0 401 Unauthorized');
		echo 'Text to send if user hits Cancel button';
		exit;
	}
	else if ($_SERVER['PHP_AUTH_USER'] == 'makai' and $_SERVER['PHP_AUTH_PW'] == 'Makai@123')
	{
	}
	else
	{
		header('WWW-Authenticate: Basic realm="My Realm"');
		header('HTTP/1.0 401 Unauthorized');
		echo 'Text to send if user hits Cancel button';
		exit;
	}
	if(isset($_GET["view"]))
	{
		$view = $_GET["view"];
	}
	else
	{
		header('WWW-Authenticate: Basic realm="My Realm"');
		header('HTTP/1.0 401 Unauthorized');
		echo 'Text to send if user hits Cancel button';
		exit;
	}
	if(isset($_GET["slipboyuserid"]))
	{
		$slipboyuserid = $_GET["slipboyuserid"];
	}
	else
	{
		$slipboyuserid =0;
	}
    /*
	controls the RESTful services
	URL mapping
	*/
	// to handle REST Url /mobile/list/
	switch ($view)
	{
		case 'download':
		{
			$todslipforfieldslipRestHandler1 = new todslipforfieldslipRestHandler();
			$todslipforfieldslipRestHandler1->getpendingtodslip($view,$slipboyuserid);
			break;
		}
	}
?>