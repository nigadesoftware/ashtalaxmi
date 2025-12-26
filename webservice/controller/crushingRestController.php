<?php
	require_once("..\handler\crushingRestHandler.php");
	$view = "";
	$fromdate = "";
	$todate = "";
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
	if ($view == 'sectioncrushing')
	{
		if(isset($_GET["todate"]))
		{
			$todate = $_GET["todate"];
		}
		else
		{
			break;
		}
	}
	elseif ($view == 'shiftvehiclecrushing')
	{
		if(isset($_GET["todate"]))
		{
			$todate = $_GET["todate"];
		}
		else
		{
			break;
		}
	}
	else
	{
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
	}
	
	/*
	controls the RESTful services
	URL mapping
	*/
	// to handle REST Url /mobile/list/
	switch ($view)
	{
		case 'shiftwise':
		{
			$crushingRestHandler = new crushingRestHandler();
			$crushingRestHandler->getcrushingrecord($fromdate,$todate,$view);
			break;
		}
		case 'vehiclewise':
		{
			$crushingRestHandler = new crushingRestHandler();
			$crushingRestHandler->getcrushingrecord($fromdate,$todate,$view);
			break;
		}
		case 'vehiclewiseshiftwise':
		{
			$crushingRestHandler = new crushingRestHandler();
			$crushingRestHandler->getcrushingrecord($fromdate,$todate,$view);
			break;
		}
		case 'sectioncrushing':
		{
			$crushingRestHandler = new crushingRestHandler();
			$crushingRestHandler->getsectioncrushingrecord($todate,$view);
			break;
		}
		case 'shiftvehiclecrushing':
		{
			$crushingRestHandler = new crushingRestHandler();
			$crushingRestHandler->getshiftvehiclecrushingrecord($todate,$view);
			break;
		}
	}
?>