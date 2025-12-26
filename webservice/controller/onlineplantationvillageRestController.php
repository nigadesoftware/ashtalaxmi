<?php
	require_once("..\handler\onlineplantationvillageRestHandler.php");
	$view = "";
	$seasonyear = "";
	$villagecode = "";
	$offset=1;
	$rowsperpage=10;
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
		break;header('WWW-Authenticate: Basic realm="My Realm"');
		header('HTTP/1.0 401 Unauthorized');
		echo 'Text to send if user hits Cancel button';
		exit;
	}
	if ($_GET['view'] == 'villagebysectionlist')
	{
		if(isset($_GET["sectioncode"]))
		{
			$sectioncode = $_GET["sectioncode"];
		}
		else
		{
			break;
		}
	}
	if ($_GET['view'] == 'onlineplantation' 
	or $_GET['view'] == 'totalrecords' 
	or $_GET['view'] == 'sectvillhan')
	{
		if(isset($_GET["seasonyear"]))
		{
			$seasonyear = $_GET["seasonyear"];
		}
		else
		{
			break;
		}
		if(isset($_GET["villagecode"]))
		{
			$villagecode = $_GET["villagecode"];
		}
		else
		{
			break;
		}
	}
	elseif ($_GET['view'] == 'secthan') 
	{
		if(isset($_GET["seasonyear"]))
		{
			$seasonyear = $_GET["seasonyear"];
		}
		else
		{
			break;
		}
	}
	
	if ($_GET['view'] == 'onlineplantation')
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
		case 'onlineplantation':
		{
			$plantationRestHandler = new onlineplantationRestHandler();
			$plantationRestHandler->getonlineplantationrecord($seasonyear,$villagecode,$offset,$rowsperpage,$view);
			break;
		}
		case 'totalrecords':
		{
			$plantationRestHandler = new onlineplantationRestHandler();
			$plantationRestHandler->gettotalrecords($seasonyear,$villagecode,$view);
			break;
		}
		case 'sectvillhan':
		{
			$plantationRestHandler = new onlineplantationRestHandler();
			$plantationRestHandler->getsectvillhanrecord($seasonyear,$villagecode,$view);
			break;
		}
		case 'secthan':
		{
			$plantationRestHandler = new onlineplantationRestHandler();
			$plantationRestHandler->getsecthanrecord($seasonyear,$view);
			break;
		}
		case 'villagelist':
		{
			$plantationRestHandler = new onlineplantationRestHandler();
			$plantationRestHandler->getvillagelist($view);
			break;
		}
		case 'villagebysectionlist':
		{
			$plantationRestHandler = new onlineplantationRestHandler();
			$plantationRestHandler->getvillagebysectionlist($sectioncode,$view);
			break;
		}
		case 'seasonyearlist':
		{
			$plantationRestHandler = new onlineplantationRestHandler();
			$plantationRestHandler->getseasonyearlist($view);
			break;
		}
	}
?>