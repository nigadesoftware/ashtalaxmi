<?php
	require_once("..\handler\productionRestHandler.php");
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
		exit;
	}
	if(isset($_GET["fromdate"]))
	{
		$fromdate = $_GET["fromdate"];
	}
	else
	{
		exit;
	}
	if(isset($_GET["todate"]))
	{
		$todate = $_GET["todate"];
	}
	else
	{
		exit;
	}
	if(isset($_GET["categoryid"]))
	{
		$categoryid = $_GET["categoryid"];
	}
	else
	{
		exit;
	}
	/*
	controls the RESTful services
	URL mapping
	*/
	// to handle REST Url /mobile/list/
	switch ($view)
	{
		case 'production':
		{
			$productionRestHandler = new productionRestHandler();
			$productionRestHandler->getproductionrecord($fromdate,$todate,$categoryid);
			break;
		}
	}
?>