<?php
	require_once("../handler/plotformeasurementRestHandler.php");
	$view = "";
	$measurementuserid = "";
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
	if(isset($_GET["measurementuserid"]))
	{
		$measurementuserid = $_GET["measurementuserid"];
	}
	else
	{
		$measurementuserid =0;
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
			$plotformeasurementRestHandler1 = new plotformeasurementRestHandler();
			$plotformeasurementRestHandler1->getpendingformeasurementplot($view,$measurementuserid);
			break;
		}
		case 'uploadcount':
			{
				if(isset($_GET["seasoncode"]))
				{
					$seasoncode = $_GET["seasoncode"];
				}
				else
				{
					$seasoncode =0;
				}
				if(isset($_GET["plotnumber"]))
				{
					$plotnumber = $_GET["plotnumber"];
				}
				else
				{
					$plotnumber =0;
				}
				$plotformeasurementRestHandler1 = new plotformeasurementRestHandler();
				$plotformeasurementRestHandler1->getuploadcount($view,$seasoncode,$plotnumber);
				break;
			}
	}
?>