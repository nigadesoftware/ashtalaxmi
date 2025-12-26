<?php
	require_once("..\handler\weighmentRestHandler.php");
	$view = "";
	$fromdate = "";
	$todate = "";
	$farmercode = 0;
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
		break;
	}
	if(isset($_GET["fromdate"]))
	{
		$fromdate = $_GET["fromdate"];
	}
	else
	{
		break;
	}
	if(isset($_GET["todate"]))
	{
		$todate = $_GET["todate"];
	}
	else
	{
		break;
	}
	if(isset($_GET["farmercode"]))
	{
		$farmercode = $_GET["farmercode"];
	}
	if(isset($_GET["totalrecords"]))
	{
		$totalrecords = $_GET["totalrecords"];
	}
	if(isset($_GET["currentpage"]))
	{
		$currentpage = $_GET["currentpage"];
	}
	else
	{
		$currentpage = 1;
	}
	if(isset($_GET["recordperpage"]))
	{
		$recordperpage = $_GET["recordperpage"];
	}
	else
	{
		$recordperpage = 10;
	}
	/*
	controls the RESTful services
	URL mapping
	*/
	// to handle REST Url /mobile/list/
	switch ($view)
	{
		case 'farmertonnage':
		{
			$weighmentRestHandler = new weighmentRestHandler();
			$weighmentRestHandler->getfarmerweighmentrecord($fromdate,$todate,$farmercode,$totalrecords,$currentpage,$recordperpage);
			break;
		}
		case 'recordtotal':
		{
			$weighmentRestHandler = new weighmentRestHandler();
			$weighmentRestHandler->getrecordtotal($fromdate,$todate,$farmercode);
			break;
		}
	}
?>