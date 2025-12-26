<?php
	require_once("..\handler\mtoeRestHandler.php");
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
	else if ($_SERVER['PHP_AUTH_USER'] == 'test' and $_SERVER['PHP_AUTH_PW'] == 'Te$t@123')
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
    if(isset($_GET["name"]))
	{
		$name = $_GET["name"];
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
		case 'marathitoenglish':
		{
			$mtoeRestHandler1 = new mtoeRestHandler();
			$mtoeRestHandler1->convert($name);
			break;
		}
	}
?>