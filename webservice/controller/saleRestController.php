<?php
	require_once("..\handler\saleRestHandler.php");
	$view = "";
	$fromdate = "";
	$todate = "";
	$categoryid = 0;
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
	if(isset($_GET["categoryid"]))
	{
		$categoryid = $_GET["categoryid"];
	}
	else
	{
		break;
	}
	/*
	controls the RESTful services
	URL mapping
	*/
	// to handle REST Url /mobile/list/
	switch ($view)
	{
		case 'sale':
		{
			$saleRestHandler = new saleRestHandler();
			$saleRestHandler->getsalerecord($fromdate,$todate,$categoryid);
			break;
		}
	}
?>