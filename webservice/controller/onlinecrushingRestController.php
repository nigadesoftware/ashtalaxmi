<?php
	require_once("..\handler\onlinecrushingRestHandler.php");
	$view = "";
	$fromdate = "";
	$todate = "";
	$offset=1;
	$rowsperpage=10;
	$farmercode=0;
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
	else
	{
		$farmercode =0;
	}
	if ($_GET['view'] == 'onlinecrushing')
	{
		if(isset($_GET["offset"]))
		{
			$offset = $_GET["offset"];
		}
		else
		{
			break;
		}
		if(isset($_GET["rowsperpage"]))
		{
			$rowsperpage = $_GET["rowsperpage"];
		}
		else
		{
			break;
		}
	}
    /*
	controls the RESTful services
	URL mapping
	*/
	// to handle REST Url /mobile/list/
	switch ($view)
	{
		case 'onlinecrushing':
		{
			$crushingRestHandler = new onlinecrushingRestHandler();
			$crushingRestHandler->getonlinecrushingrecord($fromdate,$todate,$farmercode,$offset,$rowsperpage,$view);
			break;
		}
		case 'totalrecords':
		{
			$crushingRestHandler = new onlinecrushingRestHandler();
			$crushingRestHandler->gettotalrecords($fromdate,$todate,$farmercode,$view);
			break;
		}
	}
?>